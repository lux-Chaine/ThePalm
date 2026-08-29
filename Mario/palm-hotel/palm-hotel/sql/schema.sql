-- =========================================================
-- The Palm Hotel — Database Schema
-- Import this file from cPanel > phpMyAdmin (or via SSH mysql)
-- =========================================================

SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- ---------------------------------------------------------
-- Table: rooms
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rooms` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name`          VARCHAR(120) NOT NULL,
  `slug`          VARCHAR(140) NOT NULL UNIQUE,
  `size_sqm`      INT UNSIGNED NOT NULL DEFAULT 0,
  `config_text`   VARCHAR(255) DEFAULT NULL,        -- e.g. "King Bed (4 Rooms) · Twin Bed (28 Rooms)"
  `description`   TEXT,
  `occupancy`     VARCHAR(80)  DEFAULT NULL,        -- e.g. "2 Adults"
  `view_type`     VARCHAR(80)  DEFAULT NULL,        -- e.g. "City / Courtyard"
  `floor_range`   VARCHAR(80)  DEFAULT NULL,        -- e.g. "1st – 4th"
  `amenities`     VARCHAR(500) DEFAULT NULL,        -- comma separated tags
  `price_per_night` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `total_units`   INT UNSIGNED NOT NULL DEFAULT 1,  -- how many physical rooms of this type exist
  `image_1`       VARCHAR(500) DEFAULT NULL,
  `image_2`       VARCHAR(500) DEFAULT NULL,
  `sort_order`    INT UNSIGNED NOT NULL DEFAULT 0,
  `is_active`     TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Table: bookings
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `room_id`       INT UNSIGNED NOT NULL,
  `full_name`     VARCHAR(150) NOT NULL,
  `email`         VARCHAR(180) NOT NULL,
  `phone`         VARCHAR(40)  NOT NULL,
  `check_in`      DATE NOT NULL,
  `check_out`     DATE NOT NULL,
  `adults`        SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  `children`      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `notes`         TEXT,
  `status`        ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT `fk_bookings_room` FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Table: admin_users
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `username`      VARCHAR(60) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name`     VARCHAR(150) DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- Seed: rooms (matches the current website content)
-- ---------------------------------------------------------
INSERT INTO `rooms`
(`name`,`slug`,`size_sqm`,`config_text`,`description`,`occupancy`,`view_type`,`floor_range`,`amenities`,`price_per_night`,`total_units`,`image_1`,`image_2`,`sort_order`)
VALUES
('Standard Room','standard-room',25,'King Bed (4 Rooms) · Twin Bed (28 Rooms)',
 'Sized for corporate travelers and short city breaks alike. Every Standard Room features premium Egyptian cotton linens and a dedicated workspace.',
 '2 Adults','City / Courtyard','1st – 4th','A/C,Shower,Safe Box,WiFi,Mini Bar',
 1200.00, 32, 'https://the-palm.vercel.app/images/palm7.jpeg','https://the-palm.vercel.app/images/palm8.jpeg', 1),

('Superior Room','superior-room',32,'King or Twin Bed (10 Rooms)',
 'A step up in space and outlook, with a seating nook by the window and upgraded bath amenities — well suited to longer stays.',
 '2 Adults, 1 Child','Garden / Pool','2nd – 5th','A/C,Bathtub,Safe Box,WiFi,Mini Bar,Seating Area',
 1650.00, 10, 'https://the-palm.vercel.app/images/palm7.jpeg','https://the-palm.vercel.app/images/palm8.jpeg', 2),

('Junior Suite','junior-suite',45,'Premium Suite Collection (4 Suites)',
 'An expansive living area alongside a spacious master bedroom — built for guests who want extra room to settle in and unwind.',
 '3 Adults','Garden','3rd – 5th','A/C,Shower,Safe Box,WiFi,Mini Bar,Living Area',
 2400.00, 4, 'https://the-palm.vercel.app/images/palm9.jpeg','https://the-palm.vercel.app/images/palm10.jpeg', 3),

('Executive Suite','executive-suite',60,'Top-Floor Collection (2 Suites)',
 'The Palm''s most spacious address — a separate lounge, dining nook, and master bedroom, finished with upgraded furnishings for guests staying longer or hosting privately.',
 '4 Adults','City Panorama','Top Floor','A/C,Bathtub,Safe Box,WiFi,Mini Bar,Dining Nook,Lounge',
 3200.00, 2, 'https://the-palm.vercel.app/images/palm37.jpeg','https://the-palm.vercel.app/images/palm36.jpg', 4);

-- ---------------------------------------------------------
-- NOTE on the admin account:
-- No admin user is seeded here on purpose (a hard-coded password
-- hash in a public SQL file is a security risk). After importing
-- this schema, open /admin/setup.php in your browser ONCE — it
-- will let you create the first admin username/password safely,
-- then it locks itself automatically. See README.md.
-- ---------------------------------------------------------
