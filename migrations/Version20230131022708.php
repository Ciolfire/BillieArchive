<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131022708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prerequisite ADD choice_group SMALLINT DEFAULT NULL, DROP is_mandatory');
        $this->addSql('ALTER TABLE prerequisite ADD CONSTRAINT FK_4594A23858D79B5E FOREIGN KEY (merit_id) REFERENCES merits (id)');
        $this->addSql('CREATE INDEX IDX_4594A23858D79B5E ON prerequisite (merit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prerequisite DROP FOREIGN KEY FK_4594A23858D79B5E');
        $this->addSql('DROP INDEX IDX_4594A23858D79B5E ON prerequisite');
        $this->addSql('ALTER TABLE prerequisite ADD is_mandatory TINYINT(1) NOT NULL, DROP choice_group');
    }
}
