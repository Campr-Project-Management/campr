<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180319025708 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_user CHANGE show_in_rasci show_in_rasci TINYINT(1) DEFAULT \'1\', CHANGE show_in_org show_in_org TINYINT(1) DEFAULT \'1\'');
        $this->addSql('ALTER TABLE team_invite ADD project_id INT DEFAULT NULL, CHANGE team_id team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE team_invite ADD CONSTRAINT FK_B1F9570E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_B1F9570E166D1F9C ON team_invite (project_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_user CHANGE show_in_rasci show_in_rasci TINYINT(1) DEFAULT NULL, CHANGE show_in_org show_in_org TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE team_invite DROP FOREIGN KEY FK_B1F9570E166D1F9C');
        $this->addSql('DROP INDEX IDX_B1F9570E166D1F9C ON team_invite');
        $this->addSql('ALTER TABLE team_invite DROP project_id, CHANGE team_id team_id INT NOT NULL');
    }
}
