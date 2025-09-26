<?php
namespace App\Core;

class Controller
{
    public function __construct(protected Request $request, protected Response $response, protected array $config)
    {}

    protected function view(string $template, array $data = []): void
    {
        extract($data);
        $baseUrl = $this->request->baseUrl();
        // Make $template available to the layout to include the correct view
        $template = ltrim($template, '/');
        include __DIR__ . '/../../views/layouts/app.php';
    }

    protected function renderPartial(string $template, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../../views/' . $template . '.php';
    }

    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    protected function requireAuth(array $roles = []): void
    {
        if (!$this->isAuthenticated()) {
            $this->response->redirect($this->request->baseUrl() . '/login');
        }
        if ($roles && !in_array($_SESSION['user']['role'], $roles, true)) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}

