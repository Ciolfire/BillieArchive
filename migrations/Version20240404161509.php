<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404161509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merits ADD type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE merits ADD CONSTRAINT FK_501541D6C54C8C93 FOREIGN KEY (type_id) REFERENCES content_type (id)');
        $this->addSql('CREATE INDEX IDX_501541D6C54C8C93 ON merits (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merits DROP FOREIGN KEY FK_501541D6C54C8C93');
        $this->addSql('DROP INDEX IDX_501541D6C54C8C93 ON merits');
        $this->addSql('ALTER TABLE merits DROP type_id');
    }
}
