<?php
namespace App\Controllers\Api;

use App\Core\DB;
use PDO;

class SlotController extends BaseApiController
{
    public function availableByDoctor(): void
    {
        $doctorId = (int)($this->request->get['doctor_id'] ?? 0);
        if ($doctorId <= 0) { $this->ok([]); return; }
        [$page,$per,$offset] = $this->paginationParams();
        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('SELECT id, starts_at, ends_at FROM doctor_time_slots WHERE doctor_id = ? AND is_booked = 0 AND starts_at > NOW() ORDER BY starts_at ASC LIMIT :per OFFSET :off');
        $stmt->bindValue(1, $doctorId, PDO::PARAM_INT);
        $stmt->bindValue(':per', $per, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->ok($rows, ['page'=>$page,'per_page'=>$per,'count'=>count($rows)]);
    }
}

