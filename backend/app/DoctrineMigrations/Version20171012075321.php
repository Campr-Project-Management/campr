<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171012075321 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_role ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_role ADD CONSTRAINT FK_6EF84272727ACA70 FOREIGN KEY (parent_id) REFERENCES project_role (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_6EF84272727ACA70 ON project_role (parent_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project_role DROP FOREIGN KEY FK_6EF84272727ACA70');
        $this->addSql('DROP INDEX IDX_6EF84272727ACA70 ON project_role');
        $this->addSql('ALTER TABLE project_role DROP parent_id');
    }
}
