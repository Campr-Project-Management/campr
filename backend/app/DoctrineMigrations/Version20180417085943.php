<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180417085943 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6956883F77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE opportunity DROP currency');
        $this->addSql('ALTER TABLE project ADD currency_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE38248176 ON project (currency_id)');
        $this->addSql('ALTER TABLE risk DROP currency');
        $this->addSql("INSERT INTO currency(code, created_at, updated_at) values('EUR', NOW(), NOW())");
        $this->addSql("INSERT INTO currency(code, created_at, updated_at) values('USD', NOW(), NOW())");
        $this->addSql("INSERT INTO currency(code, created_at, updated_at) values('GBP', NOW(), NOW())");
        $this->addSql('UPDATE project SET currency_id = 1');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' != $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE38248176');
        $this->addSql('DROP TABLE currency');
        $this->addSql('ALTER TABLE opportunity ADD currency VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX IDX_2FB3D0EE38248176 ON project');
        $this->addSql('ALTER TABLE project DROP currency_id');
        $this->addSql('ALTER TABLE risk ADD currency VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
