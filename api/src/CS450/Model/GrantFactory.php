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
        $selectAllGrantsQ = <<<EOD
            SELECT tbl_fact_grants.id, tbl_fact_grants.title, tbl_fact_grants.status, tbl_fact_grants.balance, 
                tbl_fact_grants.original_amt, tbl_fact_grants.grant_number,
                entity.id AS `_entity_id`, entity.name AS `_entity_name`, entity.type AS `_entity_type`,
                admin.id AS `_admin_id`, admin.email AS `_admin_email`, admin.name AS `_admin_name`, 
                admin.user_role AS`_admin_role`, admin.department AS `_admin_department`
            FROM tbl_fact_grants
            JOIN tbl_fact_granting_entity AS entity
            ON tbl_fact_grants.source_id = entity.id
            JOIN tbl_fact_users AS admin
            on tbl_fact_grants.administrator_id = admin.id;
        EOD;

        $conn = $this->db->getConnection();
        $result = $conn->query($selectAllGrantsQ);

        if (!$result) {
            $errMsg = sprintf("An error occurred executing your query: %s, %s", $selectAllGrantsQ, $conn->error);
            throw new \Exception($errMsg);
        }

        $grants = [];
        while($grant = $result->fetch_object("CS450\Model\Grant", [$this->db])) {
            $grants[] = $grant;
        }

        return $grants;
    }

    public function findbyUser($params):array{
        $userName=$params["name"];
        // Wrote it just in case I need it
        $department=$params["department"];
        $selectUserGrants = <<<EOD
            SELECT a.name as faculty_name , c.grant_number, c.title, d.name as department from tbl_fact_users a
            JOIN (SELECT grant_i , user_id FROM tbl_map_grant_users) b on a.id = b.user_id JOIN tbl_fact_grants c on b.grant_id = c.source_id
            JOIN tbl_fact_departments d on a.department = d.id
            WHERE a.user_role = ‘FACULTY’
            AND a.name=$userName 
            AND d.name=$department
            AND c.status in ('PENDING' , 'APPROVED');
        EOD;

        $conn = $this->db->getConnection();
        $result = $conn->query($selectUserGrants);

        if (!$result) {
            $errMsg = sprintf("An error occurred executing your query: %s, %s", $selectUserGrants, $conn->error);
            throw new \Exception($errMsg);
        }

        $grants = [];
        while($grant = $result->fetch_object("CS450\Model\Grant", [$this->db])) {
            $grants[] = $grant;
        }

        return $grants;
    }
}
