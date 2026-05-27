<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260527182921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ADD niveau_scolaire_classe VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD niveau_scolaire_annee INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD niveau_scolaire_niveau VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD niveau_scolaire_fillier VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP niveau_scolaire_classe');
        $this->addSql('ALTER TABLE "user" DROP niveau_scolaire_annee');
        $this->addSql('ALTER TABLE "user" DROP niveau_scolaire_niveau');
        $this->addSql('ALTER TABLE "user" DROP niveau_scolaire_fillier');
    }
}
