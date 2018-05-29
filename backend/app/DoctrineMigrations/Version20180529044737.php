<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180529044737 extends AbstractMigration
{
    // new one
    const UTF8MB4_CHARACTER_SET = 'utf8mb4';
    const UTF8MB4_COLLATE = 'utf8mb4_unicode_ci';

    // old one
    const UTF8_CHARACTER_SET = 'utf8';
    const UTF8_COLLATE = 'utf8_unicode_ci';

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addDatabaseSQL($schema->getName(), self::UTF8MB4_CHARACTER_SET, self::UTF8MB4_COLLATE);
        $this->addTablesSQL($schema->getName(), self::UTF8MB4_CHARACTER_SET, self::UTF8MB4_COLLATE);
        $this->addColumnsSQL($schema->getName(), self::UTF8MB4_CHARACTER_SET, self::UTF8MB4_COLLATE);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addDatabaseSQL($schema->getName(), self::UTF8_CHARACTER_SET, self::UTF8_COLLATE);
        $this->addTablesSQL($schema->getName(), self::UTF8_CHARACTER_SET, self::UTF8_COLLATE);
        $this->addColumnsSQL($schema->getName(), self::UTF8_CHARACTER_SET, self::UTF8_COLLATE);
    }

    private function addDatabaseSQL($dbName, $characterSet, $collate)
    {
        $this->addSql(sprintf(
            'ALTER DATABASE %s CHARACTER SET = %s COLLATE = %s',
            $dbName,
            $characterSet,
            $collate
        ));
    }

    private function addTablesSQL($dbName, $characterSet, $collate)
    {
        $tpl = 'ALTER TABLE %s CONVERT TO CHARACTER SET %s COLLATE %s';

        $stmt = $this
            ->connection
            ->executeQuery(
                'SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = :schema',
                ['schema' => $dbName]
            )
        ;
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $this->addSql(sprintf(
                $tpl,
                $row['TABLE_NAME'],
                $characterSet,
                $collate
            ));
        }
    }

    private function addColumnsSQL($dbName, $characterSet, $collate)
    {
        $tpl = 'ALTER TABLE %s CHANGE %s %s %s';

        $stmt = $this
            ->connection
            ->executeQuery(
                'SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :schema AND CHARACTER_SET_NAME IS NOT NULL',
                ['schema' => $dbName]
            )
        ;
        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $this->addSql(sprintf(
                $tpl,
                $row['TABLE_NAME'],
                $row['COLUMN_NAME'],
                $row['COLUMN_NAME'],
                $this->buildDefinition($row, $characterSet, $collate)
            ));
        }
    }

    private function buildDefinition(array $row, $characterSet, $collate)
    {
        $out = [
            $row['COLUMN_TYPE'],
            sprintf('CHARACTER SET %s', $characterSet),
            sprintf('COLLATE %s', $collate),
        ];

        if ($row['IS_NULLABLE'] === 'NO') {
            $out[] = 'NOT NULL';
        }

        if ($row['COLUMN_DEFAULT'] === 'NULL') {
            $out[] = 'DEFAULT NULL';
        } elseif (!empty($row['COLUMN_DEFAULT'])) {
            $out[] = sprintf('DEFAULT "%s"', $row['COLUMN_DEFAULT']);
        }

        if (!empty($row['COLUMN_COMMENT'])) {
            $out[] = sprintf('COMMENT "%s"', $row['COLUMN_COMMENT']);
        }

        return implode(' ', $out);
    }
}
