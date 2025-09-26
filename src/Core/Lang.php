<?php
namespace App\Core;

class Lang
{
    private static array $dict = [];

    public static function setLocale(string $locale): void
    {
        $_SESSION['locale'] = in_array($locale, ['ar','en'], true) ? $locale : 'ar';
        self::$dict = self::load($_SESSION['locale']);
    }

    public static function locale(): string
    {
        return $_SESSION['locale'] ?? 'ar';
    }

    private static function load(string $locale): array
    {
        $path = __DIR__ . '/../lang/' . $locale . '.php';
        if (is_file($path)) {
            return require $path;
        }
        return [];
    }

    public static function t(string $key, array $vars = []): string
    {
        if (!self::$dict) self::$dict = self::load(self::locale());
        $text = self::$dict[$key] ?? $key;
        foreach ($vars as $k => $v) {
            $text = str_replace('{' . $k . '}', (string)$v, $text);
        }
        return $text;
    }
}

