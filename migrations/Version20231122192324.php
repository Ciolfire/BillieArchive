<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122192324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_info (id INT AUTO_INCREMENT NOT NULL, character_id INT NOT NULL, data LONGTEXT NOT NULL, INDEX IDX_DC7525CF1136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE character_info_character (character_info_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_17E7752C342E41C5 (character_info_id), INDEX IDX_17E7752C1136BE75 (character_id), PRIMARY KEY(character_info_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_info ADD CONSTRAINT FK_DC7525CF1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE character_info_character ADD CONSTRAINT FK_17E7752C342E41C5 FOREIGN KEY (character_info_id) REFERENCES character_info (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_info_character ADD CONSTRAINT FK_17E7752C1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_info DROP FOREIGN KEY FK_DC7525CF1136BE75');
        $this->addSql('ALTER TABLE character_info_character DROP FOREIGN KEY FK_17E7752C342E41C5');
        $this->addSql('ALTER TABLE character_info_character DROP FOREIGN KEY FK_17E7752C1136BE75');
        $this->addSql('DROP TABLE character_info');
        $this->addSql('DROP TABLE character_info_character');
    }
}
