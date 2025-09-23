<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923211923 extends AbstractMigration
{
  public function getDescription(): string
  {
    return '';
  }

  public function up(Schema $schema): void
  {
    // this up() migration is auto-generated, please modify it to your needs
    // $this->addSql('ALTER TABLE chronicle ADD is_ancient TINYINT(1) DEFAULT 0');
    $this->addSql('ALTER TABLE characters ADD is_ancient TINYINT(1) DEFAULT 0');
    $this->addSql('ALTER TABLE skill ADD is_ancient  TINYINT(1) DEFAULT 0');
    $this->addSql('ALTER TABLE merits ADD is_ancient TINYINT(1) DEFAULT 0');
    $this->addSql('ALTER TABLE devotion ADD is_ancient TINYINT(1) DEFAULT 0');
    $this->addSql('ALTER TABLE organization ADD is_ancient TINYINT(1) DEFAULT 0');
    $this->addSql('ALTER TABLE clan ADD is_ancient TINYINT(1) DEFAULT NULL');
    $this->addSql('ALTER TABLE discipline ADD is_ancient TINYINT(1) DEFAULT NULL');
    $this->addSql('ALTER TABLE discipline_power ADD is_ancient TINYINT(1) DEFAULT NULL');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->addSql('ALTER TABLE chronicle DROP is_ancient');
    $this->addSql('ALTER TABLE characters DROP is_ancient');
    $this->addSql('ALTER TABLE skill DROP is_ancient');
    $this->addSql('ALTER TABLE merits DROP is_ancient');
    $this->addSql('ALTER TABLE devotion DROP is_ancient');
    $this->addSql('ALTER TABLE organization DROP is_ancient');
    $this->addSql('ALTER TABLE clan DROP is_ancient');
    $this->addSql('ALTER TABLE discipline DROP is_ancient');
    $this->addSql('ALTER TABLE discipline_power DROP is_ancient');
  }
}
