<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250618141222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE famouslocation (id INT AUTO_INCREMENT NOT NULL, city VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE unavailable_time_slot (id INT AUTO_INCREMENT NOT NULL, unavailableslots_id INT DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_62DDB8334ACA83F4 (unavailableslots_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE unavailable_time_slot ADD CONSTRAINT FK_62DDB8334ACA83F4 FOREIGN KEY (unavailableslots_id) REFERENCES reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement ADD city VARCHAR(150) NOT NULL, ADD zipcode VARCHAR(150) NOT NULL, ADD daily_price DOUBLE PRECISION NOT NULL, CHANGE full_address address VARCHAR(150) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE unavailable_time_slot DROP FOREIGN KEY FK_62DDB8334ACA83F4
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE famouslocation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE unavailable_time_slot
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE announcement ADD full_address VARCHAR(150) NOT NULL, DROP address, DROP city, DROP zipcode, DROP daily_price
        SQL);
    }
}
