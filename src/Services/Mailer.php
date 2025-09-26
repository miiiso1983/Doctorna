<?php
namespace App\Services;

class Mailer
{
    public function __construct(private array $config) {}

    public function send(string $toEmail, string $toName, string $subject, string $html, ?string $text = null): bool
    {
        $fromEmail = $this->config['mail']['from_email'] ?? 'no-reply@example.com';
        $fromName = $this->config['mail']['from_name'] ?? ($this->config['app_name'] ?? 'App');

        // If force_log or mail() not available, log instead of sending
        $forceLog = (bool)($this->config['mail']['force_log'] ?? true);
        if ($forceLog || !function_exists('mail')) {
            return $this->log($toEmail, $toName, $subject, $html, $text, $fromEmail, $fromName);
        }

        // Best-effort using PHP mail() (depends on local MTA). Headers for HTML
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $fromName . ' <' . $fromEmail . '>';
        $headers[] = 'Reply-To: ' . $fromEmail;
        $headersStr = implode("\r\n", $headers);

        try {
            return mail($toEmail, $subject, $html, $headersStr);
        } catch (\Throwable $e) {
            // Fallback to log on failure
            return $this->log($toEmail, $toName, $subject, $html, $text, $fromEmail, $fromName, (string)$e->getMessage());
        }
    }

    private function log(string $toEmail, string $toName, string $subject, string $html, ?string $text, string $fromEmail, string $fromName, string $error = ''): bool
    {
        $dir = __DIR__ . '/../../storage/logs';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $entry = [
            'time' => date('c'),
            'to' => [$toEmail, $toName],
            'from' => [$fromEmail, $fromName],
            'subject' => $subject,
            'html' => $html,
            'text' => $text,
            'error' => $error,
        ];
        $line = json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n";
        @file_put_contents($dir . '/mail.log', $line, FILE_APPEND);
        return true; // consider logged as success
    }
}

