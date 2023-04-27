<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426210731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE society (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE society_character (society_id INT NOT NULL, character_id INT NOT NULL, INDEX IDX_B43117EBE6389D24 (society_id), INDEX IDX_B43117EB1136BE75 (character_id), PRIMARY KEY(society_id, character_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE society_character ADD CONSTRAINT FK_B43117EBE6389D24 FOREIGN KEY (society_id) REFERENCES society (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE society_character ADD CONSTRAINT FK_B43117EB1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE society_character DROP FOREIGN KEY FK_B43117EBE6389D24');
        $this->addSql('ALTER TABLE society_character DROP FOREIGN KEY FK_B43117EB1136BE75');
        $this->addSql('DROP TABLE society');
        $this->addSql('DROP TABLE society_character');
    }
}
