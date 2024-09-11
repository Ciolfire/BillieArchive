<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240911012102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE covenant_discount_merits (covenant_id INT NOT NULL, merit_id INT NOT NULL, INDEX IDX_E9DAB405DA91032A (covenant_id), INDEX IDX_E9DAB40558D79B5E (merit_id), PRIMARY KEY(covenant_id, merit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE covenant_discount_merits ADD CONSTRAINT FK_E9DAB405DA91032A FOREIGN KEY (covenant_id) REFERENCES organization_covenant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE covenant_discount_merits ADD CONSTRAINT FK_E9DAB40558D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ghoul ADD covenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ghoul ADD CONSTRAINT FK_4665AF71DA91032A FOREIGN KEY (covenant_id) REFERENCES organization_covenant (id)');
        $this->addSql('CREATE INDEX IDX_4665AF71DA91032A ON ghoul (covenant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE covenant_discount_merits DROP FOREIGN KEY FK_E9DAB405DA91032A');
        $this->addSql('ALTER TABLE covenant_discount_merits DROP FOREIGN KEY FK_E9DAB40558D79B5E');
        $this->addSql('DROP TABLE covenant_discount_merits');
        $this->addSql('ALTER TABLE ghoul DROP FOREIGN KEY FK_4665AF71DA91032A');
        $this->addSql('DROP INDEX IDX_4665AF71DA91032A ON ghoul');
        $this->addSql('ALTER TABLE ghoul DROP covenant_id');
    }
}
