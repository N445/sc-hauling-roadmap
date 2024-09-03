<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240903125540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cargo (id INT AUTO_INCREMENT NOT NULL, commodity_id INT NOT NULL, route_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_3BEE5771B4ACC212 (commodity_id), INDEX IDX_3BEE577134ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hauling (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, from_location_id INT NOT NULL, to_location_id INT NOT NULL, hauling_id INT NOT NULL, INDEX IDX_2C42079980210EB (from_location_id), INDEX IDX_2C4207928DE1FED (to_location_id), INDEX IDX_2C42079F40C4456 (hauling_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cargo ADD CONSTRAINT FK_3BEE5771B4ACC212 FOREIGN KEY (commodity_id) REFERENCES commodity (id)');
        $this->addSql('ALTER TABLE cargo ADD CONSTRAINT FK_3BEE577134ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079980210EB FOREIGN KEY (from_location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207928DE1FED FOREIGN KEY (to_location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079F40C4456 FOREIGN KEY (hauling_id) REFERENCES hauling (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cargo DROP FOREIGN KEY FK_3BEE5771B4ACC212');
        $this->addSql('ALTER TABLE cargo DROP FOREIGN KEY FK_3BEE577134ECB4E6');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079980210EB');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C4207928DE1FED');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079F40C4456');
        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE hauling');
        $this->addSql('DROP TABLE route');
    }
}
