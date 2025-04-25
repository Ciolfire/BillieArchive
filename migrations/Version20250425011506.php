<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425011506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_status_effect DROP FOREIGN KEY FK_3F9B84C61136BE75');
        $this->addSql('ALTER TABLE character_status_effect DROP FOREIGN KEY FK_3F9B84C67D7C387A');
        $this->addSql('DROP TABLE character_status_effect');
        $this->addSql('ALTER TABLE status_effect ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status_effect ADD CONSTRAINT FK_B2A39BF7E3C61F9 FOREIGN KEY (owner_id) REFERENCES characters (id)');
        $this->addSql('CREATE INDEX IDX_B2A39BF7E3C61F9 ON status_effect (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_status_effect (character_id INT NOT NULL, status_effect_id INT NOT NULL, INDEX IDX_3F9B84C61136BE75 (character_id), INDEX IDX_3F9B84C67D7C387A (status_effect_id), PRIMARY KEY(character_id, status_effect_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE character_status_effect ADD CONSTRAINT FK_3F9B84C61136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE character_status_effect ADD CONSTRAINT FK_3F9B84C67D7C387A FOREIGN KEY (status_effect_id) REFERENCES status_effect (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE status_effect DROP FOREIGN KEY FK_B2A39BF7E3C61F9');
        $this->addSql('DROP INDEX IDX_B2A39BF7E3C61F9 ON status_effect');
        $this->addSql('ALTER TABLE status_effect DROP owner_id');
    }
}
