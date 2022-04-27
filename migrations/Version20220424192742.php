<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424192742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ruleset SMALLINT NOT NULL, type VARCHAR(50) DEFAULT NULL, short VARCHAR(10) DEFAULT NULL, released_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', setting VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE merits ADD book_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE merits ADD CONSTRAINT FK_501541D616A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_501541D616A2B381 ON merits (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merits DROP FOREIGN KEY FK_501541D616A2B381');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP INDEX IDX_501541D616A2B381 ON merits');
        $this->addSql('ALTER TABLE merits DROP book_id, DROP page');
    }
}
