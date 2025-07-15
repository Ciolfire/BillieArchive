<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250715111316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage CHANGE has_own_legacy has_own_legacy TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE organization ADD is_private TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage CHANGE has_own_legacy has_own_legacy TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE organization DROP is_private');
    }
}
