<?php
namespace App\Controllers\Api;

use App\Core\DB;
use PDO;

class DoctorAppointmentsController extends BaseApiController
{
    // GET /api/v1/doctor/appointments
    public function index(): void
    {
        $claims = $this->requireJwt(['doctor']);
        $pdo = DB::conn($this->config);
        $sql = 'SELECT a.id, a.appointment_date, a.status, u.name AS patient_name
                FROM appointments a
                JOIN patients p ON p.id = a.patient_id
                JOIN users u ON u.id = p.user_id
                WHERE a.doctor_id = (SELECT id FROM doctors WHERE user_id = ?)
                ORDER BY a.appointment_date ASC LIMIT 100';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([(int)$claims['sub']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->response->json(['data' => $rows]);
    }

    // POST /api/v1/doctor/appointments/status  JSON: {id, status}
    public function updateStatus(): void
    {
        $claims = $this->requireJwt(['doctor']);
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $id = (int)($input['id'] ?? 0);
        $status = (string)($input['status'] ?? ''); // accepted|rejected|completed|cancelled
        if ($id <= 0 || $status === '') { $this->response->json(['error' => 'id and status required'], 422); return; }

        $pdo = DB::conn($this->config);
        $sql = 'UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = (SELECT id FROM doctors WHERE user_id = ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$status, $id, (int)$claims['sub']]);

        // Optional: send WhatsApp notifications similar to web controller (omitted for brevity)
        $this->response->json(['message' => 'Updated']);
    }
}

