<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180523072834 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package_status ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_640F3F0C77153098 ON work_package_status (code)');
        $this->addSql("UPDATE work_package_status SET `code` = 'open' WHERE id = 1");
        $this->addSql("UPDATE work_package_status SET `code` = 'pending' WHERE id = 2");
        $this->addSql("UPDATE work_package_status SET `code` = 'ongoing' WHERE id = 3");
        $this->addSql("UPDATE work_package_status SET `code` = 'completed' WHERE id = 4");
        $this->addSql("UPDATE work_package_status SET `code` = 'closed' WHERE id = 5");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_640F3F0C77153098 ON work_package_status');
        $this->addSql('ALTER TABLE work_package_status DROP code');
    }
}
