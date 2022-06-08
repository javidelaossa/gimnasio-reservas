<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607181057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_73D548DEC51CDF3F ON actividades');
        $this->addSql('ALTER TABLE actividades ADD monitor_id INT NOT NULL');
        $this->addSql('ALTER TABLE actividades ADD CONSTRAINT FK_73D548DE4CE1C902 FOREIGN KEY (monitor_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_73D548DE4CE1C902 ON actividades (monitor_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73D548DEC51CDF3F ON actividades (sala_id)');
        $this->addSql('DROP INDEX UNIQ_AA1DAB016014FACA ON reservas');
        $this->addSql('DROP INDEX UNIQ_AA1DAB01DB38439E ON reservas');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA1DAB016014FACA ON reservas (actividad_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA1DAB01DB38439E ON reservas (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actividades DROP FOREIGN KEY FK_73D548DE4CE1C902');
        $this->addSql('DROP INDEX IDX_73D548DE4CE1C902 ON actividades');
        $this->addSql('DROP INDEX UNIQ_73D548DEC51CDF3F ON actividades');
        $this->addSql('ALTER TABLE actividades DROP monitor_id');
        $this->addSql('CREATE INDEX UNIQ_73D548DEC51CDF3F ON actividades (sala_id)');
        $this->addSql('DROP INDEX UNIQ_AA1DAB01DB38439E ON reservas');
        $this->addSql('DROP INDEX UNIQ_AA1DAB016014FACA ON reservas');
        $this->addSql('CREATE INDEX UNIQ_AA1DAB01DB38439E ON reservas (usuario_id)');
        $this->addSql('CREATE INDEX UNIQ_AA1DAB016014FACA ON reservas (actividad_id)');
    }
}
