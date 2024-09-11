<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240910172140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organization_covenant (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_covenant ADD CONSTRAINT FK_1BF34255BF396750 FOREIGN KEY (id) REFERENCES organization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipment RENAME item_equipment');
        $this->addSql('ALTER TABLE vehicle RENAME item_vehicle');
        $this->addSql('ALTER TABLE ranged_weapon RENAME item_weapon_ranged');
        $this->addSql('ALTER TABLE weapon RENAME item_weapon');
        $this->addSql('ALTER TABLE organization ADD book_id INT DEFAULT NULL, ADD homebrew_for_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL, ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_C1EE637C16A2B381 ON organization (book_id)');
        $this->addSql('CREATE INDEX IDX_C1EE637C7B02D7EA ON organization (homebrew_for_id)');
        $this->addSql('ALTER TABLE vampire ADD covenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vampire ADD CONSTRAINT FK_BC368BFCDA91032A FOREIGN KEY (covenant_id) REFERENCES organization_covenant (id)');
        $this->addSql('CREATE INDEX IDX_BC368BFCDA91032A ON vampire (covenant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vampire DROP FOREIGN KEY FK_BC368BFCDA91032A');
        $this->addSql('ALTER TABLE item_equipment RENAME equipment');
        $this->addSql('ALTER TABLE item_vehicle RENAME vehicle');
        $this->addSql('ALTER TABLE item_weapon RENAME weapon');
        $this->addSql('ALTER TABLE item_weapon_ranged RENAME weapon_ranged');
        $this->addSql('ALTER TABLE organization_covenant DROP FOREIGN KEY FK_1BF34255BF396750');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C16A2B381');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C7B02D7EA');
        $this->addSql('DROP INDEX IDX_C1EE637C16A2B381 ON organization');
        $this->addSql('DROP INDEX IDX_C1EE637C7B02D7EA ON organization');
        $this->addSql('ALTER TABLE organization DROP book_id, DROP homebrew_for_id, DROP page, DROP type');
        $this->addSql('DROP INDEX IDX_BC368BFCDA91032A ON vampire');
        $this->addSql('ALTER TABLE vampire DROP covenant_id');
    }
}
