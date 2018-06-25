<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180628083436 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_status DROP FOREIGN KEY FK_38311878166D1F9C');
        $this->addSql('DROP INDEX IDX_38311878166D1F9C ON opportunity_status');
        $this->addSql('ALTER TABLE opportunity_status ADD code VARCHAR(255) NOT NULL, DROP project_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3831187877153098 ON opportunity_status (code)');
        $this->addSql('SET FOREIGN_KEY_CHECKS=0; TRUNCATE opportunity_status');
        $this->addSql("INSERT INTO opportunity_status(`name`, `code`) VALUES('label.open', 'open')");
        $this->addSql("INSERT INTO opportunity_status(`name`, `code`) VALUES('label.follow_up', 'followup')");
        $this->addSql("INSERT INTO opportunity_status(`name`, `code`) VALUES('label.taken', 'taken')");
        $this->addSql("UPDATE opportunity SET opportunity_status_id = 1");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_3831187877153098 ON opportunity_status');
        $this->addSql('ALTER TABLE opportunity_status ADD project_id INT DEFAULT NULL, DROP code');
        $this->addSql('ALTER TABLE opportunity_status ADD CONSTRAINT FK_38311878166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_38311878166D1F9C ON opportunity_status (project_id)');
    }
}
