<?php
namespace App\Core;

class Jwt
{
    public static function encode(array $payload, string $secret, array $header = ['alg' => 'HS256', 'typ' => 'JWT']): string
    {
        $segments = [
            self::b64(json_encode($header, JSON_UNESCAPED_SLASHES)),
            self::b64(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];
        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, $secret, true);
        $segments[] = self::b64($signature);
        return implode('.', $segments);
    }

    public static function decode(string $jwt, string $secret, bool $verifyExpiry = true): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) throw new \InvalidArgumentException('Invalid JWT');
        [$headB64, $payloadB64, $sigB64] = $parts;
        $header = json_decode(self::ub64($headB64), true) ?: [];
        $payload = json_decode(self::ub64($payloadB64), true) ?: [];
        $sig = self::ub64($sigB64);
        if (($header['alg'] ?? 'HS256') !== 'HS256') throw new \RuntimeException('Unsupported alg');
        $expected = hash_hmac('sha256', "$headB64.$payloadB64", $secret, true);
        if (!hash_equals($expected, $sig)) throw new \RuntimeException('Signature verification failed');
        if ($verifyExpiry && isset($payload['exp']) && time() >= (int)$payload['exp']) {
            throw new \RuntimeException('Token expired');
        }
        return $payload;
    }

    private static function b64(string $data): string
    { return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); }
    private static function ub64(string $data): string
    { return base64_decode(strtr($data, '-_', '+/')); }
}

