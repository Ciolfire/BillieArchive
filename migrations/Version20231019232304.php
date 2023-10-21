<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019232304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ghoul_family (id INT AUTO_INCREMENT NOT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, short LONGTEXT NOT NULL, emblem VARCHAR(255) DEFAULT NULL, nickname VARCHAR(50) NOT NULL, strength LONGTEXT NOT NULL, weakness LONGTEXT NOT NULL, quote VARCHAR(255) NOT NULL, page SMALLINT DEFAULT NULL, INDEX IDX_112D41A016A2B381 (book_id), INDEX IDX_112D41A07B02D7EA (homebrew_for_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ghoul_family ADD CONSTRAINT FK_112D41A016A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE ghoul_family ADD CONSTRAINT FK_112D41A07B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE ghoul ADD family_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ghoul ADD CONSTRAINT FK_4665AF71C35E566A FOREIGN KEY (family_id) REFERENCES ghoul_family (id)');
        $this->addSql('CREATE INDEX IDX_4665AF71C35E566A ON ghoul (family_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ghoul DROP FOREIGN KEY FK_4665AF71C35E566A');
        $this->addSql('ALTER TABLE ghoul_family DROP FOREIGN KEY FK_112D41A016A2B381');
        $this->addSql('ALTER TABLE ghoul_family DROP FOREIGN KEY FK_112D41A07B02D7EA');
        $this->addSql('DROP TABLE ghoul_family');
        $this->addSql('DROP INDEX IDX_4665AF71C35E566A ON ghoul');
        $this->addSql('ALTER TABLE ghoul DROP family_id');
    }
}
