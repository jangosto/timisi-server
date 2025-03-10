<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250107CreateUserAndRoleTables extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables user, role, and user_role, and insert initial roles';
    }

    public function up(Schema $schema): void
    {
        // Create `user` table
        $this->addSql('CREATE TABLE `user` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(255) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `created_at` DATETIME NOT NULL,
            `updated_at` DATETIME NOT NULL,
            `deleted_at` DATETIME DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `unique_username` (`username`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        // Create `role` table
        $this->addSql('CREATE TABLE `role` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(50) NOT NULL,
            `role` VARCHAR(50) NOT NULL UNIQUE,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        // Create `user_role` table
        $this->addSql('CREATE TABLE `user_role` (
            `user_id` BIGINT UNSIGNED NOT NULL,
            `role_id` INT UNSIGNED NOT NULL,
            PRIMARY KEY (`user_id`, `role_id`),
            FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`role_id`) REFERENCES `role`(`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');

        // Insert initial roles
        $this->addSql("INSERT INTO `role` (`name`, `role`) VALUES
            ('Administrator', 'ROLE_ADMIN'),
            ('User', 'ROLE_USER'),
            ('Manager', 'ROLE_MANAGER'),
            ('Super Manager', 'ROLE_SUPER_MANAGER'),
            ('Super Administrator', 'ROLE_SUPER_ADMIN')");
    }

    public function down(Schema $schema): void
    {
        // Drop `user_role` table
        $this->addSql('DROP TABLE IF EXISTS `user_role`');

        // Drop `role` table
        $this->addSql('DROP TABLE IF EXISTS `role`');

        // Drop `user` table
        $this->addSql('DROP TABLE IF EXISTS `user`');
    }
}
