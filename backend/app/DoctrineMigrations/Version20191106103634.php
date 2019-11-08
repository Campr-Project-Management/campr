<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20191106103634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_complexity DROP FOREIGN KEY FK_4043BDA9166D1F9C');
        $this->addSql('DROP INDEX IDX_4043BDA9166D1F9C ON project_complexity');
        $this->addSql('ALTER TABLE project_complexity DROP project_id');
        $this->addSql('ALTER TABLE project_category DROP FOREIGN KEY FK_3B02921A166D1F9C');
        $this->addSql('DROP INDEX IDX_3B02921A166D1F9C ON project_category');
        $this->addSql('ALTER TABLE project_category DROP project_id');
        $this->addSql('ALTER TABLE label DROP FOREIGN KEY FK_EA750E8166D1F9C');
        $this->addSql('DROP INDEX IDX_EA750E8166D1F9C ON label');
        $this->addSql('ALTER TABLE label DROP project_id');
        $this->addSql('ALTER TABLE project_scope DROP FOREIGN KEY FK_13FA5C4D166D1F9C');
        $this->addSql('DROP INDEX IDX_13FA5C4D166D1F9C ON project_scope');
        $this->addSql('ALTER TABLE project_scope DROP project_id');
        $this->addSql('ALTER TABLE project_status DROP FOREIGN KEY FK_6CA48E56166D1F9C');
        $this->addSql('DROP INDEX IDX_6CA48E56166D1F9C ON project_status');
        $this->addSql('ALTER TABLE project_status DROP project_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE label ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE label ADD CONSTRAINT FK_EA750E8166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_EA750E8166D1F9C ON label (project_id)');
        $this->addSql('ALTER TABLE project_category ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_category ADD CONSTRAINT FK_3B02921A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_3B02921A166D1F9C ON project_category (project_id)');
        $this->addSql('ALTER TABLE project_complexity ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_complexity ADD CONSTRAINT FK_4043BDA9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4043BDA9166D1F9C ON project_complexity (project_id)');
        $this->addSql('ALTER TABLE project_scope ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_scope ADD CONSTRAINT FK_13FA5C4D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_13FA5C4D166D1F9C ON project_scope (project_id)');
        $this->addSql('ALTER TABLE project_status ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_status ADD CONSTRAINT FK_6CA48E56166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6CA48E56166D1F9C ON project_status (project_id)');
    }
}
