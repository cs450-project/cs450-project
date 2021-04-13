<?

use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\create;

use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;

use CS450\Service\JwtService;
use CS450\Service\DbService;

return [
    "env" => "dev",
    "jwt.key" => "5f2b5cdbe5194f10b3241568fe4e2b24",
    "db.host" => getenv("MYSQL_HOST"),
    "db.user" => getenv("MYSQL_USER"),
    "db.name" => getenv("MYSQL_DATABASE"),
    "db.password" => getenv("MYSQL_PASSWORD"),
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
