<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225180232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rite DROP FOREIGN KEY `FK_D1FF6E818B777BD8`');
        $this->addSql('DROP INDEX IDX_D1FF6E818B777BD8 ON rite');
        $this->addSql('ALTER TABLE rite DROP renown_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rite ADD renown_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rite ADD CONSTRAINT `FK_D1FF6E818B777BD8` FOREIGN KEY (renown_id) REFERENCES renown (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D1FF6E818B777BD8 ON rite (renown_id)');
    }
}
