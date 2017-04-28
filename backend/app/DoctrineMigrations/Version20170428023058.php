<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170428023058 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE subteam_member (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, subteam_id INT NOT NULL, INDEX IDX_497FDE14A76ED395 (user_id), INDEX IDX_497FDE14620E7061 (subteam_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subteam (id INT AUTO_INCREMENT NOT NULL, proect_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1D55D4947ED7C507 (proect_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subteam_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subteam_role_subteam_member (subteam_role_id INT NOT NULL, subteam_member_id INT NOT NULL, INDEX IDX_C158B1E2923E94FB (subteam_role_id), INDEX IDX_C158B1E2A26F4AE9 (subteam_member_id), PRIMARY KEY(subteam_role_id, subteam_member_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subteam_member ADD CONSTRAINT FK_497FDE14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subteam_member ADD CONSTRAINT FK_497FDE14620E7061 FOREIGN KEY (subteam_id) REFERENCES subteam (id)');
        $this->addSql('ALTER TABLE subteam ADD CONSTRAINT FK_1D55D4947ED7C507 FOREIGN KEY (proect_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE subteam_role_subteam_member ADD CONSTRAINT FK_C158B1E2923E94FB FOREIGN KEY (subteam_role_id) REFERENCES subteam_role (id)');
        $this->addSql('ALTER TABLE subteam_role_subteam_member ADD CONSTRAINT FK_C158B1E2A26F4AE9 FOREIGN KEY (subteam_member_id) REFERENCES subteam_member (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE subteam_role_subteam_member DROP FOREIGN KEY FK_C158B1E2A26F4AE9');
        $this->addSql('ALTER TABLE subteam_member DROP FOREIGN KEY FK_497FDE14620E7061');
        $this->addSql('ALTER TABLE subteam_role_subteam_member DROP FOREIGN KEY FK_C158B1E2923E94FB');
        $this->addSql('DROP TABLE subteam_member');
        $this->addSql('DROP TABLE subteam');
        $this->addSql('DROP TABLE subteam_role');
        $this->addSql('DROP TABLE subteam_role_subteam_member');
    }
}
