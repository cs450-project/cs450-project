<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGrantingEntity extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $sql = file_get_contents(__DIR__ . '/../sql/002_create_granting_entity.sql');
        $this->execute($sql);
    }

    public function down(): void
    {
        $this->execute("DROP TABLE tbl_fact_granting_entity");
    }
}
