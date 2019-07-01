<?php

namespace Application\Migrations;

use AppBundle\Entity\ProjectStatus;
use AppBundle\Entity\Status;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190622111222 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' != $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE project_status ADD code VARCHAR(255) NOT NULL, CHANGE sequence sequence INT NOT NULL');
        $this->addSql('UPDATE project_status SET code = name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6CA48E5677153098 ON project_status (code)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' != $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP INDEX UNIQ_6CA48E5677153098 ON project_status');
        $this->addSql('ALTER TABLE project_status DROP code, CHANGE sequence sequence INT DEFAULT 0 NOT NULL');
    }
}
