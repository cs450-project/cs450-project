<?php

namespace CS450\Model;

use CS450\Model;
use CS450\Service\DbService;

final class GrantBuilder {
    private $db;

    public $id;
    public $title;
    public $status;
    public $adminId;
    public $balance;
    public $sourceId;
    public $grantNumber;
    public $originalAmount;
    public $recipients = [];

    /**
     * @Inject
     * @param DbService $db
     */
    function __construct(DbService $db) {
        $this->db = $db;
    }

    function build(): Grant {
        return new Grant($this, $this->db);
    }

    function startupGrant() {
        $oduSourceId = $this->db->getConnection()
            ->query("SELECT id FROM tbl_fact_granting_entity WHERE name='ODU'")
            ->fetch_object()
            ->id;

        $this->status = "APPROVED";
        $this->title = "Starup Fund";
        $this->sourceId = $oduSourceId;
        $this->grantNumber = "ODU-STARTUP";

        return $this;
    }

    function for(User $user): Self {
        array_push($this->recipients, $user);   
        return $this;
    }

    function id($id): Self {
        $this->id = $id;
        return $this;
    }

    function title($title): Self {
        $this->title = $title;
        return $this;
    }

    function grantNumber($grantNumber): Self {
        $this->grantNumber = $grantNumber;
        return $this;
    }

    function sourceId($sourceId): Self {
        $this->sourceId = $sourceId;
        return $this;
    }

    function originalAmount($originalAmount): Self {
        $this->originalAmount = $originalAmount;
        return $this;
    }

    function balance($balance): Self {
        $this->balance = $balance;
        return $this;
    }

    function status($status): Self {
        $this->status = $status;
        return $this;
    }

    function adminId($adminId): Self {
        $this->adminId = $adminId;
        return $this;
    }
}
