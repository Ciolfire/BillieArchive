<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511151610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_info ADD creator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character_info ADD CONSTRAINT FK_DC7525CF61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DC7525CF61220EA6 ON character_info (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_info DROP FOREIGN KEY FK_DC7525CF61220EA6');
        $this->addSql('DROP INDEX IDX_DC7525CF61220EA6 ON character_info');
        $this->addSql('ALTER TABLE character_info DROP creator_id');
    }
}
