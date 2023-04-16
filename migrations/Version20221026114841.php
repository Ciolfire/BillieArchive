<?php declare(strict_types=1);

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026114841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mage (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE werewolf (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mage ADD CONSTRAINT FK_B6793962BF396750 FOREIGN KEY (id) REFERENCES characters (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE werewolf ADD CONSTRAINT FK_D5FB84ABBF396750 FOREIGN KEY (id) REFERENCES characters (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mage DROP FOREIGN KEY FK_B6793962BF396750');
        $this->addSql('ALTER TABLE werewolf DROP FOREIGN KEY FK_D5FB84ABBF396750');
        $this->addSql('DROP TABLE mage');
        $this->addSql('DROP TABLE werewolf');
    }
}
