<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406154453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_access (id INT AUTO_INCREMENT NOT NULL, target_id INT NOT NULL, accessor_id INT NOT NULL, rights JSON NOT NULL, INDEX IDX_A52BE8C7158E0B66 (target_id), INDEX IDX_A52BE8C72E8A75A9 (accessor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE character_access ADD CONSTRAINT FK_A52BE8C7158E0B66 FOREIGN KEY (target_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE character_access ADD CONSTRAINT FK_A52BE8C72E8A75A9 FOREIGN KEY (accessor_id) REFERENCES characters (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_access DROP FOREIGN KEY FK_A52BE8C7158E0B66');
        $this->addSql('ALTER TABLE character_access DROP FOREIGN KEY FK_A52BE8C72E8A75A9');
        $this->addSql('DROP TABLE character_access');
    }
}
