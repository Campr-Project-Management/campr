<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180510222423 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_user ADD rate NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD project_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F4163170DFF0 FOREIGN KEY (project_user_id) REFERENCES project_user (id)');
        $this->addSql('CREATE INDEX IDX_BC91F4163170DFF0 ON resource (project_user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_user DROP rate');
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F4163170DFF0');
        $this->addSql('DROP INDEX IDX_BC91F4163170DFF0 ON resource');
        $this->addSql('ALTER TABLE resource DROP project_user_id');
    }
}
