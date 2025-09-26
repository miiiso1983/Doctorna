<?php
namespace App\Controllers\Patient;

use App\Core\Controller;
use App\Core\DB;
use PDO;

class AppointmentController extends Controller
{
    public function create(): void
    {
        $this->requireAuth(['patient']);
        $doctorId = (int)$this->request->post['doctor_id'];
        $slotId = (int)($this->request->post['slot_id'] ?? 0);
        $notes = (string)($this->request->post['notes'] ?? '');

        $pdo = DB::conn($this->config);
        $appointmentId = 0;
        try {
            $pdo->beginTransaction();
            if ($slotId > 0) {
                // lock and mark the slot if available
                $stmt = $pdo->prepare('SELECT id, starts_at FROM doctor_time_slots WHERE id = ? AND doctor_id = ? AND is_booked = 0 FOR UPDATE');
                $stmt->execute([$slotId, $doctorId]);
                $slot = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$slot) {
                    $pdo->rollBack();
                    $this->response->json(['message' => 'Slot unavailable'], 409);
                    return;
                }
                $pdo->prepare('UPDATE doctor_time_slots SET is_booked = 1 WHERE id = ?')->execute([$slotId]);
                $appointmentDate = $slot['starts_at'];
            } else {
                $pdo->rollBack();
                $this->response->json(['message' => 'slot_id required'], 422);
                return;
            }
            $stmt = $pdo->prepare('INSERT INTO appointments (patient_id, doctor_id, appointment_date, notes, status) VALUES ((SELECT id FROM patients WHERE user_id = ?), ?, ?, ?, "pending")');
            $stmt->execute([$_SESSION['user']['id'], $doctorId, $appointmentDate, $notes]);
            $appointmentId = (int)$pdo->lastInsertId();
            $pdo->commit();
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $this->response->json(['message' => 'Error creating appointment'], 500);
            return;
        }

        if ($appointmentId > 0) {
            // notify doctor & patient (logged by default)
            $this->notifyNewAppointment($pdo, $appointmentId);
            $this->response->json(['message' => 'Appointment requested']);
        }
    }

    private function notifyNewAppointment(\PDO $pdo, int $appointmentId): void
    {
        // load doctor/patient emails
        $sql = 'SELECT a.id, a.appointment_date, du.name AS doctor_name, du.email AS doctor_email,
                       pu.name AS patient_name, pu.email AS patient_email
                FROM appointments a
                JOIN doctors d ON d.id = a.doctor_id
                JOIN users du ON du.id = d.user_id
                JOIN patients p ON p.id = a.patient_id
                JOIN users pu ON pu.id = p.user_id
                WHERE a.id = ? LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$appointmentId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return;

        $subject = \App\Core\Lang::t('mail.appt.new.subject');
        $htmlDoctor = nl2br(\App\Core\Lang::t('mail.appt.new.body.doctor', [
          'doctor' => (string)$row['doctor_name'],
          'date' => (string)$row['appointment_date'],
        ]));
        $htmlPatient = nl2br(\App\Core\Lang::t('mail.appt.new.body.patient', [
          'patient' => (string)$row['patient_name'],
          'date' => (string)$row['appointment_date'],
        ]));

        $mailer = new \App\Services\Mailer($this->config);
        // To doctor
        $mailer->send((string)$row['doctor_email'], (string)$row['doctor_name'], $subject, $htmlDoctor);
        // To patient
        $mailer->send((string)$row['patient_email'], (string)$row['patient_name'], $subject, $htmlPatient);
    }
}

