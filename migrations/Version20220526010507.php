<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526010507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE indicador (id INT AUTO_INCREMENT NOT NULL, ente_id INT NOT NULL, categoria_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, formula LONGTEXT DEFAULT NULL, INDEX IDX_CD123EC3EFB68F0A (ente_id), INDEX IDX_CD123EC33397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE indicador ADD CONSTRAINT FK_CD123EC3EFB68F0A FOREIGN KEY (ente_id) REFERENCES ente (id)');
        $this->addSql('ALTER TABLE indicador ADD CONSTRAINT FK_CD123EC33397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE indicador');
    }
}
