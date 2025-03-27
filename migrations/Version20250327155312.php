<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327155312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE numen (id INT AUTO_INCREMENT NOT NULL, short VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, can_reach TINYINT(1) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE siddhi (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, page SMALLINT DEFAULT NULL, homebrew_for_id INT DEFAULT NULL, book_id INT DEFAULT NULL, INDEX IDX_CA86F00B7B02D7EA (homebrew_for_id), INDEX IDX_CA86F00B16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE siddhi_power (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, short VARCHAR(255) NOT NULL, cost VARCHAR(255) NOT NULL, siddhi_id INT NOT NULL, roll_id INT DEFAULT NULL, INDEX IDX_574BB7D37A73EDB3 (siddhi_id), UNIQUE INDEX UNIQ_574BB7D3AB0B6D26 (roll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE siddhi ADD CONSTRAINT FK_CA86F00B7B02D7EA FOREIGN KEY (homebrew_for_id) REFERENCES chronicle (id)');
        $this->addSql('ALTER TABLE siddhi ADD CONSTRAINT FK_CA86F00B16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE siddhi_power ADD CONSTRAINT FK_574BB7D37A73EDB3 FOREIGN KEY (siddhi_id) REFERENCES siddhi (id)');
        $this->addSql('ALTER TABLE siddhi_power ADD CONSTRAINT FK_574BB7D3AB0B6D26 FOREIGN KEY (roll_id) REFERENCES roll (id)');
        $this->addSql('ALTER TABLE purified ADD chi SMALLINT NOT NULL, ADD essence SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE siddhi DROP FOREIGN KEY FK_CA86F00B7B02D7EA');
        $this->addSql('ALTER TABLE siddhi DROP FOREIGN KEY FK_CA86F00B16A2B381');
        $this->addSql('ALTER TABLE siddhi_power DROP FOREIGN KEY FK_574BB7D37A73EDB3');
        $this->addSql('ALTER TABLE siddhi_power DROP FOREIGN KEY FK_574BB7D3AB0B6D26');
        $this->addSql('DROP TABLE numen');
        $this->addSql('DROP TABLE siddhi');
        $this->addSql('DROP TABLE siddhi_power');
        $this->addSql('ALTER TABLE purified DROP chi, DROP essence');
    }
}
