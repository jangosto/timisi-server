<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250211CreateSessionAndRoomTablesAlterUserTable extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables session, room, and modify user table to add new fields';
    }

    public function up(Schema $schema): void
    {
        // Create `session` table
        $this->addSql('CREATE TABLE `session` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `start_datetime` DATETIME NOT NULL,
            `end_datetime` DATETIME NOT NULL,
            `price_with_vat` DECIMAL(10, 2) NOT NULL,
            `vat_percentage` DECIMAL(5, 2) NOT NULL,
            `category` VARCHAR(255) NOT NULL,
            `capacity` INT NOT NULL,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            `deleted_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        // Create `room` table
        $this->addSql('CREATE TABLE `room` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `capacity` INT NOT NULL,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            `deleted_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        // Create `client_session` table
        $this->addSql('CREATE TABLE `client_session` (
            `user_id` BIGINT UNSIGNED NOT NULL,
            `session_id` BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (`user_id`, `session_id`),
            FOREIGN KEY (`user_id`) REFERENCES `user`(`id`),
            FOREIGN KEY (`session_id`) REFERENCES `session`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        // Create `professional_session` table
        $this->addSql('CREATE TABLE `professional_session` (
            `user_id` BIGINT UNSIGNED NOT NULL,
            `session_id` BIGINT UNSIGNED NOT NULL,
            PRIMARY KEY (`user_id`, `session_id`),
            FOREIGN KEY (`user_id`) REFERENCES `user`(`id`),
            FOREIGN KEY (`session_id`) REFERENCES `session`(`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        // Modify `user` table to add new fields
        $this->addSql('ALTER TABLE `user`
            ADD COLUMN `first_name` VARCHAR(255) NOT NULL,
            ADD COLUMN `last_name` VARCHAR(255) NOT NULL,
            ADD COLUMN `id_number` VARCHAR(50) NOT NULL,
            ADD CONSTRAINT `unique_id_number` UNIQUE (`id_number`)');
    }

    public function down(Schema $schema): void
    {
        // Drop `client_session` table
        $this->addSql('DROP TABLE IF EXISTS `client_session`');

        // Drop `professional_session` table
        $this->addSql('DROP TABLE IF EXISTS `professional_session`');

        // Drop `session` table
        $this->addSql('DROP TABLE IF EXISTS `session`');

        // Drop `room` table
        $this->addSql('DROP TABLE IF EXISTS `room`');

        // Remove new fields from `user` table
        $this->addSql('ALTER TABLE `user`
            DROP COLUMN `email`,
            DROP COLUMN `first_name`,
            DROP COLUMN `last_name`');
    }
}
