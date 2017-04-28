<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170428125809 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subteam DROP FOREIGN KEY FK_1D55D4947ED7C507');
        $this->addSql('DROP INDEX IDX_1D55D4947ED7C507 ON subteam');
        $this->addSql('ALTER TABLE subteam ADD parent_id INT DEFAULT NULL, CHANGE proect_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subteam ADD CONSTRAINT FK_1D55D494166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE subteam ADD CONSTRAINT FK_1D55D494727ACA70 FOREIGN KEY (parent_id) REFERENCES subteam (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_1D55D494166D1F9C ON subteam (project_id)');
        $this->addSql('CREATE INDEX IDX_1D55D494727ACA70 ON subteam (parent_id)');
        $this->addSql('ALTER TABLE subteam_member ADD is_lead TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subteam DROP FOREIGN KEY FK_1D55D494166D1F9C');
        $this->addSql('ALTER TABLE subteam DROP FOREIGN KEY FK_1D55D494727ACA70');
        $this->addSql('DROP INDEX IDX_1D55D494166D1F9C ON subteam');
        $this->addSql('DROP INDEX IDX_1D55D494727ACA70 ON subteam');
        $this->addSql('ALTER TABLE subteam ADD proect_id INT DEFAULT NULL, DROP project_id, DROP parent_id');
        $this->addSql('ALTER TABLE subteam ADD CONSTRAINT FK_1D55D4947ED7C507 FOREIGN KEY (proect_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_1D55D4947ED7C507 ON subteam (proect_id)');
        $this->addSql('ALTER TABLE subteam_member DROP is_lead');
    }
}
