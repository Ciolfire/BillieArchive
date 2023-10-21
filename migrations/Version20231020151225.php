<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231020151225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ghoul_family ADD clan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ghoul_family ADD CONSTRAINT FK_112D41A0BEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id)');
        $this->addSql('CREATE INDEX IDX_112D41A0BEAF84C8 ON ghoul_family (clan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ghoul_family DROP FOREIGN KEY FK_112D41A0BEAF84C8');
        $this->addSql('DROP INDEX IDX_112D41A0BEAF84C8 ON ghoul_family');
        $this->addSql('ALTER TABLE ghoul_family DROP clan_id');
    }
}
