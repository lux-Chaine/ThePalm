<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hotel Information
            [
                'key' => 'hotel_name',
                'value' => 'Palm Hotel',
                'type' => 'string',
                'category' => 'hotel',
                'description' => 'Hotel name displayed on invoices and reports',
            ],
            [
                'key' => 'hotel_address',
                'value' => '123 Main Street, Cairo, Egypt',
                'type' => 'string',
                'category' => 'hotel',
                'description' => 'Hotel physical address',
            ],
            [
                'key' => 'hotel_phone',
                'value' => '+201234567890',
                'type' => 'string',
                'category' => 'hotel',
                'description' => 'Hotel contact phone number',
            ],
            [
                'key' => 'hotel_email',
                'value' => 'info@palmhotel.com',
                'type' => 'string',
                'category' => 'hotel',
                'description' => 'Hotel contact email',
            ],
            [
                'key' => 'hotel_website',
                'value' => 'https://palmhotel.com',
                'type' => 'string',
                'category' => 'hotel',
                'description' => 'Hotel website URL',
            ],

            // Pricing Settings
            [
                'key' => 'default_room_price',
                'value' => '500',
                'type' => 'number',
                'category' => 'pricing',
                'description' => 'Default price for standard rooms',
            ],
            [
                'key' => 'currency',
                'value' => 'EGP',
                'type' => 'string',
                'category' => 'pricing',
                'description' => 'Currency code for all transactions',
            ],
            [
                'key' => 'tax_rate',
                'value' => '14',
                'type' => 'number',
                'category' => 'pricing',
                'description' => 'Tax rate percentage',
            ],
            [
                'key' => 'service_charge_rate',
                'value' => '10',
                'type' => 'number',
                'category' => 'pricing',
                'description' => 'Service charge percentage',
            ],

            // Booking Settings
            [
                'key' => 'min_nights',
                'value' => '1',
                'type' => 'number',
                'category' => 'booking',
                'description' => 'Minimum number of nights for booking',
            ],
            [
                'key' => 'max_nights',
                'value' => '30',
                'type' => 'number',
                'category' => 'booking',
                'description' => 'Maximum number of nights for booking',
            ],
            [
                'key' => 'check_in_time',
                'value' => '14:00',
                'type' => 'string',
                'category' => 'booking',
                'description' => 'Standard check-in time',
            ],
            [
                'key' => 'check_out_time',
                'value' => '12:00',
                'type' => 'string',
                'category' => 'booking',
                'description' => 'Standard check-out time',
            ],
            [
                'key' => 'advance_payment_required',
                'value' => 'true',
                'type' => 'boolean',
                'category' => 'booking',
                'description' => 'Whether advance payment is required',
            ],
            [
                'key' => 'advance_payment_percentage',
                'value' => '50',
                'type' => 'number',
                'category' => 'booking',
                'description' => 'Percentage of advance payment required',
            ],

            // Billing Settings
            [
                'key' => 'invoice_prefix',
                'value' => 'INV-',
                'type' => 'string',
                'category' => 'billing',
                'description' => 'Prefix for invoice numbers',
            ],
            [
                'key' => 'invoice_start_number',
                'value' => '1001',
                'type' => 'number',
                'category' => 'billing',
                'description' => 'Starting number for invoices',
            ],
            [
                'key' => 'payment_terms',
                'value' => 'Payment due upon check-out',
                'type' => 'string',
                'category' => 'billing',
                'description' => 'Default payment terms',
            ],

            // General Settings
            [
                'key' => 'default_language',
                'value' => 'en',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Default language for the system',
            ],
            [
                'key' => 'timezone',
                'value' => 'Africa/Cairo',
                'type' => 'string',
                'category' => 'general',
                'description' => 'System timezone',
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Default date format',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
