<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200510193105 extends AbstractMigration
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

        $this->addSql('CREATE TABLE point_lang_data (
            id INT AUTO_INCREMENT NOT NULL,
            point_id INT NOT NULL,
            language_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            data JSON NOT NULL,
            INDEX IDX_1BBDDF2AC028CEA2 (point_id),
            INDEX IDX_1BBDDF2A82F1BAF4 (language_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE point_lang_data ADD CONSTRAINT FK_1BBDDF2AC028CEA2 FOREIGN KEY (point_id) REFERENCES point (id)');

        $this->addSql('ALTER TABLE point_lang_data ADD CONSTRAINT FK_1BBDDF2A82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
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

        $this->addSql('DROP TABLE point_lang_data');
    }
}
