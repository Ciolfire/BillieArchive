<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209204532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devotion ADD bloodline_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE devotion ADD CONSTRAINT FK_C520E079C2F225F2 FOREIGN KEY (bloodline_id) REFERENCES clan (id)');
        $this->addSql('CREATE INDEX IDX_C520E079C2F225F2 ON devotion (bloodline_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devotion DROP FOREIGN KEY FK_C520E079C2F225F2');
        $this->addSql('DROP INDEX IDX_C520E079C2F225F2 ON devotion');
        $this->addSql('ALTER TABLE devotion DROP bloodline_id');
    }
}
