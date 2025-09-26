<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\DB;

class SpecializationController extends Controller
{
    public function index(): void
    {
        $pdo = DB::conn($this->config);
        $rows = $pdo->query('SELECT id, name FROM specializations ORDER BY name')->fetchAll();
        $this->response->json(['data' => $rows]);
    }
}

