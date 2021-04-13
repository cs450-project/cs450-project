<?php

namespace CS450\Service\Db;

use CS450\Service\DbService;

class MysqlDb implements DbService {

    /**
     * 
     * @Inject("db.host")
     */
    private $dbHost;

    /**
     * 
     * @Inject("db.user")
     */
    private $dbUser;

    /**
     * 
     * @Inject("db.name")
     */
    private $dbName;

    /**
     * 
     * @Inject("db.password")
     */
    private $dbPassword;

    /**
     * 
     * @Inject
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    private $conn;

    public function getConnection() {
        $this->logger->debug(sprintf(
            "Returning mysql connection to %s@%s/%s",
            $this->dbUser,
            $this->dbHost,
            $this->dbName,
        ));
        
        $this->conn = new \mysqli(
            $this->dbHost,
            $this->dbUser,
            $this->dbPassword,
            $this->dbName,
        );

        return $this->conn;
    }

    public function __destruct() {
        if(!empty($this->conn)) {
            $this->conn->close();
        }
    }
}
