
DROP TABLE IF EXISTS `activity_comments`;
DROP TABLE IF EXISTS `customer_activities`;
DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `public_id` VARCHAR(16) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,

    UNIQUE KEY `uq_public_id` (`public_id`),
    UNIQUE KEY `uq_email` (`email`),
    KEY `idx_is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `customer_activities` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `customer_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(32) NOT NULL,
    `details` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    KEY `idx_customer_created` (`customer_id`, `created_at`),
    KEY `idx_type` (`type`),

    CONSTRAINT `fk_activity_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `activity_comments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `customer_activity_id` BIGINT UNSIGNED NOT NULL,
    `comment` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    KEY `idx_activity_created` (`customer_activity_id`, `created_at`),

    CONSTRAINT `fk_comment_activity` FOREIGN KEY (`customer_activity_id`) REFERENCES `customer_activities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
