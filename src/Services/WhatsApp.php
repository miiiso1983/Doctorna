<?php
namespace App\Services;

use PDO;

class WhatsApp
{
    public function __construct(private array $config) {}

    // For PoC we just log the outbound WhatsApp message
    public function sendFromDoctor(PDO $pdo, int $doctorUserId, string $toPhoneE164, string $text): bool
    {
        // ensure doctor has whatsapp enabled and a from phone id
        $stmt = $pdo->prepare('SELECT d.whatsapp_enabled, d.whatsapp_from_phone_id, u.name AS doctor_name FROM doctors d JOIN users u ON u.id = d.user_id WHERE d.user_id = ?');
        $stmt->execute([$doctorUserId]);
        $doc = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$doc || (int)$doc['whatsapp_enabled'] !== 1) return false;
        if (empty($doc['whatsapp_from_phone_id'])) return false;

        $dir = __DIR__ . '/../../storage/logs';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);
        $entry = [
            'time' => date('c'),
            'channel' => 'whatsapp',
            'from_phone_id' => $doc['whatsapp_from_phone_id'],
            'to' => $toPhoneE164,
            'doctor_user_id' => $doctorUserId,
            'text' => $text,
        ];
        @file_put_contents($dir . '/whatsapp.log', json_encode($entry, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
        return true;
    }
}

