<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200819190809 extends AbstractMigration
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
            CREATE TABLE user_confirm_code (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                type SMALLINT NOT NULL,
                code VARCHAR(50) NOT NULL,
                created DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                UNIQUE INDEX UNIQ_6B857B0EA76ED395 (user_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
         ');

        $this->addSql('ALTER TABLE user_confirm_code ADD CONSTRAINT FK_6B857B0EA76ED395
            FOREIGN KEY (user_id) REFERENCES user (id)');
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

        $this->addSql('DROP TABLE user_confirm_code');
    }
}
