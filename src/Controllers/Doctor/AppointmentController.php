<?php
namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Core\DB;

class AppointmentController extends Controller
{
    public function index(): void
    {
        $this->requireAuth(['doctor']);
        $pdo = DB::conn($this->config);
        $sql = 'SELECT a.id, a.appointment_date, a.status, u.name AS patient_name
                FROM appointments a
                JOIN patients p ON p.id = a.patient_id
                JOIN users u ON u.id = p.user_id
                WHERE a.doctor_id = (SELECT id FROM doctors WHERE user_id = ?)
                ORDER BY a.appointment_date ASC LIMIT 100';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user']['id']]);
        $rows = $stmt->fetchAll();

        // notify nothing; just respond
        $this->response->json(['data' => $rows]);
    }

    public function updateStatus(): void
    {
        $this->requireAuth(['doctor']);
        $id = (int)$this->request->post['id'];
        $status = (string)$this->request->post['status']; // accepted|rejected|completed|cancelled
        $pdo = DB::conn($this->config);
        $sql = 'UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = (SELECT id FROM doctors WHERE user_id = ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $id, $_SESSION['user']['id']]);

        // On acceptance, try to send WhatsApp confirmation from doctor to patient
        if ($status === 'accepted') {
            $info = $pdo->prepare('SELECT a.appointment_date, pu.phone AS patient_phone FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users pu ON pu.id=p.user_id WHERE a.id=?');
            $info->execute([$id]);
            $row = $info->fetch();
            if ($row && !empty($row['patient_phone'])) {
                $text = \App\Core\Lang::t('wa.appt.accepted', ['date' => (string)$row['appointment_date']]);
                (new \App\Services\WhatsApp($this->config))->sendFromDoctor($pdo, (int)$_SESSION['user']['id'], (string)$row['patient_phone'], $text);
            }
        }
        else if ($status === 'rejected' || $status === 'cancelled' || $status === 'completed') {
            $info = $pdo->prepare('SELECT a.appointment_date, pu.phone AS patient_phone FROM appointments a JOIN patients p ON p.id=a.patient_id JOIN users pu ON pu.id=p.user_id WHERE a.id=?');
            $info->execute([$id]);
            $row = $info->fetch();
            if ($row && !empty($row['patient_phone'])) {
                $map = [
                    'rejected' => 'wa.appt.rejected',
                    'cancelled' => 'wa.appt.cancelled',
                    'completed' => 'wa.appt.completed',
                ];
                $key = $map[$status] ?? '';
                if ($key) {
                    $text = \App\Core\Lang::t($key, ['date' => (string)$row['appointment_date']]);
                    (new \App\Services\WhatsApp($this->config))->sendFromDoctor($pdo, (int)$_SESSION['user']['id'], (string)$row['patient_phone'], $text);
                }
            }
        }

        $this->response->json(['message' => 'Updated']);
    }
}

