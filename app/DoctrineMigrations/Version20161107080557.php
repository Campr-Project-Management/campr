<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161107080557 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chat_room (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, is_default TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D403CCDA166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, chat_room_id INT DEFAULT NULL, from_id INT NOT NULL, to_id INT DEFAULT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, read_at DATETIME DEFAULT NULL, INDEX IDX_B6BD307F166D1F9C (project_id), INDEX IDX_B6BD307F1819BCFA (chat_room_id), INDEX IDX_B6BD307F78CED90B (from_id), INDEX IDX_B6BD307F30354A65 (to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_room ADD CONSTRAINT FK_D403CCDA166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F1819BCFA FOREIGN KEY (chat_room_id) REFERENCES chat_room (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F78CED90B FOREIGN KEY (from_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F30354A65 FOREIGN KEY (to_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F1819BCFA');
        $this->addSql('DROP TABLE chat_room');
        $this->addSql('DROP TABLE message');
    }
}
