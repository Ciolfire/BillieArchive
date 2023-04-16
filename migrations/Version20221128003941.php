<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128003941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_note (id INT AUTO_INCREMENT NOT NULL, personnage_id INT NOT NULL, content LONGTEXT NOT NULL, assigned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D841EE8C5E315342 (personnage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_note ADD CONSTRAINT FK_D841EE8C5E315342 FOREIGN KEY (personnage_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE characters DROP notes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_note DROP FOREIGN KEY FK_D841EE8C5E315342');
        $this->addSql('DROP TABLE character_note');
        $this->addSql('ALTER TABLE characters ADD notes LONGTEXT NOT NULL');
    }
}
