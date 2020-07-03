<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200701200029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $now = date('Y-m-d H:i:s');

        $this->addSql("INSERT INTO \"users\" (status, email, password, role_id, first_name, last_name, created_at, updated_at) VALUES (1, 'admin@example.com', '".password_hash('123456', PASSWORD_BCRYPT)."', 1, 'Admin', 'Admin', '".$now."', '".$now."')");
        $this->addSql("INSERT INTO \"users\" (status, email, password, role_id, first_name, last_name, created_at, updated_at) VALUES (1, 'customer1@example.com', '".password_hash('123456', PASSWORD_BCRYPT)."', 2, 'First', 'Customer', '".$now."', '".$now."')");
        $this->addSql("INSERT INTO \"users\" (status, email, password, role_id, first_name, last_name, created_at, updated_at) VALUES (1, 'customer2@example.com', '".password_hash('w5=rM&DC=5M6AG2+', PASSWORD_BCRYPT)."', 2, 'Second', 'Customer', '".$now."', '".$now."')");
        $this->addSql("INSERT INTO \"users\" (status, email, password, role_id, first_name, last_name, created_at, updated_at) VALUES (2, 'customer3@example.com', '".password_hash('A5%G8T2veA$dPn_Q', PASSWORD_BCRYPT)."', 2, 'Deleted', 'Customer', '".$now."', '".$now."')");
        $this->addSql("INSERT INTO \"users\" (status, email, password, role_id, first_name, last_name, created_at, updated_at) VALUES (2, 'customer4@example.com', '".password_hash('2Z-Gq_e=7AF-MY3j', PASSWORD_BCRYPT)."', 2, 'Deleted', 'Customer', '".$now."', '".$now."')");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
