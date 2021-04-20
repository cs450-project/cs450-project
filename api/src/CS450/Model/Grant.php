<?php

namespace CS450\Model;

use CS450\Model\GrantBuilder;
use CS450\Service\DbService;

final class Grant {
    private $db;

    private $id;
    private $title;
    private $status;
    private $adminId;
    private $balance;
    private $sourceId;
    private $grantNumber;
    private $originalAmount;
    private $recipients = [];

    public function __construct(GrantBuilder $builder, DbService $db) {
        $this->db = $db;

        $this->id = $builder->id;
        $this->status = $builder->status;
        $this->adminId = $builder->adminId;
        $this->sourceId = $builder->sourceId;
        $this->grantNumber = $builder->grantNumber;
        $this->originalAmount = $builder->originalAmount;
        $this->balance = $this->balance ?? $this->originalAmount;
        $this->recipients = (new \ArrayObject($builder->recipients))->getArrayCopy();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getAdminId() {
        return $this->adminId;
    }

    public function getBalance() {
        return $this->balance;
    }

    public function getOriginalAmount() {
        return $this->originalAmount;
    }

    public function getRecipients() {
        return $this->recipients;
    }

    public function save(): Self {
        $insertGrantSql = <<<EOD
            INSERT INTO tbl_fact_grants (grant_number, title, source_id, original_amt, balance, status, administrator_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        EOD;

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($insertGrantSql);
        if (!$stmt) {
            $errMsg = sprintf("An error occurred preparing your query: %s - %s", $insertGrantSql, $conn->error);
            throw new \Exception($errMsg);
        }

        $executed = $stmt->bind_param(
            "ssiddsi",
            $this->grantNumber,
            $this->title,
            $this->sourceId,
            $this->originalAmount,
            $this->balance,
            $this->status,
            $this->adminId,
        ) && $stmt->execute() && $stmt->close();
        

        if (!$executed) {
            throw new \Exception($conn->error);
        }
        $this->id = $conn->insert_id;

        $insertUserGrantMapSql = <<<EOD
            INSERT INTO tbl_fact_map_grant_users (grant_id, user_id)
            VALUES (?, ?)
        EOD;

        $stmt = $conn->prepare($insertUserGrantMapSql);

        if (!$stmt) {
            $errMsg = sprintf("An error occurred preparing your query: %s - %s", $insertUserGrantMapSql, $conn->error);
            throw new \Exception($errMsg);
        }

        $executed = $stmt->bind_param(
            "ii",
            $this->id,
            $userId
        );

        foreach ($this->recipients as $user) {
            $userId = $user->getId();
            $executed = $executed && $stmt->execute();

            if (!$executed) {
                throw new \Exception($conn->error);
            }
        }
        $stmt->close();
        
        return $this;
    }
}
