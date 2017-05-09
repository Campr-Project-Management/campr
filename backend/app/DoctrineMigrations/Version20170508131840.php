<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170508131840 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM `project_user_project_role`');
        $this->addSql('DELETE FROM `project_role`');
        $this->addSql('INSERT INTO `project_role` (`id`, `name`, `sequence`, `is_lead`, `created_at`)
            VALUES
            (1, \'roles.project_sponsor\', 1, 0, \'2017-01-01\'),
            (2, \'roles.project_manager\', 2, 0, \'2017-01-01\'),
            (3, \'roles.team_member\', 3, 0, \'2017-01-01\'),
            (4, \'roles.team_leader\', 4, 0, \'2017-01-01\')
        ');
    }
    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
