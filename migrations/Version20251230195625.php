<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251230195625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id_admin INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, mdp VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY (id_admin)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE avis_entreprises (id_avis INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, commentaire LONGTEXT DEFAULT NULL, date_avis DATETIME NOT NULL, id_etudiant INT NOT NULL, id_entreprise INT NOT NULL, INDEX IDX_958FA0621A5CE76 (id_etudiant), INDEX IDX_958FA06A8937AB7 (id_entreprise), PRIMARY KEY (id_avis)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE candidatures (id_candidature INT AUTO_INCREMENT NOT NULL, date_candidature DATETIME NOT NULL, statut VARCHAR(20) NOT NULL, notes LONGTEXT DEFAULT NULL, id_etudiant INT NOT NULL, id_offre INT NOT NULL, INDEX IDX_DE57CF6621A5CE76 (id_etudiant), INDEX IDX_DE57CF664103C75F (id_offre), PRIMARY KEY (id_candidature)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE documents (id_document INT AUTO_INCREMENT NOT NULL, type_document VARCHAR(50) NOT NULL, chemin_fichier VARCHAR(255) NOT NULL, date_upload DATETIME NOT NULL, id_candidature INT NOT NULL, INDEX IDX_A2B07288FB291E4F (id_candidature), PRIMARY KEY (id_document)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE entreprises (id_entreprise INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, adresse LONGTEXT DEFAULT NULL, email VARCHAR(100) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, mdp VARCHAR(255) NOT NULL, date_inscription DATETIME NOT NULL, est_valide TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_56B1B7A9E7927C74 (email), PRIMARY KEY (id_entreprise)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE etudiants (id_etudiant INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(100) NOT NULL, mdp VARCHAR(255) NOT NULL, date_inscription DATETIME NOT NULL, est_valide TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_227C02EBE7927C74 (email), PRIMARY KEY (id_etudiant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE feedbacks (id INT AUTO_INCREMENT NOT NULL, note INT NOT NULL, commentaire LONGTEXT NOT NULL, date_creation DATETIME NOT NULL, id_candidature INT NOT NULL, UNIQUE INDEX UNIQ_7E6C3F89FB291E4F (id_candidature), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE offres_stage (id_offre INT AUTO_INCREMENT NOT NULL, titre VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, date_publication DATETIME NOT NULL, lettre_motivation_requise TINYINT(1) NOT NULL, est_valide TINYINT(1) NOT NULL, id_entreprise INT NOT NULL, INDEX IDX_27D34C36A8937AB7 (id_entreprise), PRIMARY KEY (id_offre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE avis_entreprises ADD CONSTRAINT FK_958FA0621A5CE76 FOREIGN KEY (id_etudiant) REFERENCES etudiants (id_etudiant)');
        $this->addSql('ALTER TABLE avis_entreprises ADD CONSTRAINT FK_958FA06A8937AB7 FOREIGN KEY (id_entreprise) REFERENCES entreprises (id_entreprise)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF6621A5CE76 FOREIGN KEY (id_etudiant) REFERENCES etudiants (id_etudiant)');
        $this->addSql('ALTER TABLE candidatures ADD CONSTRAINT FK_DE57CF664103C75F FOREIGN KEY (id_offre) REFERENCES offres_stage (id_offre)');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288FB291E4F FOREIGN KEY (id_candidature) REFERENCES candidatures (id_candidature)');
        $this->addSql('ALTER TABLE feedbacks ADD CONSTRAINT FK_7E6C3F89FB291E4F FOREIGN KEY (id_candidature) REFERENCES candidatures (id_candidature)');
        $this->addSql('ALTER TABLE offres_stage ADD CONSTRAINT FK_27D34C36A8937AB7 FOREIGN KEY (id_entreprise) REFERENCES entreprises (id_entreprise)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis_entreprises DROP FOREIGN KEY FK_958FA0621A5CE76');
        $this->addSql('ALTER TABLE avis_entreprises DROP FOREIGN KEY FK_958FA06A8937AB7');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF6621A5CE76');
        $this->addSql('ALTER TABLE candidatures DROP FOREIGN KEY FK_DE57CF664103C75F');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288FB291E4F');
        $this->addSql('ALTER TABLE feedbacks DROP FOREIGN KEY FK_7E6C3F89FB291E4F');
        $this->addSql('ALTER TABLE offres_stage DROP FOREIGN KEY FK_27D34C36A8937AB7');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE avis_entreprises');
        $this->addSql('DROP TABLE candidatures');
        $this->addSql('DROP TABLE documents');
        $this->addSql('DROP TABLE entreprises');
        $this->addSql('DROP TABLE etudiants');
        $this->addSql('DROP TABLE feedbacks');
        $this->addSql('DROP TABLE offres_stage');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
