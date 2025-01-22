<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250122224508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE society ADD organization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE society ADD CONSTRAINT FK_D6461F232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id)');
        $this->addSql('CREATE INDEX IDX_D6461F232C8A3DE ON society (organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE society DROP FOREIGN KEY FK_D6461F232C8A3DE');
        $this->addSql('DROP INDEX IDX_D6461F232C8A3DE ON society');
        $this->addSql('ALTER TABLE society DROP organization_id');
    }
}
