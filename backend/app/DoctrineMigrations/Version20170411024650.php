<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170411024650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_package_media (work_package_id INT NOT NULL, media_id INT NOT NULL, INDEX IDX_BFF5DFDFEF2F062C (work_package_id), INDEX IDX_BFF5DFDFEA9FDD75 (media_id), PRIMARY KEY(work_package_id, media_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cost (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, work_package_id INT DEFAULT NULL, resource_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, type INT DEFAULT 0 NOT NULL, rate NUMERIC(9, 2) NOT NULL, quantity NUMERIC(9, 2) NOT NULL, unit NUMERIC(9, 2) DEFAULT NULL, duration VARCHAR(64) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_182694FC166D1F9C (project_id), INDEX IDX_182694FCEF2F062C (work_package_id), INDEX IDX_182694FC89329D25 (resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_package_media ADD CONSTRAINT FK_BFF5DFDFEF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE work_package_media ADD CONSTRAINT FK_BFF5DFDFEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FC166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FCEF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE cost ADD CONSTRAINT FK_182694FC89329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE work_package ADD automatic_schedule TINYINT(1) DEFAULT \'0\' NOT NULL, ADD duration VARCHAR(64) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cost DROP FOREIGN KEY FK_182694FC89329D25');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE work_package_media');
        $this->addSql('DROP TABLE cost');
        $this->addSql('ALTER TABLE work_package DROP automatic_schedule, DROP duration');
    }
}
