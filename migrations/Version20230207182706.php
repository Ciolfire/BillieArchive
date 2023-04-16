<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207182706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters CHANGE `name` first_name VARCHAR(30) NOT NULL, ADD last_name VARCHAR(30) NOT NULL, ADD nickname VARCHAR(60) NOT NULL, ADD look_age SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE vampire CHANGE apparent_age death_age SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characters DROP last_name, CHANGE first_name `name`, DROP nickname, DROP look_age');
        $this->addSql('ALTER TABLE vampire CHANGE death_age apparent_age SMALLINT NOT NULL');
    }
}
