<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170910230519 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rasci (id INT AUTO_INCREMENT NOT NULL, work_package_id INT DEFAULT NULL, user_id INT DEFAULT NULL, data VARCHAR(255) NOT NULL, INDEX IDX_EAA3E345EF2F062C (work_package_id), INDEX IDX_EAA3E345A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rasci ADD CONSTRAINT FK_EAA3E345EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE rasci ADD CONSTRAINT FK_EAA3E345A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('INSERT INTO rasci SELECT * FROM raci');
        $this->addSql('DROP TABLE raci');
        $this->addSql('ALTER TABLE project_user CHANGE show_in_raci show_in_rasci TINYINT(1) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE raci (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, work_package_id INT DEFAULT NULL, data VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_D3D9F784EF2F062C (work_package_id), INDEX IDX_D3D9F784A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE raci ADD CONSTRAINT FK_D3D9F784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE raci ADD CONSTRAINT FK_D3D9F784EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('INSERT INTO raci SELECT * FROM rasci');
        $this->addSql('DROP TABLE rasci');
        $this->addSql('ALTER TABLE project_user CHANGE show_in_rasci show_in_raci TINYINT(1) DEFAULT NULL');
    }
}
