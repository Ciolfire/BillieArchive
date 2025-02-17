<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217182052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE magical_practice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE characters CHANGE gender gender INT NOT NULL');
        $this->addSql('ALTER TABLE mage_spell ADD practice_id INT NOT NULL');
        $this->addSql('ALTER TABLE mage_spell ADD CONSTRAINT FK_64EE3803ED33821 FOREIGN KEY (practice_id) REFERENCES magical_practice (id)');
        $this->addSql('CREATE INDEX IDX_64EE3803ED33821 ON mage_spell (practice_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE magical_practice');
        $this->addSql('ALTER TABLE characters CHANGE gender gender VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE mage_spell DROP FOREIGN KEY FK_64EE3803ED33821');
        $this->addSql('DROP INDEX IDX_64EE3803ED33821 ON mage_spell');
        $this->addSql('ALTER TABLE mage_spell DROP practice_id');
    }
}
