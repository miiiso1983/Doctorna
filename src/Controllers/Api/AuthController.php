<?php
namespace App\Controllers\Api;

use App\Core\DB;
use App\Core\Jwt;

class AuthController extends BaseApiController
{
    // POST /api/v1/auth/login  {email,password}
    public function login(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $email = trim((string)($input['email'] ?? ''));
        $password = (string)($input['password'] ?? '');
        if ($email === '' || $password === '') {
            $this->error('Email and password are required', 422);
        }
        $pdo = DB::conn($this->config);
        $stmt = $pdo->prepare('SELECT id, name, email, role, password FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            $this->error('Invalid credentials', 401);
        }
        $userId = (int)$user['id'];
        $role = $user['role'];
        $now = time();
        $accessPayload = [
            'sub' => $userId,
            'role' => $role,
            'iat' => $now,
            'exp' => $now + 15 * 60,
            'typ' => 'access'
        ];
        $refreshPayload = [
            'sub' => $userId,
            'iat' => $now,
            'exp' => $now + 14 * 24 * 60 * 60, // 14 days
            'typ' => 'refresh'
        ];
        $accessToken = Jwt::encode($accessPayload, $this->config['jwt_secret']);
        $refreshToken = Jwt::encode($refreshPayload, $this->config['jwt_secret']);
        unset($user['password']);
        $this->ok([
            'token' => [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'expires_in' => 15 * 60,
            ],
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]
        ]);
    }

    // POST /api/v1/auth/refresh  {refresh_token}
    public function refresh(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $refresh = (string)($input['refresh_token'] ?? '');
        if ($refresh === '') { $this->response->json(['error' => 'refresh_token is required'], 422); return; }
        try {
            $claims = Jwt::decode($refresh, $this->config['jwt_secret']);
            if (($claims['typ'] ?? '') !== 'refresh') throw new \RuntimeException('Not a refresh token');
        } catch (\Throwable $e) {
            $this->error('Invalid refresh token: ' . $e->getMessage(), 401);
        }
        $now = time();
        $accessPayload = [
            'sub' => $claims['sub'] ?? 0,
            'role' => $claims['role'] ?? 'patient',
            'iat' => $now,
            'exp' => $now + 15 * 60,
            'typ' => 'access'
        ];
        $accessToken = Jwt::encode($accessPayload, $this->config['jwt_secret']);
        $this->ok(['access_token' => $accessToken, 'expires_in' => 15*60]);
    }

    // POST /api/v1/auth/logout  (optional in stateless JWT; client just discards tokens)
    public function logout(): void
    {
        $this->response->json(['message' => 'Logged out']);
    }
}

