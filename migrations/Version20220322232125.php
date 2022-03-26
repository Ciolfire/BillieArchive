<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220322232125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE clan_attribute (clan_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_50770B59BEAF84C8 (clan_id), INDEX IDX_50770B59B6E62EFA (attribute_id), PRIMARY KEY(clan_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clan_discipline (clan_id INT NOT NULL, discipline_id INT NOT NULL, INDEX IDX_4DCC211FBEAF84C8 (clan_id), INDEX IDX_4DCC211FA5522701 (discipline_id), PRIMARY KEY(clan_id, discipline_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE clan_attribute ADD CONSTRAINT FK_50770B59BEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clan_attribute ADD CONSTRAINT FK_50770B59B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clan_discipline ADD CONSTRAINT FK_4DCC211FBEAF84C8 FOREIGN KEY (clan_id) REFERENCES clan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE clan_discipline ADD CONSTRAINT FK_4DCC211FA5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE clan_attribute');
        $this->addSql('DROP TABLE clan_discipline');
    }
}
