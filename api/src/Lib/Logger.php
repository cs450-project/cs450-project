<?php 
namespace App\Lib;

use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

class Logger extends \Monolog\Logger
{
    private const DEFAULT_LOG_PATH = __DIR__ . '/../../logs';
    private static $loggers = [];

    public function __construct(string $key = "app", $config = null)
    {
        parent::__construct($key);

        if (empty($config)) {
            $LOG_PATH = Config::get('LOG_PATH', self::DEFAULT_LOG_PATH);
            $config = [
                'logFile' => "{$LOG_PATH}/{$key}.log",
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
        self::$loggers['error']->pushHandler(new StreamHandler("{$LOG_PATH}/errors.log"));
        ErrorHandler::register(self::$loggers['error']);

        if ($shouldLogRequests) {
            // Request Log
            $data = [
                $_SERVER,
                $_REQUEST,
                trim(file_get_contents("php://input"))
            ];
            self::$loggers['request'] = new Logger('request');
            self::$loggers['request']->pushHandler(new StreamHandler("{$LOG_PATH}/request.log"));
            self::$loggers['request']->info("REQUEST", $data);
        }
    }
}