<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220531025451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reporte (id INT AUTO_INCREMENT NOT NULL, ente_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, procedimiento VARCHAR(255) NOT NULL, INDEX IDX_5CB1214EFB68F0A (ente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reporte ADD CONSTRAINT FK_5CB1214EFB68F0A FOREIGN KEY (ente_id) REFERENCES ente (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reporte');
    }
}
