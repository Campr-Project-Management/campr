<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170302171458 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_package_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, visible TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UNIQ_640F3F0C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_package ADD work_package_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE work_package ADD CONSTRAINT FK_BA3DFB7A73A0B9D FOREIGN KEY (work_package_status_id) REFERENCES work_package_status (id)');
        $this->addSql('CREATE INDEX IDX_BA3DFB7A73A0B9D ON work_package (work_package_status_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package DROP FOREIGN KEY FK_BA3DFB7A73A0B9D');
        $this->addSql('DROP TABLE work_package_status');
        $this->addSql('DROP INDEX IDX_BA3DFB7A73A0B9D ON work_package');
        $this->addSql('ALTER TABLE work_package DROP work_package_status_id');
    }
}
