<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220509220717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actividades (id INT AUTO_INCREMENT NOT NULL, sala_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, h_inicio TIME NOT NULL, h_fin TIME NOT NULL, UNIQUE INDEX UNIQ_73D548DEC51CDF3F (sala_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actividades ADD CONSTRAINT FK_73D548DEC51CDF3F FOREIGN KEY (sala_id) REFERENCES sala (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE actividades');
    }
}
