<?php

// Standalone seeder runner for Palm Hotel ERP
// This runs seeders without requiring full Laravel installation

// Database configuration for XAMPP
$host = 'localhost';
$port = 3306;
$dbname = 'palm_hotel';
$username = 'root';
$password = ''; // XAMPP default MySQL password is empty

try {
    // Create PDO connection for XAMPP
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connected to XAMPP MySQL successfully!\n";
    echo "  Host: $host:$port\n";
    echo "  Database: $dbname\n\n";

    // Run seeders
    echo "Running seeders...\n";
    echo str_repeat("=", 50) . "\n\n";

    // UserSeeder
    echo "Running UserSeeder...\n";
    $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, user_type, email_verified_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Admin User', 'admin@palm.com', $passwordHash, 'admin', 'staff', date('Y-m-d H:i:s')]);
        echo "✓ Admin user created (admin@palm.com / admin123)\n";
    } catch (PDOException $e) {
        echo "ℹ Admin user may already exist: " . $e->getMessage() . "\n";
    }

    try {
        $passwordHash = password_hash('manager123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, user_type, email_verified_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Manager User', 'manager@palm.com', $passwordHash, 'manager', 'staff', date('Y-m-d H:i:s')]);
        echo "✓ Manager user created (manager@palm.com / manager123)\n";
    } catch (PDOException $e) {
        echo "ℹ Manager user may already exist: " . $e->getMessage() . "\n";
    }

    try {
        $passwordHash = password_hash('receptionist123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, user_type, email_verified_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Receptionist User', 'receptionist@palm.com', $passwordHash, 'receptionist', 'staff', date('Y-m-d H:i:s')]);
        echo "✓ Receptionist user created (receptionist@palm.com / receptionist123)\n";
    } catch (PDOException $e) {
        echo "ℹ Receptionist user may already exist: " . $e->getMessage() . "\n";
    }

    echo "\n";

    // SettingSeeder
    echo "Running SettingSeeder...\n";
    $settings = [
        // Hotel Information
        ['hotel_name', 'Palm Hotel', 'string', 'hotel', 'Hotel name displayed on invoices and reports'],
        ['hotel_address', '123 Main Street, Cairo, Egypt', 'string', 'hotel', 'Hotel physical address'],
        ['hotel_phone', '+201234567890', 'string', 'hotel', 'Hotel contact phone number'],
        ['hotel_email', 'info@palmhotel.com', 'string', 'hotel', 'Hotel contact email'],
        ['hotel_website', 'https://palmhotel.com', 'string', 'hotel', 'Hotel website URL'],
        // Pricing Settings
        ['default_room_price', '500', 'number', 'pricing', 'Default price for standard rooms'],
        ['currency', 'EGP', 'string', 'pricing', 'Currency code for all transactions'],
        ['tax_rate', '14', 'number', 'pricing', 'Tax rate percentage'],
        ['service_charge_rate', '10', 'number', 'pricing', 'Service charge percentage'],
        // Booking Settings
        ['min_nights', '1', 'number', 'booking', 'Minimum number of nights for booking'],
        ['max_nights', '30', 'number', 'booking', 'Maximum number of nights for booking'],
        ['check_in_time', '14:00', 'string', 'booking', 'Standard check-in time'],
        ['check_out_time', '12:00', 'string', 'booking', 'Standard check-out time'],
        ['advance_payment_required', 'true', 'boolean', 'booking', 'Whether advance payment is required'],
        ['advance_payment_percentage', '50', 'number', 'booking', 'Percentage of advance payment required'],
        // Billing Settings
        ['invoice_prefix', 'INV-', 'string', 'billing', 'Prefix for invoice numbers'],
        ['invoice_start_number', '1001', 'number', 'billing', 'Starting number for invoices'],
        ['payment_terms', 'Payment due upon check-out', 'string', 'billing', 'Default payment terms'],
        // General Settings
        ['default_language', 'en', 'string', 'general', 'Default language for the system'],
        ['timezone', 'Africa/Cairo', 'string', 'general', 'System timezone'],
        ['date_format', 'Y-m-d', 'string', 'general', 'Default date format'],
    ];

    $stmt = $pdo->prepare("INSERT INTO settings (`key`, value, type, category, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
    $inserted = 0;
    foreach ($settings as $setting) {
        try {
            $stmt->execute($setting);
            $inserted++;
        } catch (PDOException $e) {
            echo "  ⚠ Skipped: " . $setting[0] . " - " . $e->getMessage() . "\n";
        }
    }
    echo "✓ $inserted settings inserted\n";
    echo "\n";

    // RoomSeeder
    echo "Running RoomSeeder...\n";
    $rooms = [];

    // Single Rooms (5 rooms)
    for ($i = 1; $i <= 5; $i++) {
        $rooms[] = [sprintf('1%02d', $i), 'single', 400, 1, 'available', 1, 'Comfortable single room with city view'];
    }

    // Double Rooms (10 rooms)
    for ($i = 1; $i <= 10; $i++) {
        $rooms[] = [sprintf('2%02d', $i), 'double', 600, 2, 'available', $i <= 5 ? 2 : 3, 'Spacious double room with modern amenities'];
    }

    // Suite Rooms (5 rooms)
    for ($i = 1; $i <= 5; $i++) {
        $rooms[] = [sprintf('3%02d', $i), 'suite', 1200, 4, 'available', 4, 'Luxury suite with separate living area'];
    }

    $stmt = $pdo->prepare("INSERT INTO rooms (room_number, type, price_per_night, capacity, status, floor, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $inserted = 0;
    foreach ($rooms as $room) {
        try {
            $stmt->execute($room);
            $inserted++;
        } catch (PDOException $e) {
            // Ignore duplicate entries
        }
    }
    echo "✓ $inserted rooms inserted\n";

    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 Seeding completed successfully!\n";
    echo "\nDefault users created:\n";
    echo "  - Admin: admin@palm.com / admin123\n";
    echo "  - Manager: manager@palm.com / manager123\n";
    echo "  - Receptionist: receptionist@palm.com / receptionist123\n";

} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database credentials in the script.\n";
    exit(1);
}
