<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214003301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1499E6F5DF');
        $this->addSql('DROP INDEX IDX_CFBDFA1499E6F5DF ON note');
        $this->addSql('ALTER TABLE note CHANGE player_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A76ED395 ON note (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('DROP INDEX IDX_CFBDFA14A76ED395 ON note');
        $this->addSql('ALTER TABLE note CHANGE user_id player_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1499E6F5DF FOREIGN KEY (player_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CFBDFA1499E6F5DF ON note (player_id)');
    }
}
