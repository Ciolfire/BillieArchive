<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903222246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE path ADD book_id INT DEFAULT NULL, ADD homebrew_for_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0F16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE path ADD CONSTRAINT FK_B548B0F7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_B548B0F16A2B381 ON path (book_id)');
        $this->addSql('CREATE INDEX IDX_B548B0F7B02D7EA ON path (homebrew_for_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0F16A2B381');
        $this->addSql('ALTER TABLE path DROP FOREIGN KEY FK_B548B0F7B02D7EA');
        $this->addSql('DROP INDEX IDX_B548B0F16A2B381 ON path');
        $this->addSql('DROP INDEX IDX_B548B0F7B02D7EA ON path');
        $this->addSql('ALTER TABLE path DROP book_id, DROP homebrew_for_id, DROP page');
    }
}
