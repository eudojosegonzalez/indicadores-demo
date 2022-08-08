<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601012330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reporte ADD categoria_id INT NOT NULL');
        $this->addSql('ALTER TABLE reporte ADD CONSTRAINT FK_5CB12143397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_5CB12143397707A ON reporte (categoria_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reporte DROP FOREIGN KEY FK_5CB12143397707A');
        $this->addSql('DROP INDEX IDX_5CB12143397707A ON reporte');
        $this->addSql('ALTER TABLE reporte DROP categoria_id');
    }
}
