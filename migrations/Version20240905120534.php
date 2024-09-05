<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905120534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cargo (id INT AUTO_INCREMENT NOT NULL, commodity_id INT NOT NULL, route_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_3BEE5771B4ACC212 (commodity_id), INDEX IDX_3BEE577134ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commodity (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hauling (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, anonymous_user VARCHAR(255) DEFAULT NULL, INDEX IDX_C9F1647AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, INDEX IDX_5E9E89CBA977936C (tree_root), INDEX IDX_5E9E89CB727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id INT AUTO_INCREMENT NOT NULL, from_location_id INT NOT NULL, to_location_id INT NOT NULL, hauling_id INT NOT NULL, from_specifique_location VARCHAR(255) DEFAULT NULL, to_specifique_location VARCHAR(255) DEFAULT NULL, INDEX IDX_2C42079980210EB (from_location_id), INDEX IDX_2C4207928DE1FED (to_location_id), INDEX IDX_2C42079F40C4456 (hauling_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cargo ADD CONSTRAINT FK_3BEE5771B4ACC212 FOREIGN KEY (commodity_id) REFERENCES commodity (id)');
        $this->addSql('ALTER TABLE cargo ADD CONSTRAINT FK_3BEE577134ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE hauling ADD CONSTRAINT FK_C9F1647AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBA977936C FOREIGN KEY (tree_root) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB727ACA70 FOREIGN KEY (parent_id) REFERENCES location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079980210EB FOREIGN KEY (from_location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C4207928DE1FED FOREIGN KEY (to_location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE route ADD CONSTRAINT FK_2C42079F40C4456 FOREIGN KEY (hauling_id) REFERENCES hauling (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cargo DROP FOREIGN KEY FK_3BEE5771B4ACC212');
        $this->addSql('ALTER TABLE cargo DROP FOREIGN KEY FK_3BEE577134ECB4E6');
        $this->addSql('ALTER TABLE hauling DROP FOREIGN KEY FK_C9F1647AA76ED395');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBA977936C');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB727ACA70');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079980210EB');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C4207928DE1FED');
        $this->addSql('ALTER TABLE route DROP FOREIGN KEY FK_2C42079F40C4456');
        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE commodity');
        $this->addSql('DROP TABLE hauling');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE user');
    }
}
