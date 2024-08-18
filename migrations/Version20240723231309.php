<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723231309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD owner_id INT DEFAULT NULL, ADD container_id INT DEFAULT NULL, ADD img VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES characters (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EBC21F742 FOREIGN KEY (container_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E7E3C61F9 ON item (owner_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EBC21F742 ON item (container_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E7E3C61F9');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EBC21F742');
        $this->addSql('DROP INDEX IDX_1F1B251E7E3C61F9 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251EBC21F742 ON item');
        $this->addSql('ALTER TABLE item DROP owner_id, DROP container_id, DROP img');
    }
}
