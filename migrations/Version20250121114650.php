<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250121114650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merits ADD roll_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE merits ADD CONSTRAINT FK_501541D6AB0B6D26 FOREIGN KEY (roll_id) REFERENCES roll (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_501541D6AB0B6D26 ON merits (roll_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merits DROP FOREIGN KEY FK_501541D6AB0B6D26');
        $this->addSql('DROP INDEX UNIQ_501541D6AB0B6D26 ON merits');
        $this->addSql('ALTER TABLE merits DROP roll_id');
    }
}
