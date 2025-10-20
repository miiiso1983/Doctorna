<?php
namespace App\Controllers\Api;

use App\Core\DB;

class SpecializationController extends BaseApiController
{
    public function index(): void
    {
        [$page,$per,$offset] = $this->paginationParams();
        $pdo = DB::conn($this->config);
        $total = (int)$pdo->query('SELECT COUNT(*) c FROM specializations')->fetch()['c'];
        $stmt = $pdo->prepare('SELECT id, name FROM specializations ORDER BY name LIMIT :per OFFSET :off');
        $stmt->bindValue(':per', $per, \PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $this->ok($rows, ['page'=>$page,'per_page'=>$per,'total'=>$total]);
    }
}

