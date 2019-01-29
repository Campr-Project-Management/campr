<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190130073349 extends AbstractMigration
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

        foreach ($this->getProjectsModules() as $projectId => $modules) {
            $this->addSql(
                sprintf(
                    "UPDATE status_report SET modules = '%s' WHERE project_id = %d AND modules is not null",
                    json_encode($modules),
                    $projectId
                )
            );
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
    }

    /**
     * @return array
     */
    private function getProjectsModules(): array
    {
        $sql = 'SELECT p.id, pm.module FROM status_report sr
                INNER JOIN project p ON p.id = sr.project_id
                INNER JOIN project_module pm ON p.id = pm.project_id AND pm.is_enabled = 1';

        $stmt = $this->connection->executeQuery($sql);
        $data = [];
        while (false !== ($row = $stmt->fetch())) {
            $projectId = $row['id'];
            $module = $row['module'];
            if (!isset($data[$projectId])) {
                $data[$projectId] = [];
            }

            $data[$projectId][] = $module;
        }

        foreach ($data as $projectId => $modules) {
            $data[$projectId] = array_values(array_unique($modules));
        }

        return $data;
    }
}
