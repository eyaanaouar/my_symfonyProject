<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251230200722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE feedbacks (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, commentaire LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, id_candidature INT NOT NULL, UNIQUE INDEX UNIQ_7E6C3F89FB291E4F (id_candidature), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE feedbacks ADD CONSTRAINT FK_7E6C3F89FB291E4F FOREIGN KEY (id_candidature) REFERENCES candidatures (id_candidature)');
        $this->addSql('ALTER TABLE entreprises CHANGE telephone telephone VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedbacks DROP FOREIGN KEY FK_7E6C3F89FB291E4F');
        $this->addSql('DROP TABLE feedbacks');
        $this->addSql('ALTER TABLE entreprises CHANGE telephone telephone VARCHAR(20) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
    }
}
