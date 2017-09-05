<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170830122846 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package ADD accountability_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7EFF0A1F4 FOREIGN KEY (accountability_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BA3DFB7EFF0A1F4 ON work_package (accountability_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7EFF0A1F4');
        $this->addSql('DROP INDEX IDX_BA3DFB7EFF0A1F4 ON work_package');
        $this->addSql('ALTER TABLE work_package DROP accountability_id');
    }
}
