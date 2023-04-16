<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425134323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline ADD homebrew_for_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE discipline ADD CONSTRAINT FK_75BEEE3F7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_75BEEE3F7B02D7EA ON discipline (homebrew_for_id)');
        $this->addSql('ALTER TABLE merits ADD homebrew_for_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE merits ADD CONSTRAINT FK_501541D67B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('CREATE INDEX IDX_501541D67B02D7EA ON merits (homebrew_for_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE discipline DROP FOREIGN KEY FK_75BEEE3F7B02D7EA');
        $this->addSql('DROP INDEX IDX_75BEEE3F7B02D7EA ON discipline');
        $this->addSql('ALTER TABLE discipline DROP homebrew_for_id');
        $this->addSql('ALTER TABLE merits DROP FOREIGN KEY FK_501541D67B02D7EA');
        $this->addSql('DROP INDEX IDX_501541D67B02D7EA ON merits');
        $this->addSql('ALTER TABLE merits DROP homebrew_for_id');
    }
}
