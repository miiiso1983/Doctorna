<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\DB;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', ['title' => 'Login']);
    }

    public function showRegister(): void
    {
        $this->view('auth/register', ['title' => 'Register']);
    }

    public function login(): void
    {
        $email = $this->request->input('email');
        $password = $this->request->input('password');

        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            $_SESSION['user'] = $user;
            $this->response->redirect($this->request->baseUrl() . '/dashboard');
            return;
        }

        $this->view('auth/login', ['title' => 'Login', 'error' => 'Invalid credentials']);
    }

    public function register(): void
    {
        $role = $this->request->input('role', 'patient'); // patient | doctor
        $name = $this->request->input('name');
        $email = $this->request->input('email');
        $phone = $this->request->input('phone');
        $password = password_hash($this->request->input('password'), PASSWORD_BCRYPT);

        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$name, $email, $phone, $password, $role]);
        $userId = (int)$pdo->lastInsertId();

        if ($role === 'doctor') {
            $pdo->prepare('INSERT INTO doctors (user_id) VALUES (?)')->execute([$userId]);
        } else {
            $pdo->prepare('INSERT INTO patients (user_id) VALUES (?)')->execute([$userId]);
        }

        $this->response->redirect($this->request->baseUrl() . '/login');
    }

    public function logout(): void
    {
        session_destroy();
        $this->response->redirect($this->request->baseUrl() . '/');
    }
}

