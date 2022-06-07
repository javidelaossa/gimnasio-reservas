<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220601180446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actividades (id INT AUTO_INCREMENT NOT NULL, sala_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, h_inicio TIME NOT NULL, h_fin TIME NOT NULL, imagen VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_73D548DEC51CDF3F (sala_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sala (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, numero INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actividades ADD CONSTRAINT FK_73D548DEC51CDF3F FOREIGN KEY (sala_id) REFERENCES sala (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actividades DROP FOREIGN KEY FK_73D548DEC51CDF3F');
        $this->addSql('DROP TABLE actividades');
        $this->addSql('DROP TABLE sala');
        $this->addSql('DROP TABLE usuario');
    }
}
