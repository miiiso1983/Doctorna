<?php
namespace App\Controllers\Api;

use App\Core\DB;

class AdminController extends BaseApiController
{
    public function stats(): void
    {
        $this->requireJwt(['super_admin']);
        $pdo = DB::conn($this->config);
        $users = (int)$pdo->query('SELECT COUNT(*) c FROM users')->fetch()['c'];
        $doctors = (int)$pdo->query("SELECT COUNT(*) c FROM users WHERE role='doctor'")->fetch()['c'];
        $patients = (int)$pdo->query("SELECT COUNT(*) c FROM users WHERE role='patient'")->fetch()['c'];
        $appointments = (int)$pdo->query('SELECT COUNT(*) c FROM appointments')->fetch()['c'];
        $this->ok(compact('users','doctors','patients','appointments'));
    }
}

