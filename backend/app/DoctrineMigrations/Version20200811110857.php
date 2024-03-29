<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20200811110857 extends AbstractMigration
{
    private $table = 'project_status';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $stmt = $this
            ->connection
            ->executeQuery(
                'SELECT * FROM `' . $this->table . '`',
                ['schema' => $schema->getName()]
            );

        if (empty($stmt->fetchAll())) {
            $this->addSql('INSERT INTO `' . $this->table . '`
             (`id`, `name`, `sequence`, `created_at`, `updated_at`, `code`)
                VALUES 
                (1,\'label.not_started\',0,\'2017-08-31 02:41:27\',\'2019-06-25 12:34:45\',\'label.not_started\'),
                (2,\'label.in_progress\',1,\'2017-08-31 02:41:27\',\'2019-06-25 12:34:45\',\'label.in_progress\'),
                (3,\'label.pending\',2,\'2017-08-31 02:41:27\',\'2019-06-25 12:34:45\',\'label.pending\'),
                (4,\'label.open\',3,\'2017-08-31 02:41:27\',\'2019-06-25 12:34:45\',\'label.open\'),
                (5,\'label.closed\',4,\'2017-08-31 02:41:27\',\'2019-06-25 12:34:45\',\'label.closed\')
            ');
        }
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM `' . $this->table . '`');
    }
}
