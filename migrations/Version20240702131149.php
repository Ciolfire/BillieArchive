<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702131149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rule ADD homebrew_for_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rule ADD CONSTRAINT FK_46D8ACCC7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_46D8ACCC7B02D7EA ON rule (homebrew_for_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rule DROP FOREIGN KEY FK_46D8ACCC7B02D7EA');
        $this->addSql('DROP INDEX IDX_46D8ACCC7B02D7EA ON rule');
        $this->addSql('ALTER TABLE rule DROP homebrew_for_id');
    }
}
