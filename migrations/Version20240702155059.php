<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702155059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roll ADD homebrew_for_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE roll ADD CONSTRAINT FK_2EB532CE7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_2EB532CE7B02D7EA ON roll (homebrew_for_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE roll DROP FOREIGN KEY FK_2EB532CE7B02D7EA');
        $this->addSql('DROP INDEX IDX_2EB532CE7B02D7EA ON roll');
        $this->addSql('ALTER TABLE roll DROP homebrew_for_id');
    }
}
