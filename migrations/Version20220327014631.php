<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220327014631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vampire_discipline (id INT AUTO_INCREMENT NOT NULL, discipline_id INT NOT NULL, character_id INT NOT NULL, level SMALLINT NOT NULL, INDEX IDX_57BB63AAA5522701 (discipline_id), INDEX IDX_57BB63AA1136BE75 (character_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vampire_discipline ADD CONSTRAINT FK_57BB63AAA5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id)');
        $this->addSql('ALTER TABLE vampire_discipline ADD CONSTRAINT FK_57BB63AA1136BE75 FOREIGN KEY (character_id) REFERENCES vampire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE vampire_discipline');
    }
}
