<?php
namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Jwt;

class BaseApiController extends Controller
{
    protected function requireJwt(array $roles = []): array
    {
        $auth = $this->request->server['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/Bearer\s+(\S+)/', $auth, $m)) {
            $this->error('Missing bearer token', 401);
        }
        try {
            $claims = Jwt::decode($m[1], $this->config['jwt_secret']);
        } catch (\Throwable $e) {
            $this->error('Invalid token: ' . $e->getMessage(), 401);
        }
        if ($roles && !in_array(($claims['role'] ?? ''), $roles, true)) {
            $this->error('Forbidden', 403);
        }
        return $claims; // contains sub(user_id), role, exp, iat
    }

    protected function ok($data = null, ?array $meta = null, int $status = 200): void
    {
        $body = ['success' => true];
        if ($data !== null) { $body['data'] = $data; }
        if ($meta !== null) { $body['meta'] = $meta; }
        $this->response->json($body, $status);
    }

    protected function error(string $message, int $status = 400, $details = null): void
    {
        http_response_code($status);
        $body = ['success' => false, 'error' => ['message' => $message]];
        if ($details !== null) { $body['error']['details'] = $details; }
        $this->response->json($body, $status);
        exit;
    }

    protected function paginationParams(): array
    {
        $page = max(1, (int)($this->request->get['page'] ?? 1));
        $per = (int)($this->request->get['per_page'] ?? 20);
        $per = min(100, max(1, $per));
        $offset = ($page - 1) * $per;
        return [$page, $per, $offset];
    }
}

