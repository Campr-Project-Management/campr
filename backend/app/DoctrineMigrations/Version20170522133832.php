<?php

namespace Application\Migrations;

use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;


/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170522133832 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE work_package SET work_package_status_id = ' . WorkPackageStatus::OPEN . ' ' .
            'WHERE work_package_status_id IS NULL AND  type = ' . WorkPackage::TYPE_TASK
        );

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // the code for this down() method is empty because
        // once we set all the work packages with status = null to status=OPEN we cannot reverse that action
        // we can't identify the work packages  that had status=null  before running the up() method

    }
}
