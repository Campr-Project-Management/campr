<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170126203740 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE distribution_list (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, sequence INT DEFAULT 0 NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_4C7574AC5E237E06 (name), INDEX IDX_4C7574AC166D1F9C (project_id), INDEX IDX_4C7574ACA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE distribution_list_user (distribution_list_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8FCA49BCDD1F5839 (distribution_list_id), INDEX IDX_8FCA49BCA76ED395 (user_id), PRIMARY KEY(distribution_list_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE distribution_list_meeting (distribution_list_id INT NOT NULL, meeting_id INT NOT NULL, INDEX IDX_A2742F85DD1F5839 (distribution_list_id), INDEX IDX_A2742F8567433D9C (meeting_id), PRIMARY KEY(distribution_list_id, meeting_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE distribution_list ADD CONSTRAINT FK_4C7574AC166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE distribution_list ADD CONSTRAINT FK_4C7574ACA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE distribution_list_user ADD CONSTRAINT FK_8FCA49BCDD1F5839 FOREIGN KEY (distribution_list_id) REFERENCES distribution_list (id)');
        $this->addSql('ALTER TABLE distribution_list_user ADD CONSTRAINT FK_8FCA49BCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE distribution_list_meeting ADD CONSTRAINT FK_A2742F85DD1F5839 FOREIGN KEY (distribution_list_id) REFERENCES distribution_list (id)');
        $this->addSql('ALTER TABLE distribution_list_meeting ADD CONSTRAINT FK_A2742F8567433D9C FOREIGN KEY (meeting_id) REFERENCES meeting (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE distribution_list_user DROP FOREIGN KEY FK_8FCA49BCDD1F5839');
        $this->addSql('ALTER TABLE distribution_list_meeting DROP FOREIGN KEY FK_A2742F85DD1F5839');
        $this->addSql('DROP TABLE distribution_list');
        $this->addSql('DROP TABLE distribution_list_user');
        $this->addSql('DROP TABLE distribution_list_meeting');
    }
}
