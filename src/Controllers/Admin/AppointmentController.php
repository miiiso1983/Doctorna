<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class AppointmentController extends Controller
{
    private function guard(): void
    {
        $this->requireAuth(['super_admin']);
    }

    public function index(): void
    {
        $this->guard();
        $pdo = DB::conn($this->config);

        $status = (string)($this->request->get['status'] ?? '');
        $doctorId = (int)($this->request->get['doctor_id'] ?? 0);
        $patientId = (int)($this->request->get['patient_id'] ?? 0);
        $from = (string)($this->request->get['from'] ?? '');
        $to = (string)($this->request->get['to'] ?? '');

        $where = [];
        $params = [];
        if ($status !== '') { $where[] = 'a.status = ?'; $params[] = $status; }
        if ($doctorId > 0) { $where[] = 'a.doctor_id = ?'; $params[] = $doctorId; }
        if ($patientId > 0) { $where[] = 'a.patient_id = ?'; $params[] = $patientId; }
        if ($from !== '') { $where[] = 'a.appointment_date >= ?'; $params[] = $from . ' 00:00:00'; }
        if ($to !== '') { $where[] = 'a.appointment_date <= ?'; $params[] = $to . ' 23:59:59'; }
        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

        $sql = "SELECT a.id, a.appointment_date, a.status,
                       du.name AS doctor_name, pu.name AS patient_name
                FROM appointments a
                JOIN doctors d ON d.id = a.doctor_id
                JOIN users du ON du.id = d.user_id
                JOIN patients p ON p.id = a.patient_id
                JOIN users pu ON pu.id = p.user_id
                $whereSql
                ORDER BY a.appointment_date DESC
                LIMIT 500";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        // For filters
        $doctors = $pdo->query('SELECT d.id, u.name FROM doctors d JOIN users u ON u.id = d.user_id ORDER BY u.name')->fetchAll();
        $patients = $pdo->query('SELECT p.id, u.name FROM patients p JOIN users u ON u.id = p.user_id ORDER BY u.name')->fetchAll();

        $this->view('admin/appointments/index', [
            'title' => \App\Core\Lang::t('admin.appointments'),
            'rows' => $rows,
            'filters' => compact('status','doctorId','patientId','from','to'),
            'doctors' => $doctors,
            'patients' => $patients,
        ]);
    }

    public function updateStatus(): void
    {
        $this->guard();
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $id = (int)$this->request->post['id'];
        $status = (string)$this->request->post['status'];
        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('UPDATE appointments SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
        $this->response->redirect($this->request->baseUrl() . '/admin/appointments');
    }

    public function exportCsv(): void
    {
        $this->guard();
        $pdo = DB::conn($this->config);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="appointments.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID','Date','Status','Doctor','Patient']);
        $sql = "SELECT a.id, a.appointment_date, a.status, du.name AS doctor_name, pu.name AS patient_name
                FROM appointments a
                JOIN doctors d ON d.id = a.doctor_id
                JOIN users du ON du.id = d.user_id
                JOIN patients p ON p.id = a.patient_id
                JOIN users pu ON pu.id = p.user_id
                ORDER BY a.appointment_date DESC LIMIT 2000";
        foreach ($pdo->query($sql) as $r) {
            fputcsv($out, [$r['id'], $r['appointment_date'], $r['status'], $r['doctor_name'], $r['patient_name']]);
        }
        fclose($out);
    }
}

