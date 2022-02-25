<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220207230708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialty ADD skill_id INT NOT NULL');
        $this->addSql('ALTER TABLE specialty ADD CONSTRAINT FK_E066A6EC5585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('CREATE INDEX IDX_E066A6EC5585C142 ON specialty (skill_id)');
        $this->addSql('ALTER TABLE specialty RENAME INDEX idx_f3d7a08e5e315342 TO IDX_E066A6EC5E315342');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specialty DROP FOREIGN KEY FK_E066A6EC5585C142');
        $this->addSql('DROP INDEX IDX_E066A6EC5585C142 ON specialty');
        $this->addSql('ALTER TABLE specialty DROP skill_id');
        $this->addSql('ALTER TABLE specialty RENAME INDEX idx_e066a6ec5e315342 TO IDX_F3D7A08E5E315342');
    }
}
