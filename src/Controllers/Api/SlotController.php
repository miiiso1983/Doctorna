<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\DB;
use PDO;

class SlotController extends Controller
{
    public function availableByDoctor(): void
    {
        $doctorId = (int)($this->request->get['doctor_id'] ?? 0);
        if ($doctorId <= 0) {
            $this->response->json(['data' => []]);
            return;
        }
        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('SELECT id, starts_at, ends_at FROM doctor_time_slots WHERE doctor_id = ? AND is_booked = 0 AND starts_at > NOW() ORDER BY starts_at ASC LIMIT 200');
        $stmt->execute([$doctorId]);
        $this->response->json(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }
}

