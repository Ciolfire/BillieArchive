<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212031422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_spell ADD arcanum_id INT NOT NULL');
        $this->addSql('ALTER TABLE mage_spell ADD CONSTRAINT FK_64EE380323DF3E0B FOREIGN KEY (arcanum_id) REFERENCES arcanum (id)');
        $this->addSql('CREATE INDEX IDX_64EE380323DF3E0B ON mage_spell (arcanum_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_spell DROP FOREIGN KEY FK_64EE380323DF3E0B');
        $this->addSql('DROP INDEX IDX_64EE380323DF3E0B ON mage_spell');
        $this->addSql('ALTER TABLE mage_spell DROP arcanum_id');
    }
}
