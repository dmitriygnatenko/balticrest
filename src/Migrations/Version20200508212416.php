<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200508212416 extends AbstractMigration
{
    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function up(Schema $schema) : void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE point (
            id INT AUTO_INCREMENT NOT NULL,
            city_id INT NOT NULL,
            type_id INT NOT NULL,
            lat DOUBLE PRECISION NOT NULL,
            lon DOUBLE PRECISION NOT NULL,
            url VARCHAR(255) NULL,
            logo VARCHAR(255) NOT NULL,
            data JSON NOT NULL,
            is_active TINYINT(1) DEFAULT \'1\' NOT NULL,
            INDEX IDX_B7A5F3248BAC62AF (city_id),
            INDEX IDX_B7A5F324C54C8C93 (type_id),
            INDEX IDX_B7A5F3241B5771DD (is_active),
            UNIQUE INDEX UNIQ_B7A5F324F47645AE (url),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F3248BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');

        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324C54C8C93 FOREIGN KEY (type_id) REFERENCES point_type (id)');
    }

    /**
     * @param Schema $schema
     *
     * @throws DBALException
     */
    public function down(Schema $schema) : void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE point');
    }
}
