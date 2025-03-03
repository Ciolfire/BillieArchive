<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303122116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bodythief_society (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(191) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX bodybody_thief_society_idx (locale, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE thief_society (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, strengths LONGTEXT NOT NULL, weaknesses LONGTEXT NOT NULL, quote VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, creation LONGTEXT NOT NULL, page SMALLINT DEFAULT NULL, defining_merit_id INT NOT NULL, book_id INT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, INDEX IDX_74478A27283CD845 (defining_merit_id), INDEX IDX_74478A2716A2B381 (book_id), INDEX IDX_74478A277B02D7EA (homebrew_for_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE thief_society_merit (body_thief_society_id INT NOT NULL, merit_id INT NOT NULL, INDEX IDX_21A64B0C729731D3 (body_thief_society_id), INDEX IDX_21A64B0C58D79B5E (merit_id), PRIMARY KEY(body_thief_society_id, merit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE thief_society ADD CONSTRAINT FK_74478A27283CD845 FOREIGN KEY (defining_merit_id) REFERENCES merits (id)');
        $this->addSql('ALTER TABLE thief_society ADD CONSTRAINT FK_74478A2716A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE thief_society ADD CONSTRAINT FK_74478A277B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE thief_society_merit ADD CONSTRAINT FK_21A64B0C729731D3 FOREIGN KEY (body_thief_society_id) REFERENCES thief_society (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE thief_society_merit ADD CONSTRAINT FK_21A64B0C58D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE body_thief ADD possession_method INT NOT NULL, ADD society_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE body_thief ADD CONSTRAINT FK_6C564116E6389D24 FOREIGN KEY (society_id) REFERENCES thief_society (id)');
        $this->addSql('CREATE INDEX IDX_6C564116E6389D24 ON body_thief (society_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE thief_society DROP FOREIGN KEY FK_74478A27283CD845');
        $this->addSql('ALTER TABLE thief_society DROP FOREIGN KEY FK_74478A2716A2B381');
        $this->addSql('ALTER TABLE thief_society DROP FOREIGN KEY FK_74478A277B02D7EA');
        $this->addSql('ALTER TABLE thief_society_merit DROP FOREIGN KEY FK_21A64B0C729731D3');
        $this->addSql('ALTER TABLE thief_society_merit DROP FOREIGN KEY FK_21A64B0C58D79B5E');
        $this->addSql('DROP TABLE bodythief_society');
        $this->addSql('DROP TABLE thief_society');
        $this->addSql('DROP TABLE thief_society_merit');
        $this->addSql('ALTER TABLE body_thief DROP FOREIGN KEY FK_6C564116E6389D24');
        $this->addSql('DROP INDEX IDX_6C564116E6389D24 ON body_thief');
        $this->addSql('ALTER TABLE body_thief DROP possession_method, DROP society_id');
    }
}
