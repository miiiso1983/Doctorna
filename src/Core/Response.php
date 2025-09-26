<?php
namespace App\Core;

class Response
{
    public function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

