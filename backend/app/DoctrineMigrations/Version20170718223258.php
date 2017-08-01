<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170718223258 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE info_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(7) DEFAULT \'#ffffff\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, info_category_id INT NOT NULL, info_status_id INT NOT NULL, topic VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, expiry_date DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_CB893157166D1F9C (project_id), INDEX IDX_CB893157CD7EB71D (info_category_id), INDEX IDX_CB893157C687BD80 (info_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE info_user (info_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D4F804C75D8BC1F8 (info_id), INDEX IDX_D4F804C7A76ED395 (user_id), PRIMARY KEY(info_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE info ADD CONSTRAINT FK_CB893157166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE info ADD CONSTRAINT FK_CB893157CD7EB71D FOREIGN KEY (info_category_id) REFERENCES info_category (id)');
        $this->addSql('ALTER TABLE info ADD CONSTRAINT FK_CB893157C687BD80 FOREIGN KEY (info_status_id) REFERENCES info_status (id)');
        $this->addSql('ALTER TABLE info_user ADD CONSTRAINT FK_D4F804C75D8BC1F8 FOREIGN KEY (info_id) REFERENCES info (id)');
        $this->addSql('ALTER TABLE info_user ADD CONSTRAINT FK_D4F804C7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');

        // fixtures
        $infoCategories = [
            1 => 'label.production',
            2 => 'label.logistics',
            3 => 'label.quality_management',
            4 => 'label.human_resources',
            5 => 'label.purchasing',
            6 => 'label.maintenance',
            7 => 'label.assembly',
            8 => 'label.tooling',
            9 => 'label.process_engineering',
            10 => 'label.industrialization',
        ];
        $infoStatuses = [
            [1, 'label.initiated', '#CCBA54'],
            [2, 'label.ongoing', '#197252'],
            [3, 'label.on_hold', '#D8DAE5'],
            [4, 'label.published', '#5FC3A5'],
            [5, 'label.expired', '#C87369'],
        ];

        foreach ($infoCategories as $id => $name) {
            $this->addSql(
                'INSERT INTO `info_category` SET `id` = :id, `name` = :name',
                [
                    'id' => $id,
                    'name' => $name,
                ]
            );
        }
        foreach ($infoStatuses as $row) {
            list($id, $name, $color) = $row;
            $this->addSql(
                'INSERT INTO `info_status` SET `id` = :id, `name` = :name, `color` = :color',
                [
                    'id' => $id,
                    'name' => $name,
                    'color' => $color,
                ]
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE info DROP FOREIGN KEY FK_CB893157CD7EB71D');
        $this->addSql('ALTER TABLE info DROP FOREIGN KEY FK_CB893157C687BD80');
        $this->addSql('ALTER TABLE info_user DROP FOREIGN KEY FK_D4F804C75D8BC1F8');
        $this->addSql('DROP TABLE info_category');
        $this->addSql('DROP TABLE info_status');
        $this->addSql('DROP TABLE info');
        $this->addSql('DROP TABLE info_user');
    }
}
