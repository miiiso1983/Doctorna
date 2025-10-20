<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Jwt;
use App\Core\DB;

class BaseApiController extends Controller
{
    protected function requireJwt(array $roles = []): array
    {
        $auth = $this->request->server['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/Bearer\s+(\S+)/', $auth, $m)) {
            http_response_code(401);
            $this->response->json(['error' => 'Missing bearer token'], 401);
            exit;
        }
        try {
            $claims = Jwt::decode($m[1], $this->config['jwt_secret']);
        } catch (\Throwable $e) {
            http_response_code(401);
            $this->response->json(['error' => 'Invalid token: ' . $e->getMessage()], 401);
            exit;
        }
        if ($roles && !in_array(($claims['role'] ?? ''), $roles, true)) {
            http_response_code(403);
            $this->response->json(['error' => 'Forbidden'], 403);
            exit;
        }
        return $claims; // contains sub(user_id), role, exp, iat
    }
}

