<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170606151025 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_package_comment (work_package_id INT NOT NULL, comment_id INT NOT NULL, INDEX IDX_89DC4D52EF2F062C (work_package_id), INDEX IDX_89DC4D52F8697D13 (comment_id), PRIMARY KEY(work_package_id, comment_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE work_package_comment ADD CONSTRAINT FK_89DC4D52EF2F062C FOREIGN KEY (work_package_id) REFERENCES work_package (id)');
        $this->addSql('ALTER TABLE work_package_comment ADD CONSTRAINT FK_89DC4D52F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE work_package_comment DROP FOREIGN KEY FK_89DC4D52F8697D13');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE work_package_comment');
    }
}
