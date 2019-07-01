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
class Version20190622111223 extends AbstractMigration implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $projectStatuses = $em->getRepository(ProjectStatus::class)->findAll();
        $sequence = 5;

        /** @var ProjectStatus $projectStatus */
        foreach ($projectStatuses as $projectStatus) {
            // hardcoded sequences for these 5, higher dynamic sequences for any other status
            if ($projectStatus->getCode() === ProjectStatus::CODE_NOT_STARTED) {
                $projectStatus->setSequence(0);
            } elseif ($projectStatus->getCode() === ProjectStatus::CODE_IN_PROGRESS) {
                $projectStatus->setSequence(1);
            } elseif ($projectStatus->getCode() === ProjectStatus::CODE_PENDING) {
                $projectStatus->setSequence(2);
            } elseif ($projectStatus->getCode() === ProjectStatus::CODE_OPEN) {
                $projectStatus->setSequence(3);
            } elseif ($projectStatus->getCode() === ProjectStatus::CODE_CLOSED) {
                $projectStatus->setSequence(4);
            } else {
                $projectStatus->setSequence($sequence);
                $sequence++;
            }
        }

        $em->flush();
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
    }
}
