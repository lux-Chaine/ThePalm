-- Pricing Settings Seeder
-- Run this to insert default pricing settings into the settings table

INSERT INTO settings (`key`, value, type, category, description, created_at, updated_at) VALUES
-- Room base prices
('price_single', '500', 'number', 'pricing', 'Base price for single rooms per night', NOW(), NOW()),
('price_double', '700', 'number', 'pricing', 'Base price for double rooms per night', NOW(), NOW()),
('price_suite', '1200', 'number', 'pricing', 'Base price for suite rooms per night', NOW(), NOW()),
('price_deluxe', '2000', 'number', 'pricing', 'Base price for deluxe rooms per night', NOW(), NOW()),
('price_penthouse', '3500', 'number', 'pricing', 'Base price for penthouse rooms per night', NOW(), NOW()),

-- Tax settings
('tax_rate', '14', 'number', 'pricing', 'VAT tax rate percentage', NOW(), NOW()),

-- Discount settings
('seasonal_discount_percentage', '0', 'number', 'pricing', 'Seasonal discount percentage', NOW(), NOW()),
('long_stay_discount_7_nights', '10', 'number', 'pricing', 'Discount percentage for 7+ nights', NOW(), NOW()),
('long_stay_discount_14_nights', '15', 'number', 'pricing', 'Discount percentage for 14+ nights', NOW(), NOW()),
('group_discount_min_guests', '3', 'number', 'pricing', 'Minimum guests for group discount', NOW(), NOW()),
('group_discount_percentage', '5', 'number', 'pricing', 'Discount percentage for group bookings', NOW(), NOW()),

-- Deposit settings
('deposit_required_nights', '3', 'number', 'pricing', 'Minimum nights to require deposit', NOW(), NOW()),
('deposit_percentage', '20', 'number', 'pricing', 'Deposit percentage of total amount', NOW(), NOW()),

-- Cancellation settings
('cancellation_deadline_hours', '24', 'number', 'pricing', 'Hours before check-in for free cancellation', NOW(), NOW()),
('cancellation_fee_percentage', '50', 'number', 'pricing', 'Cancellation fee percentage after deadline', NOW(), NOW()),

-- Late fee settings
('late_fee_daily_rate', '0.01', 'number', 'pricing', 'Daily late fee rate as decimal (0.01 = 1%)', NOW(), NOW()),

-- Promotional codes (stored as JSON)
('promotional_codes', '{"WELCOME10":{"discount":0.10,"type":"percentage","expires":"2024-12-31"},"SUMMER20":{"discount":0.20,"type":"percentage","expires":"2024-09-30"},"FLAT50":{"discount":50,"type":"fixed","expires":"2024-12-31"}}', 'json', 'pricing', 'Available promotional codes', NOW(), NOW())

ON DUPLICATE KEY UPDATE 
    value = VALUES(value),
    description = VALUES(description),
    updated_at = NOW();
