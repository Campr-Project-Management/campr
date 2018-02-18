<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180214040514 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('SET foreign_key_checks=0');
        $this->addSql('UPDATE info SET info_status_id = 1 WHERE info_status_id IN (1, 2, 3, 4)');
        $this->addSql('UPDATE info SET info_status_id = 2 WHERE info_status_id IN (5)');
        $this->addSql('DELETE FROM info_status WHERE id > 2');
        $this->addSql(
            "REPLACE INTO info_status (id, name, color)
            VALUES
                (1, 'label.published', '#5FC3A5'),
                (2, 'label.expired', '#C87369')"
        );
        $this->addSql('SET foreign_key_checks=1');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('SET foreign_key_checks=0');
        $this->addSql(
            "REPLACE INTO info_status (id, name, color)
            VALUES
                (1, 'label.initiated', '#CCBA54'),
                (2, 'label.ongoing', '#197252'),
                (3, 'label.on_hold', '#D8DAE5'),
                (4, 'label.published', '#5FC3A5'),
                (5, 'label.expired', '#C87369')"
        );
        $this->addSql('UPDATE info SET info_status_id = 4 WHERE info_status_id = 1');
        $this->addSql('UPDATE info SET info_status_id = 5 WHERE info_status_id = 2');
        $this->addSql('SET foreign_key_checks=1');
    }
}
