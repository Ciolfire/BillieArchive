<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221128004412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_note ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_note ADD CONSTRAINT FK_D841EE8CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D841EE8CF675F31B ON character_note (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_note DROP FOREIGN KEY FK_D841EE8CF675F31B');
        $this->addSql('DROP INDEX IDX_D841EE8CF675F31B ON character_note');
        $this->addSql('ALTER TABLE character_note DROP author_id');
    }
}
