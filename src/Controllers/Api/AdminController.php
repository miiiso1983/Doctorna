<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\DB;

class AdminController extends Controller
{
    public function stats(): void
    {
        $this->requireAuth(['super_admin']);
        $pdo = DB::conn($this->config);
        $users = (int)$pdo->query('SELECT COUNT(*) c FROM users')->fetch()['c'];
        $doctors = (int)$pdo->query("SELECT COUNT(*) c FROM users WHERE role='doctor'")->fetch()['c'];
        $patients = (int)$pdo->query("SELECT COUNT(*) c FROM users WHERE role='patient'")->fetch()['c'];
        $appointments = (int)$pdo->query('SELECT COUNT(*) c FROM appointments')->fetch()['c'];
        $this->response->json(compact('users','doctors','patients','appointments'));
    }
}

