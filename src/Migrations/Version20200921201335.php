<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200921201335 extends AbstractMigration
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

        $this->addSql('
            CREATE TABLE article (
                id INT AUTO_INCREMENT NOT NULL,
                url VARCHAR(255) NOT NULL,
                is_active TINYINT(1) DEFAULT \'1\' NOT NULL,
                INDEX IDX_23A0E661B5771DD (is_active),
                UNIQUE INDEX UNIQ_23A0E66F47645AE (url),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');

        $this->addSql('
            CREATE TABLE article_lang_data (
                id INT AUTO_INCREMENT NOT NULL,
                article_id INT NOT NULL,
                language_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                text LONGTEXT NOT NULL,
                meta_keywords VARCHAR(255) NOT NULL,
                meta_description VARCHAR(255) NOT NULL,
                INDEX IDX_62A6EA207294869C (article_id),
                INDEX IDX_62A6EA2082F1BAF4 (language_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
       ');

        $this->addSql('ALTER TABLE article_lang_data ADD CONSTRAINT FK_62A6EA207294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_lang_data ADD CONSTRAINT FK_62A6EA2082F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
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

        $this->addSql('ALTER TABLE article_lang_data DROP FOREIGN KEY FK_62A6EA207294869C');

        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_lang_data');
    }
}
