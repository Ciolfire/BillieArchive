<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228013627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters ADD willpower SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE specialty DROP FOREIGN KEY FK_E066A6EC1136BE75');
        $this->addSql('DROP INDEX IDX_E066A6EC1136BE75 ON specialty');
        $this->addSql('ALTER TABLE specialty DROP character_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP willpower');
        $this->addSql('ALTER TABLE specialty ADD character_id INT NOT NULL');
        $this->addSql('ALTER TABLE specialty ADD CONSTRAINT FK_E066A6EC1136BE75 FOREIGN KEY (character_id) REFERENCES characters (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E066A6EC1136BE75 ON specialty (character_id)');
    }
}
