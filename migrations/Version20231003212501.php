<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003212501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_derangement (id INT AUTO_INCREMENT NOT NULL, character_id INT NOT NULL, details VARCHAR(50) DEFAULT NULL, morality_link SMALLINT DEFAULT NULL, INDEX IDX_CED65FC61136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_derangement ADD CONSTRAINT FK_CED65FC61136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_derangement DROP FOREIGN KEY FK_CED65FC61136BE75');
        $this->addSql('DROP TABLE character_derangement');
    }
}
