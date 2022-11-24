<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124135759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline CHANGE short short VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline_power CHANGE details details LONGTEXT DEFAULT NULL, CHANGE short short VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline CHANGE short short LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline_power CHANGE short short LONGTEXT NOT NULL, CHANGE details details LONGTEXT NOT NULL');
    }
}
