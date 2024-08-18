<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626220552 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item ADD book_id INT DEFAULT NULL, ADD homebrew_for_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E16A2B381 ON item (book_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E7B02D7EA ON item (homebrew_for_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E16A2B381');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E7B02D7EA');
        $this->addSql('DROP INDEX IDX_1F1B251E16A2B381 ON item');
        $this->addSql('DROP INDEX IDX_1F1B251E7B02D7EA ON item');
        $this->addSql('ALTER TABLE item DROP book_id, DROP homebrew_for_id, DROP page');
    }
}
