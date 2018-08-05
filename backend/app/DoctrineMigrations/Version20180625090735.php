<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180625090735 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE todo_status ADD code VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_128BF03E77153098 ON todo_status (code)');

        $this->addSql("UPDATE todo_status SET code = 'initiated' WHERE id = 1");
        $this->addSql("UPDATE todo_status SET code = 'ongoing' WHERE id = 2");
        $this->addSql("UPDATE todo_status SET code = 'finished' WHERE id = 3");
        $this->addSql("UPDATE todo_status SET code = 'on_hold' WHERE id = 4");
        $this->addSql("UPDATE todo_status SET code = 'discountinued' WHERE id = 5");

        $this->addSql('ALTER TABLE todo_status MODIFY code VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_128BF03E77153098 ON todo_status');
        $this->addSql('ALTER TABLE todo_status DROP code');
    }
}
