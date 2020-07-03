<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200701200025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $now = date('Y-m-d H:i:s');
        $this->connection->insert('role', ['status' => 1, 'name' => 'Admin', 'created_at' => $now, 'updated_at' => $now]);
        $this->connection->insert('role', ['status' => 1, 'name' => 'Customer', 'created_at' => $now, 'updated_at' => $now]);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
