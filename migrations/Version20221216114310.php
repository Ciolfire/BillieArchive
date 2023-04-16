<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216114310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE note_links (note_source INT NOT NULL, note_target INT NOT NULL, INDEX IDX_B49202CF475828A7 (note_source), INDEX IDX_B49202CF5EBD7828 (note_target), PRIMARY KEY(note_source, note_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_links ADD CONSTRAINT FK_B49202CF475828A7 FOREIGN KEY (note_source) REFERENCES note (id)');
        $this->addSql('ALTER TABLE note_links ADD CONSTRAINT FK_B49202CF5EBD7828 FOREIGN KEY (note_target) REFERENCES note (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_links DROP FOREIGN KEY FK_B49202CF475828A7');
        $this->addSql('ALTER TABLE note_links DROP FOREIGN KEY FK_B49202CF5EBD7828');
        $this->addSql('DROP TABLE note_links');
    }
}
