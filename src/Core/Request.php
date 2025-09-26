<?php
namespace App\Core;

class Request
{
    public array $get;
    public array $post;
    public array $server;
    public array $files;
    public array $cookies;
    public array $session;
    public string $method;
    public string $uri;

    public function __construct(private array $config)
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        $this->session = &$_SESSION;
        $this->method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        $this->uri = parse_url($this->server['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    }

    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function isAjax(): bool
    {
        return ($this->server['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest'
            || (isset($this->server['HTTP_ACCEPT']) && str_contains($this->server['HTTP_ACCEPT'], 'application/json'));
    }

    public function baseUrl(): string
    {
        if (!empty($this->config['base_url'])) {
            return rtrim($this->config['base_url'], '/');
        }
        $scheme = (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $this->server['HTTP_HOST'] ?? 'localhost';
        $scriptName = $this->server['SCRIPT_NAME'] ?? '/index.php';
        $dir = rtrim(str_replace('index.php', '', $scriptName), '/');
        return rtrim($scheme . '://' . $host . $dir, '/');
    }
}

