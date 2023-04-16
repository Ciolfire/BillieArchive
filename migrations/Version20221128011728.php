<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128011728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_note DROP FOREIGN KEY FK_D841EE8C5E315342');
        $this->addSql('DROP INDEX IDX_D841EE8C5E315342 ON character_note');
        $this->addSql('ALTER TABLE character_note CHANGE personnage_id character_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_note ADD CONSTRAINT FK_D841EE8C1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id)');
        $this->addSql('CREATE INDEX IDX_D841EE8C1136BE75 ON character_note (character_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_note DROP FOREIGN KEY FK_D841EE8C1136BE75');
        $this->addSql('DROP INDEX IDX_D841EE8C1136BE75 ON character_note');
        $this->addSql('ALTER TABLE character_note CHANGE character_id personnage_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_note ADD CONSTRAINT FK_D841EE8C5E315342 FOREIGN KEY (personnage_id) REFERENCES characters (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D841EE8C5E315342 ON character_note (personnage_id)');
    }
}
