<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class UserController extends Controller
{
    public function index(): void
    {
        $this->requireAuth(['super_admin']);
        $pdo = DB::conn($this->config);
        $rows = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 200')->fetchAll();
        $this->view('admin/users/index', [
            'title' => 'Manage Users',
            'users' => $rows,
        ]);
    }

    public function store(): void
    {
        $this->requireAuth(['super_admin']);
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $name = trim((string)$this->request->post['name'] ?? '');
        $email = trim((string)$this->request->post['email'] ?? '');
        $role = (string)($this->request->post['role'] ?? 'patient');
        $password = password_hash((string)($this->request->post['password'] ?? 'password123'), PASSWORD_BCRYPT);
        if ($name && $email && in_array($role, ['doctor','patient','super_admin'], true)) {
            $pdo = DB::conn($this->config);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, $password, $role]);
            $userId = (int)$pdo->lastInsertId();
            if ($role === 'doctor') {
                $pdo->prepare('INSERT INTO doctors (user_id) VALUES (?)')->execute([$userId]);
            } elseif ($role === 'patient') {
                $pdo->prepare('INSERT INTO patients (user_id) VALUES (?)')->execute([$userId]);
            }
        }
        $this->response->redirect($this->request->baseUrl() . '/admin/users');
    }

    public function destroy(): void
    {
        $this->requireAuth(['super_admin']);
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $id = (int)($this->request->post['id'] ?? 0);
        if ($id > 0) {
            $pdo = DB::conn($this->config);
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$id]);
        }
        $this->response->redirect($this->request->baseUrl() . '/admin/users');
    }
}

