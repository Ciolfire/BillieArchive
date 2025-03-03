<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303175417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE body_thief_society (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, advantage LONGTEXT NOT NULL, weakness LONGTEXT NOT NULL, description LONGTEXT NOT NULL, creation LONGTEXT NOT NULL, talent_type INT NOT NULL, page SMALLINT DEFAULT NULL, defining_merit_id INT DEFAULT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_A791B748283CD845 (defining_merit_id), INDEX IDX_A791B74816A2B381 (book_id), INDEX IDX_A791B7487B02D7EA (homebrew_for_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE bodythief_society (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX bodythief_society_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE body_thief_society ADD CONSTRAINT FK_A791B748283CD845 FOREIGN KEY (defining_merit_id) REFERENCES merits (id)');
        $this->addSql('ALTER TABLE body_thief_society ADD CONSTRAINT FK_A791B74816A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE body_thief_society ADD CONSTRAINT FK_A791B7487B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE body_thief_society DROP FOREIGN KEY FK_A791B748283CD845');
        $this->addSql('ALTER TABLE body_thief_society DROP FOREIGN KEY FK_A791B74816A2B381');
        $this->addSql('ALTER TABLE body_thief_society DROP FOREIGN KEY FK_A791B7487B02D7EA');
        $this->addSql('DROP TABLE body_thief_society');
        $this->addSql('DROP TABLE bodythief_society');
    }
}
