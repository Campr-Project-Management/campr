<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170831130838 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE work_package_support_user (work_package_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6BE961F0EF2F062C (work_package_id), INDEX IDX_6BE961F0A76ED395 (user_id), PRIMARY KEY(work_package_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_package_consulted_user (work_package_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_988DBEA9EF2F062C (work_package_id), INDEX IDX_988DBEA9A76ED395 (user_id), PRIMARY KEY(work_package_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_package_informed_user (work_package_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_56D96C9BEF2F062C (work_package_id), INDEX IDX_56D96C9BA76ED395 (user_id), PRIMARY KEY(work_package_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE work_package_support_user ADD CONSTRAINT FK_6BE961F0EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package_support_user ADD CONSTRAINT FK_6BE961F0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_package_consulted_user ADD CONSTRAINT FK_988DBEA9EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package_consulted_user ADD CONSTRAINT FK_988DBEA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_package_informed_user ADD CONSTRAINT FK_56D96C9BEF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package_informed_user ADD CONSTRAINT FK_56D96C9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE work_package_support_user');
        $this->addSql('DROP TABLE work_package_consulted_user');
        $this->addSql('DROP TABLE work_package_informed_user');
    }
}
