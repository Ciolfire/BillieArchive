<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250210023633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE arcanum ADD page SMALLINT DEFAULT NULL, ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE arcanum ADD CONSTRAINT FK_884DDF6216A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_884DDF6216A2B381 ON arcanum (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE arcanum DROP FOREIGN KEY FK_884DDF6216A2B381');
        $this->addSql('DROP INDEX IDX_884DDF6216A2B381 ON arcanum');
        $this->addSql('ALTER TABLE arcanum DROP page, DROP book_id');
    }
}
