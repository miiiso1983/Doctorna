<?php
namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Core\DB;
use DateInterval;
use DatePeriod;
use DateTime;
use PDO;

class TimeSlotController extends Controller
{
    private function doctorId(PDO $pdo): int
    {
        $stmt = $pdo->prepare('SELECT id FROM doctors WHERE user_id = ?');
        $stmt->execute([$_SESSION['user']['id']]);
        return (int)($stmt->fetch()['id'] ?? 0);
    }

    public function index(): void
    {
        $this->requireAuth(['doctor']);
        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('SELECT id, starts_at, ends_at, is_booked FROM doctor_time_slots WHERE doctor_id = (SELECT id FROM doctors WHERE user_id = ?) ORDER BY starts_at ASC LIMIT 500');
        $stmt->execute([$_SESSION['user']['id']]);
        $this->view('doctor/slots/index', ['title' => 'My Availability', 'slots' => $stmt->fetchAll()]);
    }

    public function store(): void
    {
        $this->requireAuth(['doctor']);
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        try {
            $start = new DateTime((string)$this->request->post['starts_at']);
            $end = new DateTime((string)$this->request->post['ends_at']);
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Invalid date/time format.';
            $this->response->redirect($this->request->baseUrl() . '/doctor/slots');
            return;
        }
        if ($end <= $start) {
            $_SESSION['flash_error'] = 'End time must be after start time.';
            $this->response->redirect($this->request->baseUrl() . '/doctor/slots');
            return;
        }
        $pdo = DB::conn($this->config);
        $doctorId = $this->doctorId($pdo);
        $q = $pdo->prepare('SELECT COUNT(*) c FROM doctor_time_slots WHERE doctor_id = ? AND (? < ends_at AND ? > starts_at)');
        $q->execute([$doctorId, $start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);
        if ((int)$q->fetch()['c'] === 0) {
            $stmt = $pdo->prepare('INSERT INTO doctor_time_slots (doctor_id, starts_at, ends_at) VALUES (?, ?, ?)');
            $stmt->execute([$doctorId, $start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);
            $_SESSION['flash'] = 'Slot added.';
        } else {
            $_SESSION['flash_error'] = 'This slot overlaps with an existing one.';
        }
        $this->response->redirect($this->request->baseUrl() . '/doctor/slots');
    }

    public function storeRecurring(): void
    {
        $this->requireAuth(['doctor']);
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        try {
            $startDate = new DateTime((string)$this->request->post['start_date']);
            $endDate = new DateTime((string)$this->request->post['end_date']);
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'Invalid date range.';
            $this->response->redirect($this->request->baseUrl() . '/doctor/slots');
            return;
        }
        $days = (array)($this->request->post['days'] ?? []);
        $startTime = (string)$this->request->post['start_time'];
        $endTime = (string)$this->request->post['end_time'];
        $duration = max(5, (int)$this->request->post['slot_minutes']);
        if ($endDate < $startDate) { $_SESSION['flash_error'] = 'End date must be after start date.'; $this->response->redirect($this->request->baseUrl() . '/doctor/slots'); return; }
        if (!$days) { $_SESSION['flash_error'] = 'Choose at least one weekday.'; $this->response->redirect($this->request->baseUrl() . '/doctor/slots'); return; }
        $dayMap = ['sun'=>0,'mon'=>1,'tue'=>2,'wed'=>3,'thu'=>4,'fri'=>5,'sat'=>6];
        $selected = array_map(fn($d) => $dayMap[$d] ?? -1, $days);

        $pdo = DB::conn($this->config);
        $doctorId = $this->doctorId($pdo);
        $created = 0; $skipped = 0;
        $period = new DatePeriod($startDate, new DateInterval('P1D'), (clone $endDate)->modify('+1 day'));
        foreach ($period as $date) {
            if (!in_array((int)$date->format('w'), $selected, true)) continue;
            $dayStart = new DateTime($date->format('Y-m-d') . ' ' . $startTime);
            $dayEnd = new DateTime($date->format('Y-m-d') . ' ' . $endTime);
            if ($dayEnd <= $dayStart) continue;
            $slotStart = clone $dayStart;
            while (($slotStart->getTimestamp() + $duration*60) <= $dayEnd->getTimestamp()) {
                $slotEnd = (clone $slotStart)->modify("+{$duration} minutes");
                $q = $pdo->prepare('SELECT COUNT(*) c FROM doctor_time_slots WHERE doctor_id = ? AND (? < ends_at AND ? > starts_at)');
                $q->execute([$doctorId, $slotStart->format('Y-m-d H:i:s'), $slotEnd->format('Y-m-d H:i:s')]);
                if ((int)$q->fetch()['c'] === 0) {
                    $ins = $pdo->prepare('INSERT INTO doctor_time_slots (doctor_id, starts_at, ends_at) VALUES (?, ?, ?)');
                    $ins->execute([$doctorId, $slotStart->format('Y-m-d H:i:s'), $slotEnd->format('Y-m-d H:i:s')]);
                    $created++;
                } else {
                    $skipped++;
                }
                $slotStart = $slotEnd;
            }
        }
        $_SESSION['flash'] = "Created $created slots, skipped $skipped";
        $this->response->redirect($this->request->baseUrl() . '/doctor/slots');
    }

    public function destroy(): void
    {
        $this->requireAuth(['doctor']);
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $id = (int)$this->request->post['id'];
        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('DELETE FROM doctor_time_slots WHERE id = ? AND doctor_id = (SELECT id FROM doctors WHERE user_id = ?) AND is_booked = 0');
        $stmt->execute([$id, $_SESSION['user']['id']]);
        $this->response->redirect($this->request->baseUrl() . '/doctor/slots');
    }

    public function destroyRange(): void
    {
        $this->requireAuth(['doctor']);
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $from = new DateTime((string)$this->request->post['date_from']);
        $to = new DateTime((string)$this->request->post['date_to']);
        $pdo = DB::conn($this->config);
        $doctorId = $this->doctorId($pdo);
        $del = $pdo->prepare('DELETE FROM doctor_time_slots WHERE doctor_id = ? AND is_booked = 0 AND starts_at >= ? AND ends_at <= ?');
        $del->execute([$doctorId, $from->format('Y-m-d 00:00:00'), $to->format('Y-m-d 23:59:59')]);
        $this->response->redirect($this->request->baseUrl() . '/doctor/slots');
    }
}

