<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200508212416 extends AbstractMigration
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

        $this->addSql('CREATE TABLE point (
            id INT AUTO_INCREMENT NOT NULL,
            city_id INT NOT NULL,
            type_id INT NOT NULL,
            lat VARCHAR(50) NOT NULL,
            lon VARCHAR(50) NOT NULL,
            url VARCHAR(255) DEFAULT NULL,
            logo VARCHAR(255) NOT NULL,
            data JSON NOT NULL,
            is_active TINYINT(1) DEFAULT \'1\' NOT NULL,
            last_update_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
            INDEX IDX_B7A5F3248BAC62AF (city_id), 
            INDEX IDX_B7A5F324C54C8C93 (type_id),
            INDEX IDX_B7A5F3241B5771DD (is_active),
            UNIQUE INDEX UNIQ_B7A5F324F47645AE (url),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F3248BAC62AF FOREIGN KEY (city_id)
            REFERENCES city (id)');

        $this->addSql('ALTER TABLE point ADD CONSTRAINT FK_B7A5F324C54C8C93 FOREIGN KEY (type_id)
            REFERENCES point_type (id)');

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


        $this->addSql('ALTER TABLE point_lang_data ADD CONSTRAINT FK_1BBDDF2AC028CEA2 FOREIGN KEY (point_id)
            REFERENCES point (id)');

        $this->addSql('ALTER TABLE point_lang_data ADD CONSTRAINT FK_1BBDDF2A82F1BAF4 FOREIGN KEY (language_id)
            REFERENCES language (id)');
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

        $this->addSql('ALTER TABLE point_lang_data DROP FOREIGN KEY FK_1BBDDF2A82F1BAF4');
        $this->addSql('ALTER TABLE point_lang_data DROP FOREIGN KEY FK_1BBDDF2AC028CEA2');
        $this->addSql('DROP TABLE point_lang_data');

        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F3248BAC62AF');
        $this->addSql('ALTER TABLE point DROP FOREIGN KEY FK_B7A5F324C54C8C93');
        $this->addSql('DROP TABLE point');
    }
}
