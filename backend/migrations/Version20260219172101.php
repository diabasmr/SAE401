<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260219172101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statistiques (id INT AUTO_INCREMENT NOT NULL, annee_publication SMALLINT NOT NULL, nombre_habitants INT DEFAULT NULL, variation_population_10_ans NUMERIC(6, 2) DEFAULT NULL, nombre_logements INT DEFAULT NULL, moyenne_annuelle_construction_neuve_10_ans INT DEFAULT NULL, construction NUMERIC(8, 2) DEFAULT NULL, parc_social_nombre_logements INT DEFAULT NULL, parc_social_logements_demolis INT DEFAULT NULL, parc_social_taux_logements_vacants NUMERIC(5, 2) DEFAULT NULL, code_departement VARCHAR(3) NOT NULL, code_region VARCHAR(3) NOT NULL, INDEX IDX_B31AB0668837B2D3 (code_departement), INDEX IDX_B31AB06670E4A9D4 (code_region), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB0668837B2D3 FOREIGN KEY (code_departement) REFERENCES departement (code) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE statistiques ADD CONSTRAINT FK_B31AB06670E4A9D4 FOREIGN KEY (code_region) REFERENCES region (code) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB0668837B2D3');
        $this->addSql('ALTER TABLE statistiques DROP FOREIGN KEY FK_B31AB06670E4A9D4');
        $this->addSql('DROP TABLE statistiques');
    }
}
