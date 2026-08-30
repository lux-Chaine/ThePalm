<?php

// Standalone migration runner for Palm Hotel ERP
// This runs migrations without requiring full Laravel installation

// Database configuration for XAMPP
$host = 'localhost';
$port = 3306;
$dbname = 'palm_hotel';
$username = 'root';
$password = ''; // XAMPP default MySQL password is empty

try {
    // Create PDO connection for XAMPP
    $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connected to XAMPP MySQL successfully!\n";
    echo "  Host: $host:$port\n";
    echo "  Database: $dbname\n\n";

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' created or already exists.\n";

    // Select the database
    $pdo->exec("USE `$dbname`");
    echo "Using database '$dbname'.\n\n";

    // Migration files in order
    $migrations = [
        'create_users_table' => "
            CREATE TABLE IF NOT EXISTS users (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'manager', 'receptionist', 'housekeeping', 'maintenance', 'accountant') DEFAULT 'receptionist',
                user_type ENUM('staff', 'guest') DEFAULT 'staff',
                email_verified_at TIMESTAMP NULL,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL,
                INDEX idx_email (email),
                INDEX idx_role (role)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",

        'create_rooms_table' => "
            CREATE TABLE IF NOT EXISTS rooms (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                room_number VARCHAR(10) NOT NULL UNIQUE,
                type ENUM('Single', 'Double', 'Suite', 'Deluxe', 'Presidential') NOT NULL,
                price_per_night DECIMAL(10,2) NOT NULL,
                status ENUM('available', 'booked', 'maintenance', 'cleaning') DEFAULT 'available',
                floor INT DEFAULT 1,
                capacity INT DEFAULT 2,
                description TEXT NULL,
                amenities JSON NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL,
                INDEX idx_room_number (room_number),
                INDEX idx_type (type),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",

        'create_guests_table' => "
            CREATE TABLE IF NOT EXISTS guests (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NULL,
                phone VARCHAR(20) NOT NULL,
                identity_number VARCHAR(50) NOT NULL UNIQUE,
                identity_type VARCHAR(50) DEFAULT 'national_id',
                date_of_birth DATE NULL,
                address TEXT NULL,
                city VARCHAR(100) NULL,
                country VARCHAR(100) DEFAULT 'Egypt',
                notes TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL,
                INDEX idx_identity_number (identity_number),
                INDEX idx_phone (phone)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",

        'create_reservations_table' => "
            CREATE TABLE IF NOT EXISTS reservations (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                guest_id BIGINT UNSIGNED NOT NULL,
                room_id BIGINT UNSIGNED NOT NULL,
                user_id BIGINT UNSIGNED NOT NULL,
                check_in_date DATE NOT NULL,
                check_out_date DATE NOT NULL,
                number_of_guests INT DEFAULT 1,
                total_amount DECIMAL(10,2) NOT NULL,
                deposit_amount DECIMAL(10,2) DEFAULT 0,
                status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
                special_requests TEXT NULL,
                cancellation_reason TEXT NULL,
                cancelled_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL,
                FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE CASCADE,
                FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                INDEX idx_check_dates (check_in_date, check_out_date),
                INDEX idx_status (status),
                INDEX idx_guest_id (guest_id),
                INDEX idx_room_id (room_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",

        'create_invoices_table' => "
            CREATE TABLE IF NOT EXISTS invoices (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                invoice_number VARCHAR(50) NOT NULL UNIQUE,
                reservation_id BIGINT UNSIGNED NOT NULL,
                created_by BIGINT UNSIGNED NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                paid_amount DECIMAL(10,2) DEFAULT 0,
                discount_amount DECIMAL(10,2) DEFAULT 0,
                tax_amount DECIMAL(10,2) DEFAULT 0,
                payment_status ENUM('unpaid', 'partial', 'paid', 'overdue') DEFAULT 'unpaid',
                payment_method ENUM('cash', 'credit_card', 'bank_transfer', 'online') NULL,
                due_date DATE NOT NULL,
                paid_date DATE NULL,
                notes TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL,
                FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
                INDEX idx_invoice_number (invoice_number),
                INDEX idx_payment_status (payment_status),
                INDEX idx_due_date (due_date)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",

        'create_settings_table' => "
            CREATE TABLE IF NOT EXISTS settings (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `key` VARCHAR(255) NOT NULL UNIQUE,
                value TEXT NOT NULL,
                type ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
                category ENUM('general', 'hotel', 'pricing', 'booking', 'billing') DEFAULT 'general',
                description TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_key (`key`),
                INDEX idx_category (category)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",

        'create_expenses_table' => "
            CREATE TABLE IF NOT EXISTS expenses (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                created_by BIGINT UNSIGNED NOT NULL,
                category VARCHAR(50) NOT NULL,
                description VARCHAR(255) NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                expense_date DATE NOT NULL,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                receipt_url TEXT NULL,
                notes TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                deleted_at TIMESTAMP NULL,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
                INDEX idx_category (category),
                INDEX idx_expense_date (expense_date),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ",
    ];

    // Run migrations
    echo "Running migrations...\n";
    echo str_repeat("=", 50) . "\n\n";

    foreach ($migrations as $name => $sql) {
        try {
            echo "Running migration: $name... ";
            $pdo->exec($sql);
            echo "✓ Success\n";
        } catch (PDOException $e) {
            echo "✗ Failed: " . $e->getMessage() . "\n";
        }
    }

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Migration process completed!\n";

    // Create a default admin user
    echo "\nCreating default admin user...\n";
    $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Admin User', 'admin@palmhotel.com', $passwordHash, 'admin']);
        echo "✓ Default admin user created!\n";
        echo "  Email: admin@palmhotel.com\n";
        echo "  Password: admin123\n";
    } catch (PDOException $e) {
        echo "ℹ Admin user may already exist or there was an error: " . $e->getMessage() . "\n";
    }

    // Create sample rooms
    echo "\nCreating sample rooms...\n";
    $sampleRooms = [
        ['101', 'Single', 150.00, 1, 1],
        ['102', 'Single', 150.00, 1, 1],
        ['201', 'Double', 250.00, 2, 2],
        ['202', 'Double', 250.00, 2, 2],
        ['301', 'Suite', 450.00, 3, 3],
        ['302', 'Suite', 450.00, 3, 3],
        ['401', 'Deluxe', 350.00, 4, 2],
        ['402', 'Deluxe', 350.00, 4, 2],
        ['501', 'Presidential', 800.00, 5, 4],
        ['502', 'Presidential', 800.00, 5, 4],
    ];

    try {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, type, price_per_night, floor, capacity) VALUES (?, ?, ?, ?, ?)");
        foreach ($sampleRooms as $room) {
            $stmt->execute($room);
        }
        echo "✓ 10 sample rooms created!\n";
    } catch (PDOException $e) {
        echo "ℹ Sample rooms may already exist or there was an error: " . $e->getMessage() . "\n";
    }

    echo "\n🎉 Database setup completed successfully!\n";
    echo "You can now start using the Palm Hotel ERP system.\n";

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database credentials in the script.\n";
    exit(1);
}
