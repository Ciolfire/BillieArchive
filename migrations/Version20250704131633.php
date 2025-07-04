<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704131633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE mage ADD legacy_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mage ADD CONSTRAINT FK_B6793962184998FC FOREIGN KEY (legacy_id) REFERENCES legacy (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6793962184998FC ON mage (legacy_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE mage DROP FOREIGN KEY FK_B6793962184998FC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_B6793962184998FC ON mage
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mage DROP legacy_id
        SQL);
    }
}
