<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20180704120139 extends AbstractMigration
{
    /**
     * This migration will fix the association between todos, infos, decision to projects.
     *
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
            UPDATE decision
            SET project_id = (SELECT project_id FROM meeting WHERE meeting.id = decision.meeting_id)
            WHERE project_id IS NULL
        ');

        $this->addSql('
            UPDATE info
            SET project_id = (SELECT project_id FROM meeting WHERE meeting.id = info.meeting_id)
            WHERE project_id IS NULL
        ');

        $this->addSql('
            UPDATE todo
            SET project_id = (SELECT project_id FROM meeting WHERE meeting.id = todo.meeting_id)
            WHERE project_id IS NULL
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
