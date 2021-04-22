<?php

namespace CS450\Model;

use CS450\Model\Grant;

final class GrantFactory {
    /**
     * @Inject
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @Inject
     * @var CS450\Service\DbService
     */
    private $db;

    public function findAll(): array {
        $selectAllGrantsQ = "SELECT * FROM tbl_fact_grants";

        $conn = $this->db->getConnection();
        $result = $conn->query($selectAllGrantsQ);

        if (!$result) {
            $errMsg = sprintf("An error occurred executing your query: %s, %s", $selectAllGrantsQ, $conn->error);
            throw new \Exception($errMsg);
        }

        $grants = [];
        while($grant = $result->fetch_object("CS450\Model\Grant", [$this->db])) {
            $grants[] = array(
                "id" => $grant->getId(),
                "title" => $grant->getTitle(),
                "status" => $grant->getStatus(),
                "balance" => $grant->getBalance(),
                "originalAmount" => $grant->getOriginalAmount(),
                "source" => "TODO",
                "grant_number" => $grant->getGrantNumber(),
                "administrator" => "TODO",
                "recipients" => ["TODO"],
            );
        }

        return $grants;
    }
}
