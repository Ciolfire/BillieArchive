<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303151905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE body_thief CHANGE possession_method talent_type INT NOT NULL');
        $this->addSql('ALTER TABLE body_thief_society ADD advantage LONGTEXT NOT NULL, ADD weakness LONGTEXT NOT NULL, DROP strengths, DROP weaknesses, DROP quote');
        $this->addSql('ALTER TABLE body_thief_society RENAME INDEX idx_74478a27283cd845 TO IDX_A791B748283CD845');
        $this->addSql('ALTER TABLE body_thief_society RENAME INDEX idx_74478a2716a2b381 TO IDX_A791B74816A2B381');
        $this->addSql('ALTER TABLE body_thief_society RENAME INDEX idx_74478a277b02d7ea TO IDX_A791B7487B02D7EA');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE body_thief CHANGE talent_type possession_method INT NOT NULL');
        $this->addSql('ALTER TABLE body_thief_society ADD strengths LONGTEXT NOT NULL, ADD weaknesses LONGTEXT NOT NULL, ADD quote VARCHAR(255) NOT NULL, DROP advantage, DROP weakness');
        $this->addSql('ALTER TABLE body_thief_society RENAME INDEX idx_a791b748283cd845 TO IDX_74478A27283CD845');
        $this->addSql('ALTER TABLE body_thief_society RENAME INDEX idx_a791b74816a2b381 TO IDX_74478A2716A2B381');
        $this->addSql('ALTER TABLE body_thief_society RENAME INDEX idx_a791b7487b02d7ea TO IDX_74478A277B02D7EA');
    }
}
