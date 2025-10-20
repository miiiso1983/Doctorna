<?php
namespace App\Controllers\Api;

use App\Core\DB;
use PDO;

class AppointmentController extends BaseApiController
{
    // POST /api/v1/patient/appointments  JSON: {doctor_id, slot_id, notes}
    public function create(): void
    {
        $claims = $this->requireJwt(['patient']);
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $doctorId = (int)($input['doctor_id'] ?? 0);
        $slotId = (int)($input['slot_id'] ?? 0);
        $notes = (string)($input['notes'] ?? '');
        if ($doctorId <= 0 || $slotId <= 0) {
            $this->error('doctor_id and slot_id are required', 422);
        }
        $pdo = DB::conn($this->config);
        try {
            $pdo->beginTransaction();
            // lock slot
            $stmt = $pdo->prepare('SELECT id, starts_at FROM doctor_time_slots WHERE id = ? AND doctor_id = ? AND is_booked = 0 FOR UPDATE');
            $stmt->execute([$slotId, $doctorId]);
            $slot = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$slot) {
                $pdo->rollBack();
                $this->error('Slot unavailable', 409);
            }
            $pdo->prepare('UPDATE doctor_time_slots SET is_booked = 1 WHERE id = ?')->execute([$slotId]);
            $appointmentDate = (string)$slot['starts_at'];

            // patient_id by user_id
            $stmt = $pdo->prepare('SELECT id FROM patients WHERE user_id = ? LIMIT 1');
            $stmt->execute([(int)$claims['sub']]);
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$patient) { $pdo->rollBack(); $this->error('Patient profile not found', 404); }

            $stmt = $pdo->prepare('INSERT INTO appointments (patient_id, doctor_id, appointment_date, notes, status) VALUES (?, ?, ?, ?, "pending")');
            $stmt->execute([(int)$patient['id'], $doctorId, $appointmentDate, $notes]);
            $appointmentId = (int)$pdo->lastInsertId();
            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $this->error('Error creating appointment', 500);
        }
        $this->ok(['id' => $appointmentId, 'message' => 'Appointment requested']);
    }

    // GET /api/v1/appointments  (patient's own appointments)
    public function index(): void
    {
        $claims = $this->requireJwt(['patient']);
        $pdo = DB::conn($this->config);
        [$page,$per,$offset] = $this->paginationParams();
        $sql = 'SELECT a.id, a.appointment_date, a.status, du.name AS doctor_name
                FROM appointments a
                JOIN doctors d ON d.id = a.doctor_id
                JOIN users du ON du.id = d.user_id
                WHERE a.patient_id = (SELECT id FROM patients WHERE user_id = ?)
                ORDER BY a.appointment_date DESC LIMIT :per OFFSET :off';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, (int)$claims['sub'], PDO::PARAM_INT);
        $stmt->bindValue(':per', $per, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->ok($rows, ['page'=>$page,'per_page'=>$per,'count'=>count($rows)]);
    }

}

