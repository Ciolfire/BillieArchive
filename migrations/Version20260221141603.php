<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260221141603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merit RENAME INDEX idx_501541d6c54c8c93 TO IDX_1F5A9A25C54C8C93');
        $this->addSql('ALTER TABLE merit RENAME INDEX idx_501541d67b02d7ea TO IDX_1F5A9A257B02D7EA');
        $this->addSql('ALTER TABLE merit RENAME INDEX idx_501541d616a2b381 TO IDX_1F5A9A2516A2B381');
        $this->addSql('ALTER TABLE merit RENAME INDEX uniq_501541d6ab0b6d26 TO UNIQ_1F5A9A25AB0B6D26');
        $this->addSql('ALTER TABLE user ADD chronicles_order JSON NOT NULL');
        $this->addSql('ALTER TABLE user ADD stories_order JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE merit RENAME INDEX uniq_1f5a9a25ab0b6d26 TO UNIQ_501541D6AB0B6D26');
        $this->addSql('ALTER TABLE merit RENAME INDEX idx_1f5a9a2516a2b381 TO IDX_501541D616A2B381');
        $this->addSql('ALTER TABLE merit RENAME INDEX idx_1f5a9a257b02d7ea TO IDX_501541D67B02D7EA');
        $this->addSql('ALTER TABLE merit RENAME INDEX idx_1f5a9a25c54c8c93 TO IDX_501541D6C54C8C93');
        $this->addSql('ALTER TABLE user DROP chronicles_order');
        $this->addSql('ALTER TABLE user DROP stories_order');
    }
}
