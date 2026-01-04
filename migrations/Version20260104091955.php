<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260104091955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprises CHANGE telephone telephone VARCHAR(20) DEFAULT NULL, CHANGE est_valide est_valide INT NOT NULL');
        $this->addSql('ALTER TABLE etudiants CHANGE est_valide est_valide INT NOT NULL');
        $this->addSql('ALTER TABLE offres_stage CHANGE titre titre VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprises CHANGE telephone telephone VARCHAR(20) DEFAULT \'NULL\', CHANGE est_valide est_valide TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE etudiants CHANGE est_valide est_valide TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE offres_stage CHANGE titre titre VARCHAR(100) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }
}
