<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212030536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_spell ADD skill_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mage_spell ADD CONSTRAINT FK_64EE38035585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('CREATE INDEX IDX_64EE38035585C142 ON mage_spell (skill_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_spell DROP FOREIGN KEY FK_64EE38035585C142');
        $this->addSql('DROP INDEX IDX_64EE38035585C142 ON mage_spell');
        $this->addSql('ALTER TABLE mage_spell DROP skill_id');
    }
}
