<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170216072727 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_team ADD project_id INT NOT NULL, ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07727ACA70 FOREIGN KEY (parent_id) REFERENCES project_team (id)');
        $this->addSql('CREATE INDEX IDX_FD716E07166D1F9C ON project_team (project_id)');
        $this->addSql('CREATE INDEX IDX_FD716E07727ACA70 ON project_team (parent_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_team DROP FOREIGN KEY FK_FD716E07166D1F9C');
        $this->addSql('ALTER TABLE project_team DROP FOREIGN KEY FK_FD716E07727ACA70');
        $this->addSql('DROP INDEX IDX_FD716E07166D1F9C ON project_team');
        $this->addSql('DROP INDEX IDX_FD716E07727ACA70 ON project_team');
        $this->addSql('ALTER TABLE project_team DROP project_id, DROP parent_id');
    }
}
