<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220603015235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subreporte (id INT AUTO_INCREMENT NOT NULL, ente_id INT NOT NULL, reporte_id INT NOT NULL, categoria_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, procedimiento VARCHAR(255) NOT NULL, INDEX IDX_4EA9BADBEFB68F0A (ente_id), INDEX IDX_4EA9BADB92CA572 (reporte_id), UNIQUE INDEX UNIQ_4EA9BADB3397707A (categoria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subreporte ADD CONSTRAINT FK_4EA9BADBEFB68F0A FOREIGN KEY (ente_id) REFERENCES ente (id)');
        $this->addSql('ALTER TABLE subreporte ADD CONSTRAINT FK_4EA9BADB92CA572 FOREIGN KEY (reporte_id) REFERENCES reporte (id)');
        $this->addSql('ALTER TABLE subreporte ADD CONSTRAINT FK_4EA9BADB3397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('ALTER TABLE reporte ADD CONSTRAINT FK_5CB12143397707A FOREIGN KEY (categoria_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_5CB12143397707A ON reporte (categoria_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE subreporte');
        $this->addSql('ALTER TABLE reporte DROP FOREIGN KEY FK_5CB12143397707A');
        $this->addSql('DROP INDEX IDX_5CB12143397707A ON reporte');
    }
}
