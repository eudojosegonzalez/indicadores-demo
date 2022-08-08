<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220527011217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE registro (id INT AUTO_INCREMENT NOT NULL, ente_id INT NOT NULL, categoria_id INT NOT NULL, indicador_id INT NOT NULL, periodo_id INT NOT NULL, valor NUMERIC(13, 2) NOT NULL, INDEX IDX_397CA85BEFB68F0A (ente_id), INDEX IDX_397CA85B3397707A (categoria_id), INDEX IDX_397CA85B47D487D1 (indicador_id), INDEX IDX_397CA85B9C3921AB (periodo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85BEFB68F0A FOREIGN KEY (ente_id) REFERENCES ente (id)');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B47D487D1 FOREIGN KEY (indicador_id) REFERENCES indicador (id)');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B9C3921AB FOREIGN KEY (periodo_id) REFERENCES periodo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE registro');
    }
}
