<?

include 'dbconfig.php';

use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\create;

use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

use CS450\Service\JwtService;
use CS450\Service\DbService;

$db_conn_params = load_db_config(
    getenv("MYSQL_HOST"),
    getenv("MYSQL_USER"),
    getenv("MYSQL_PASSWORD"),
    getenv("MYSQL_DATABASE"),
);

return [
    "env" => "dev",
    "db.host" => $db_conn_params["host"],
    "db.user" => $db_conn_params["user"],
    "db.name" => $db_conn_params["name"],
    "db.password" => $db_conn_params["pass"],
    "jwt.key" => "5f2b5cdbe5194f10b3241568fe4e2b24",
    DbService::class => DI\Autowire(CS450\Service\Db\MysqlDb::class),
    JwtService::class => create(CS450\Service\Jwt\FirebaseJwt::class),
    Psr\Log\LoggerInterface::class => DI\factory(function () {
        $logger = new Logger("CS450");

        $fileHandler = new StreamHandler("php://stdout", Logger::DEBUG);
        $logger->pushHandler($fileHandler);

        ErrorHandler::register($logger);

        return $logger;
    }),
];
