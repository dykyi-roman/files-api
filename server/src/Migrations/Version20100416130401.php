<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class CreateFilesTable
 * @package DoctrineMigrations
 */
class Version20100416130401 extends AbstractMigration
{
    const TABLE_NAME = 'files';

    /**
     * @param Schema $schema
     * @return Schema
     */
    public function up(Schema $schema)
    {
        $table = $schema->createTable(self::TABLE_NAME);
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('filename', 'string');
        $table->addColumn('active', 'boolean');
        $table->setPrimaryKey(['id']);

        return $schema;
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable(self::TABLE_NAME);
    }
}
