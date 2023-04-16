<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230214202204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vampire_discipline_power (vampire_id INT NOT NULL, discipline_power_id INT NOT NULL, INDEX IDX_E4272387CD529B21 (vampire_id), INDEX IDX_E4272387C9F8163B (discipline_power_id), PRIMARY KEY(vampire_id, discipline_power_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vampire_discipline_power ADD CONSTRAINT FK_E4272387CD529B21 FOREIGN KEY (vampire_id) REFERENCES vampire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vampire_discipline_power ADD CONSTRAINT FK_E4272387C9F8163B FOREIGN KEY (discipline_power_id) REFERENCES discipline_power (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discipline_power ADD homebrew_for_id INT DEFAULT NULL, ADD book_id INT DEFAULT NULL, ADD page SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E87B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE discipline_power ADD CONSTRAINT FK_ECEDB2E816A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_ECEDB2E87B02D7EA ON discipline_power (homebrew_for_id)');
        $this->addSql('CREATE INDEX IDX_ECEDB2E816A2B381 ON discipline_power (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vampire_discipline_power DROP FOREIGN KEY FK_E4272387CD529B21');
        $this->addSql('ALTER TABLE vampire_discipline_power DROP FOREIGN KEY FK_E4272387C9F8163B');
        $this->addSql('DROP TABLE vampire_discipline_power');
        $this->addSql('ALTER TABLE discipline_power DROP FOREIGN KEY FK_ECEDB2E87B02D7EA');
        $this->addSql('ALTER TABLE discipline_power DROP FOREIGN KEY FK_ECEDB2E816A2B381');
        $this->addSql('DROP INDEX IDX_ECEDB2E87B02D7EA ON discipline_power');
        $this->addSql('DROP INDEX IDX_ECEDB2E816A2B381 ON discipline_power');
        $this->addSql('ALTER TABLE discipline_power DROP homebrew_for_id, DROP book_id, DROP page');
    }
}
