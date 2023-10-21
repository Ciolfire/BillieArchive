<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021031603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ghoul DROP FOREIGN KEY FK_4665AF71798BC459');
        $this->addSql('DROP INDEX IDX_4665AF71798BC459 ON ghoul');
        $this->addSql('ALTER TABLE ghoul CHANGE regent_clan_id clan_id INT NOT NULL');
        $this->addSql('ALTER TABLE ghoul ADD CONSTRAINT FK_4665AF71BEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id)');
        $this->addSql('CREATE INDEX IDX_4665AF71BEAF84C8 ON ghoul (clan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ghoul DROP FOREIGN KEY FK_4665AF71BEAF84C8');
        $this->addSql('DROP INDEX IDX_4665AF71BEAF84C8 ON ghoul');
        $this->addSql('ALTER TABLE ghoul CHANGE clan_id regent_clan_id INT NOT NULL');
        $this->addSql('ALTER TABLE ghoul ADD CONSTRAINT FK_4665AF71798BC459 FOREIGN KEY (regent_clan_id) REFERENCES clan (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_4665AF71798BC459 ON ghoul (regent_clan_id)');
    }
}
