<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240622020732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_translation RENAME INDEX attributes_translation_idx TO attribute_translation_idx');
        $this->addSql('ALTER TABLE characters ADD avatar VARCHAR(255) DEFAULT ""');
        $this->addSql('ALTER TABLE derangement_translation RENAME INDEX derangements_translation_idx TO derangement_translation_idx');
        $this->addSql('ALTER TABLE devotion_translation RENAME INDEX devotions_translation_idx TO devotion_translation_idx');
        $this->addSql('ALTER TABLE discipline_translation RENAME INDEX disciplines_translation_idx TO discipline_translation_idx');
        $this->addSql('ALTER TABLE merit_translation RENAME INDEX merits_translation_idx TO merit_translation_idx');
        $this->addSql('ALTER TABLE roll_translation RENAME INDEX rolls_translation_idx TO roll_translation_idx');
        $this->addSql('ALTER TABLE skill_translation RENAME INDEX skills_translation_idx TO skill_translation_idx');
        $this->addSql('ALTER TABLE vice_translation RENAME INDEX vices_translation_idx TO vice_translation_idx');
        $this->addSql('ALTER TABLE virtue_translation RENAME INDEX virtues_translation_idx TO virtue_translation_idx');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_translation RENAME INDEX attribute_translation_idx TO attributes_translation_idx');
        $this->addSql('ALTER TABLE characters DROP avatar');
        $this->addSql('ALTER TABLE derangement_translation RENAME INDEX derangement_translation_idx TO derangements_translation_idx');
        $this->addSql('ALTER TABLE devotion_translation RENAME INDEX devotion_translation_idx TO devotions_translation_idx');
        $this->addSql('ALTER TABLE discipline_translation RENAME INDEX discipline_translation_idx TO disciplines_translation_idx');
        $this->addSql('ALTER TABLE merit_translation RENAME INDEX merit_translation_idx TO merits_translation_idx');
        $this->addSql('ALTER TABLE roll_translation RENAME INDEX roll_translation_idx TO rolls_translation_idx');
        $this->addSql('ALTER TABLE skill_translation RENAME INDEX skill_translation_idx TO skills_translation_idx');
        $this->addSql('ALTER TABLE vice_translation RENAME INDEX vice_translation_idx TO vices_translation_idx');
        $this->addSql('ALTER TABLE virtue_translation RENAME INDEX virtue_translation_idx TO virtues_translation_idx');
    }
}
