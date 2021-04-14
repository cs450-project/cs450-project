<?

use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\create;

use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

use CS450\Service\JwtService;
use CS450\Service\DbService;

function getDbConnectionDetailsArray() {
    $cleardb_url = getenv("CLEARDB_DATABASE_URL");
    $cleardb_conn_params = parse_url($cleardb_url);

    return array(
        "db.host" => empty($cleardb_url) ? $cleardb_conn_params["host"] : getenv("MYSQL_HOST"),
        "db.user" => empty($cleardb_url) ? $cleardb_conn_params["user"] : getenv("MYSQL_USER"),
        "db.password" => empty($cleardb_url) ? $cleardb_conn_params["pass"] : getenv("MYSQL_PASSWORD"),
        "db.name" => empty($cleardb_url) ? substr($cleardb_conn_params["path"], 1) : getenv("MYSQL_DATABASE"),
    );
}

return array_merge(
[
    "env" => "dev",
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
],
getDbConnectionDetailsArray()
);
