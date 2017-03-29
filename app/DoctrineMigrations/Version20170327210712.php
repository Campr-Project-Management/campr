<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170327210712 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7727ACA70');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7727ACA70 FOREIGN KEY (parent_id) REFERENCES work_package (id) ON DELETE CASCADE');
        $this->addSql('UPDATE work_package SET puid = id');
        $this->addSql('CREATE UNIQUE INDEX puid_project_unique ON work_package (puid, project_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7727ACA70');
        $this->addSql('DROP INDEX puid_project_unique ON work_package');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7727ACA70 FOREIGN KEY (parent_id) REFERENCES work_package (id)');
    }
}
