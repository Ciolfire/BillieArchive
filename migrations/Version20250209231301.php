<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209231301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_arcanum DROP FOREIGN KEY FK_E09AD8E5A4F9EEA');
        $this->addSql('DROP INDEX IDX_E09AD8E5A4F9EEA ON mage_arcanum');
        $this->addSql('ALTER TABLE mage_arcanum CHANGE mage_id character_id INT NOT NULL');
        $this->addSql('ALTER TABLE mage_arcanum ADD CONSTRAINT FK_E09AD8E51136BE75 FOREIGN KEY (character_id) REFERENCES mage (id)');
        $this->addSql('CREATE INDEX IDX_E09AD8E51136BE75 ON mage_arcanum (character_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage_arcanum DROP FOREIGN KEY FK_E09AD8E51136BE75');
        $this->addSql('DROP INDEX IDX_E09AD8E51136BE75 ON mage_arcanum');
        $this->addSql('ALTER TABLE mage_arcanum CHANGE character_id mage_id INT NOT NULL');
        $this->addSql('ALTER TABLE mage_arcanum ADD CONSTRAINT FK_E09AD8E5A4F9EEA FOREIGN KEY (mage_id) REFERENCES mage (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E09AD8E5A4F9EEA ON mage_arcanum (mage_id)');
    }
}
