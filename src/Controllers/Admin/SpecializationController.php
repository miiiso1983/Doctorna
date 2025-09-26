<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\DB;

class SpecializationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth(['super_admin']);
        $pdo = DB::conn($this->config);
        $rows = $pdo->query('SELECT id, name FROM specializations ORDER BY name')->fetchAll();
        $this->view('admin/specializations/index', [
            'title' => 'Manage Specializations',
            'specializations' => $rows,
        ]);
    }

    public function store(): void
    {
        $this->requireAuth(['super_admin']);
        $token = (string)($this->request->post['csrf'] ?? '');
        if (!\App\Core\verify_csrf($token)) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $name = trim((string)$this->request->post['name'] ?? '');
        if ($name === '') {
            $this->response->redirect($this->request->baseUrl() . '/admin/specializations');
            return;
        }
        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('INSERT IGNORE INTO specializations (name) VALUES (?)');
        $stmt->execute([$name]);
        $this->response->redirect($this->request->baseUrl() . '/admin/specializations');
    }

    public function destroy(): void
    {
        $this->requireAuth(['super_admin']);
        $token = (string)($this->request->post['csrf'] ?? '');
        if (!\App\Core\verify_csrf($token)) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $id = (int)($this->request->post['id'] ?? 0);
        if ($id > 0) {
            $pdo = DB::conn($this->config);
            $stmt = $pdo->prepare('DELETE FROM specializations WHERE id = ?');
            $stmt->execute([$id]);
        }
        $this->response->redirect($this->request->baseUrl() . '/admin/specializations');
    }
}

