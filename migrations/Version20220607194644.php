<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607194644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan ADD homebrew_for_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clan ADD CONSTRAINT FK_9FF6A30C7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_9FF6A30C7B02D7EA ON clan (homebrew_for_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clan DROP FOREIGN KEY FK_9FF6A30C7B02D7EA');
        $this->addSql('DROP INDEX IDX_9FF6A30C7B02D7EA ON clan');
        $this->addSql('ALTER TABLE clan DROP homebrew_for_id');
    }
}
