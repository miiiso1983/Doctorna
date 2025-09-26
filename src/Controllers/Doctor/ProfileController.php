<?php
namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Core\DB;

class ProfileController extends Controller
{
    public function edit(): void
    {
        $this->requireAuth(['doctor']);
        $pdo = DB::conn($this->config);
        $doctor = $pdo->prepare('SELECT d.*, s.name AS specialization_name FROM doctors d LEFT JOIN specializations s ON s.id = d.specialization_id WHERE d.user_id = ?');
        $doctor->execute([$_SESSION['user']['id']]);
        $d = $doctor->fetch();
        $specs = $pdo->query('SELECT id, name FROM specializations ORDER BY name')->fetchAll();
        $this->view('doctor/profile/edit', ['title' => 'My Profile', 'doctor' => $d, 'specializations' => $specs]);
    }

    public function update(): void
    {
        $this->requireAuth(['doctor']);
        if (!\App\Core\verify_csrf((string)($this->request->post['csrf'] ?? ''))) {
            http_response_code(419);
            echo 'CSRF token mismatch';
            return;
        }
        $bio = (string)($this->request->post['bio'] ?? '');
        $specialization_id = (int)($this->request->post['specialization_id'] ?? 0) ?: null;
        $working_hours = (string)($this->request->post['working_hours'] ?? '');
        $pdo = DB::conn($this->config);
        $whatsapp_enabled = (int)!empty($this->request->post['whatsapp_enabled']);
        $whatsapp_from_phone_id = (string)($this->request->post['whatsapp_from_phone_id'] ?? '');
        $stmt = $pdo->prepare('UPDATE doctors SET bio = ?, specialization_id = ?, working_hours = ?, whatsapp_enabled = ?, whatsapp_from_phone_id = ? WHERE user_id = ?');
        $stmt->execute([$bio, $specialization_id, $working_hours ?: null, $whatsapp_enabled, $whatsapp_from_phone_id ?: null, $_SESSION['user']['id']]);
        $this->response->redirect($this->request->baseUrl() . '/doctor/profile');
    }
}

