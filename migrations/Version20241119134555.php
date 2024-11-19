<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241119134555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fight (id INT AUTO_INCREMENT NOT NULL, team_host_id INT NOT NULL, team_guest_id INT NOT NULL, INDEX IDX_21AA4456E0C1A0B2 (team_host_id), INDEX IDX_21AA445622087A26 (team_guest_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, fight_id INT NOT NULL, team_id INT NOT NULL, points INT NOT NULL, INDEX IDX_32993751AC6657E4 (fight_id), INDEX IDX_32993751296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA4456E0C1A0B2 FOREIGN KEY (team_host_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE fight ADD CONSTRAINT FK_21AA445622087A26 FOREIGN KEY (team_guest_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751AC6657E4 FOREIGN KEY (fight_id) REFERENCES fight (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA4456E0C1A0B2');
        $this->addSql('ALTER TABLE fight DROP FOREIGN KEY FK_21AA445622087A26');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751AC6657E4');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751296CD8AE');
        $this->addSql('DROP TABLE fight');
        $this->addSql('DROP TABLE score');
    }
}
