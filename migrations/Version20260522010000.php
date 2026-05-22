<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260522010000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Category and Product entities with step 2 seed data';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(120) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C1989D9B62 ON category (slug)');

        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, name VARCHAR(160) NOT NULL, slug VARCHAR(180) NOT NULL, description CLOB NOT NULL, sku VARCHAR(50) NOT NULL, price NUMERIC(10, 2) NOT NULL, image_filename VARCHAR(120) DEFAULT NULL, in_stock BOOLEAN NOT NULL, CONSTRAINT FK_D34A04ADF12469DE FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D34A04ADF12469DE ON product (category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04A989D9B62 ON product (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04A15F1BFE3 ON product (sku)');

        $this->addSql(<<<'SQL'
            INSERT INTO category (id, name, slug, description) VALUES
            (1, 'Electronics', 'electronics', 'Discover the latest in technology and electronics. From headphones to speakers, find everything you need to stay connected and entertained.'),
            (2, 'Fashion', 'fashion', 'Clothing, accessories and footwear for everyday style.'),
            (3, 'Home & Garden', 'home-garden', 'Furniture, decor and practical tools for your living spaces.'),
            (4, 'Sports & Fitness', 'sports', 'Workout gear, yoga mats and equipment for an active lifestyle.'),
            (5, 'Books', 'books', 'Fiction, non-fiction and educational titles for curious readers.'),
            (6, 'Beauty & Health', 'beauty', 'Skincare, cosmetics and wellness essentials.'),
            (7, 'Toys & Games', 'toys', 'Fun products for kids and family entertainment.'),
            (8, 'Automotive', 'automotive', 'Car accessories and maintenance tools for everyday driving.'),
            (9, 'Pet Supplies', 'pets', 'Food, toys and accessories for your pets.')
        SQL);

        $this->addSql(<<<'SQL'
            INSERT INTO product (id, category_id, name, slug, description, sku, price, image_filename, in_stock) VALUES
            (1, 1, 'Wireless Headphones', 'wireless-headphones', 'Experience premium sound quality with wireless headphones featuring long battery life and a comfortable over-ear design.', 'WH-2024-001', 79.99, 'airbod.png', 1),
            (2, 2, 'Classic Leather Jacket', 'classic-leather-jacket', 'A timeless jacket designed to bring style and comfort to your daily outfits.', 'FJ-2024-002', 149.99, 'item.png', 1),
            (3, 3, 'Smart Plant Sensor', 'smart-plant-sensor', 'Monitor soil moisture and sunlight levels with an easy-to-use smart gardening companion.', 'HG-2024-003', 34.99, 'item.png', 1),
            (4, 4, 'Yoga Mat Premium', 'yoga-mat-premium', 'A cushioned yoga mat with comfortable grip for workouts and stretching sessions.', 'SP-2024-004', 29.99, 'thumbnail.png', 1),
            (5, 1, 'Bluetooth Speaker', 'bluetooth-speaker', 'Portable Bluetooth speaker with balanced sound and a compact travel-friendly build.', 'EL-2024-005', 59.99, 'mouse.png', 1),
            (6, 5, 'Web Development Guide', 'web-development-guide', 'A practical book to help beginners understand modern web development foundations.', 'BK-2024-006', 24.99, 'thumbnail.png', 1),
            (7, 1, 'Wireless Mouse', 'wireless-mouse', 'Responsive wireless mouse that fits both office work and casual gaming setups.', 'EL-2024-007', 29.99, 'mouse.png', 1),
            (8, 1, 'Mechanical Keyboard', 'mechanical-keyboard', 'Mechanical keyboard with tactile keys designed for speed, comfort and durability.', 'EL-2024-008', 89.99, 'mouse.png', 1),
            (9, 6, 'Skin Care Starter Kit', 'skin-care-starter-kit', 'A simple skincare set with the basics for a balanced daily routine.', 'BH-2024-009', 39.99, 'thumbnail.png', 1),
            (10, 7, 'Building Blocks Set', 'building-blocks-set', 'Creative blocks that encourage playful construction and imagination.', 'TY-2024-010', 44.99, 'thumbnail.png', 1),
            (11, 8, 'Car Vacuum Cleaner', 'car-vacuum-cleaner', 'Compact vacuum cleaner designed to keep your vehicle interior clean with minimal effort.', 'AU-2024-011', 54.99, 'thumbnail.png', 1),
            (12, 9, 'Pet Travel Bowl', 'pet-travel-bowl', 'Foldable travel bowl that makes outdoor trips with pets easier and cleaner.', 'PT-2024-012', 14.99, 'thumbnail.png', 1)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE category');
    }
}
