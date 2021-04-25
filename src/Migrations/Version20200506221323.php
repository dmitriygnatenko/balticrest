<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200506221323 extends AbstractMigration
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

        $this->addSql('CREATE TABLE language (
            id INT AUTO_INCREMENT NOT NULL,
            code VARCHAR(2) NOT NULL,
            title VARCHAR(150) NOT NULL,
            is_active TINYINT(1) DEFAULT \'1\' NOT NULL,
            INDEX IDX_D4DB71B51B5771DD (is_active),
            UNIQUE INDEX UNIQ_D4DB71B577153098 (code),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
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

        $this->addSql('DROP TABLE language');
    }
}
