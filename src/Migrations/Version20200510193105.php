<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200510193105 extends AbstractMigration
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

        $this->addSql('CREATE TABLE news (
            id INT AUTO_INCREMENT NOT NULL,
            image VARCHAR(255) NOT NULL,
            publish_date DATE NOT NULL,
            tags JSON NOT NULL,
            is_active TINYINT(1) DEFAULT \'1\' NOT NULL,
            INDEX IDX_1DD399501B5771DD (is_active),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE news_lang_data (
            id INT AUTO_INCREMENT NOT NULL,
            news_id INT NOT NULL,
            language_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            text LONGTEXT NOT NULL,
            INDEX IDX_A4B6E5C5B5A459A0 (news_id), 
            INDEX IDX_A4B6E5C582F1BAF4 (language_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE news_lang_data ADD CONSTRAINT FK_A4B6E5C5B5A459A0 FOREIGN KEY (news_id)
            REFERENCES news (id)');

        $this->addSql('ALTER TABLE news_lang_data ADD CONSTRAINT FK_A4B6E5C582F1BAF4 FOREIGN KEY (language_id)
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

        $this->addSql('ALTER TABLE news_lang_data DROP FOREIGN KEY FK_A4B6E5C582F1BAF4');
        $this->addSql('ALTER TABLE news_lang_data DROP FOREIGN KEY FK_A4B6E5C5B5A459A0');
        $this->addSql('DROP TABLE news_lang_data');
        $this->addSql('DROP TABLE news');
    }
}
