<?php namespace App\Lib;

class Config
{
    private static $config;

    public static function get(string $key, $default = null, $configFile = null)
    {
        $configFile = $configFile ?? __DIR__ . "/../../config.php";

        if (file_exists($configFile) && is_null(self::$config)) {
            self::$config = include_once($configFile);
        }

        return !empty(self::$config[$key])
            ? self::$config[$key]
            : $default;
    }
}
