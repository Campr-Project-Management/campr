<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170810124920 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_status ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity_status ADD CONSTRAINT FK_38311878166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_38311878166D1F9C ON opportunity_status (project_id)');
        $this->addSql('ALTER TABLE opportunity_strategy ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opportunity_strategy ADD CONSTRAINT FK_3B63A5FE166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_3B63A5FE166D1F9C ON opportunity_strategy (project_id)');
        $this->addSql('ALTER TABLE risk_strategy ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE risk_strategy ADD CONSTRAINT FK_F26F0682166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_F26F0682166D1F9C ON risk_strategy (project_id)');
        $this->addSql('ALTER TABLE status ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651C166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_7B00651C166D1F9C ON status (project_id)');
        $this->addSql('ALTER TABLE opportunity CHANGE budget budget NUMERIC(25, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE risk CHANGE cost cost NUMERIC(25, 2) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE opportunity_status DROP FOREIGN KEY FK_38311878166D1F9C');
        $this->addSql('DROP INDEX IDX_38311878166D1F9C ON opportunity_status');
        $this->addSql('ALTER TABLE opportunity_status DROP project_id');
        $this->addSql('ALTER TABLE opportunity_strategy DROP FOREIGN KEY FK_3B63A5FE166D1F9C');
        $this->addSql('DROP INDEX IDX_3B63A5FE166D1F9C ON opportunity_strategy');
        $this->addSql('ALTER TABLE opportunity_strategy DROP project_id');
        $this->addSql('ALTER TABLE risk_strategy DROP FOREIGN KEY FK_F26F0682166D1F9C');
        $this->addSql('DROP INDEX IDX_F26F0682166D1F9C ON risk_strategy');
        $this->addSql('ALTER TABLE risk_strategy DROP project_id');
        $this->addSql('ALTER TABLE status DROP FOREIGN KEY FK_7B00651C166D1F9C');
        $this->addSql('DROP INDEX IDX_7B00651C166D1F9C ON status');
        $this->addSql('ALTER TABLE status DROP project_id');
        $this->addSql('ALTER TABLE opportunity CHANGE budget budget NUMERIC(9, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE risk CHANGE cost cost NUMERIC(9, 2) DEFAULT NULL');
    }
}
