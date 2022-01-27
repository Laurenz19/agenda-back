<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127153032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entretien (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, travail_id INT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, lieu VARCHAR(255) NOT NULL, INDEX IDX_2B58D6DA8D0EB82 (candidat_id), INDEX IDX_2B58D6DAEEFE7EA9 (travail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DAEEFE7EA9 FOREIGN KEY (travail_id) REFERENCES travail (id)');
        $this->addSql('ALTER TABLE candidat ADD contact VARCHAR(20) NOT NULL, ADD email VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE entretien');
        $this->addSql('ALTER TABLE candidat DROP contact, DROP email');
    }
}
