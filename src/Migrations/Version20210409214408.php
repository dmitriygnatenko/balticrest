<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210409214408 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws Exception
     */
    public function up(Schema $schema) : void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('
            CREATE TABLE search_data (
                id INT AUTO_INCREMENT NOT NULL,
                object_id INT NOT NULL,
                object_type_id SMALLINT NOT NULL,
                data LONGTEXT NOT NULL,
                last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                FULLTEXT INDEX IDX_F6E9E60AADF3F363 (data),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE search_log (
                id INT AUTO_INCREMENT NOT NULL,
                query VARCHAR(255) NOT NULL,
                found_results_count SMALLINT NOT NULL,
                create_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    /**
     * @param Schema $schema
     *
     * @throws Exception
     */
    public function down(Schema $schema) : void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE search_data');
        $this->addSql('DROP TABLE search_log');
    }
}
