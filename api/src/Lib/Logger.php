<?php 
namespace App\Lib;

use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

/**
 * @codeCoverageIgnore
 */
class Logger extends \Monolog\Logger
{
    private const DEFAULT_LOG_PATH = "php://stderr";
    private static $loggers = [];

    public function __construct(string $key = "app", $config = null)
    {
        parent::__construct($key);

        if (empty($config)) {
            $LOG_PATH = Config::get('LOG_PATH', self::DEFAULT_LOG_PATH);
            $config = [
                'logFile' => "{$LOG_PATH}",
                'logLevel' => \Monolog\Logger::DEBUG
            ];
        }

        $this->pushHandler(new StreamHandler($config['logFile'], $config['logLevel']));
    }

    public static function getInstance($key = "app", $config = null): self {
        if (empty(self::$loggers[$key])) {
            self::$loggers[$key] = new Logger($key, $config);
        }

        return self::$loggers[$key];
    }

    public static function enableSystemLogs(bool $shouldLogRequests = false): void {
        $LOG_PATH = Config::get('LOG_PATH', self::DEFAULT_LOG_PATH);

        // Error Log
        self::$loggers['error'] = new Logger('errors');
        self::$loggers['error']->pushHandler(new StreamHandler("{$LOG_PATH}"));
        ErrorHandler::register(self::$loggers['error']);
    }
}
