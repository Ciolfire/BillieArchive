<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407144856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_status_effect (character_id INT NOT NULL, status_effect_id INT NOT NULL, INDEX IDX_3F9B84C61136BE75 (character_id), INDEX IDX_3F9B84C67D7C387A (status_effect_id), PRIMARY KEY(character_id, status_effect_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE status_effect (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, value INT NOT NULL, description LONGTEXT DEFAULT NULL, is_level_dependant TINYINT(1) NOT NULL, discipline_power_id INT DEFAULT NULL, INDEX IDX_B2A39BFC9F8163B (discipline_power_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE character_status_effect ADD CONSTRAINT FK_3F9B84C61136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_status_effect ADD CONSTRAINT FK_3F9B84C67D7C387A FOREIGN KEY (status_effect_id) REFERENCES status_effect (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE status_effect ADD CONSTRAINT FK_B2A39BFC9F8163B FOREIGN KEY (discipline_power_id) REFERENCES discipline_power (id)');
        $this->addSql('ALTER TABLE discipline_power ADD can_toggle TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_status_effect DROP FOREIGN KEY FK_3F9B84C61136BE75');
        $this->addSql('ALTER TABLE character_status_effect DROP FOREIGN KEY FK_3F9B84C67D7C387A');
        $this->addSql('ALTER TABLE status_effect DROP FOREIGN KEY FK_B2A39BFC9F8163B');
        $this->addSql('DROP TABLE character_status_effect');
        $this->addSql('DROP TABLE status_effect');
        $this->addSql('ALTER TABLE discipline_power DROP can_toggle');
    }
}
