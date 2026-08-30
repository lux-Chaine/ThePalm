<?php

namespace App\Core\Services;

use App\Modules\Rooms\Domain\RoomRepositoryInterface;
use App\Modules\Settings\Domain\SettingRepositoryInterface;

class PricingService
{
    private RoomRepositoryInterface $roomRepository;
    private SettingRepositoryInterface $settingRepository;

    public function __construct(
        RoomRepositoryInterface $roomRepository,
        SettingRepositoryInterface $settingRepository
    ) {
        $this->roomRepository = $roomRepository;
        $this->settingRepository = $settingRepository;
    }

    /**
     * Calculate total price for a reservation
     */
    public function calculateReservationPrice(
        int $roomId,
        string $checkInDate,
        string $checkOutDate,
        int $numberOfGuests = 1
    ): array {
        $room = $this->roomRepository->findById($roomId);
        
        if (!$room) {
            throw new \Exception("Room not found");
        }

        $checkIn = new \DateTime($checkInDate);
        $checkOut = new \DateTime($checkOutDate);
        $duration = (int) $checkIn->diff($checkOut)->days;

        if ($duration <= 0) {
            throw new \Exception("Invalid date range");
        }

        $basePrice = $room->pricePerNight * $duration;
        $discount = $this->calculateDiscount($room->type, $duration, $numberOfGuests);
        $tax = $this->calculateTax($basePrice - $discount);
        $total = $basePrice - $discount + $tax;

        return [
            'base_price' => $basePrice,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'price_per_night' => $room->pricePerNight,
            'duration' => $duration,
            'breakdown' => [
                'room_type' => $room->type,
                'room_number' => $room->roomNumber,
                'check_in' => $checkInDate,
                'check_out' => $checkOutDate,
                'number_of_guests' => $numberOfGuests,
            ]
        ];
    }

    /**
     * Calculate discount based on room type, duration, and number of guests
     */
    private function calculateDiscount(string $roomType, int $duration, int $numberOfGuests): float
    {
        $discount = 0;
        $discountPercentage = 0;

        // Long stay discount (7+ nights)
        if ($duration >= 7) {
            $discountPercentage += 0.10; // 10% discount
        }

        // Long stay discount (14+ nights)
        if ($duration >= 14) {
            $discountPercentage += 0.05; // Additional 5% discount
        }

        // Group discount (3+ guests)
        if ($numberOfGuests >= 3) {
            $discountPercentage += 0.05; // 5% discount
        }

        // Seasonal discount (from settings)
        $seasonalDiscount = $this->getSetting('seasonal_discount_percentage', 0);
        $discountPercentage += ($seasonalDiscount / 100);

        // Calculate discount amount
        $basePricePerNight = $this->getBasePriceByRoomType($roomType);
        $baseTotal = $basePricePerNight * $duration;
        $discount = $baseTotal * $discountPercentage;

        return round($discount, 2);
    }

    /**
     * Calculate tax based on configured tax rate
     */
    private function calculateTax(float $amount): float
    {
        $taxRate = $this->getSetting('tax_rate', 14); // Default 14% VAT
        $tax = $amount * ($taxRate / 100);
        return round($tax, 2);
    }

    /**
     * Get base price by room type
     */
    private function getBasePriceByRoomType(string $roomType): float
    {
        $prices = [
            'single' => $this->getSetting('price_single', 500),
            'double' => $this->getSetting('price_double', 700),
            'suite' => $this->getSetting('price_suite', 1200),
            'deluxe' => $this->getSetting('price_deluxe', 2000),
            'penthouse' => $this->getSetting('price_penthouse', 3500),
        ];

        return $prices[$roomType] ?? 500;
    }

    /**
     * Get setting value with default
     */
    private function getSetting(string $key, $default = null)
    {
        try {
            $setting = $this->settingRepository->findByKey($key);
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Get available pricing tiers
     */
    public function getPricingTiers(): array
    {
        return [
            'single' => [
                'base_price' => $this->getSetting('price_single', 500),
                'description' => 'Single room with basic amenities'
            ],
            'double' => [
                'base_price' => $this->getSetting('price_double', 700),
                'description' => 'Double room for two guests'
            ],
            'suite' => [
                'base_price' => $this->getSetting('price_suite', 1200),
                'description' => 'Suite with living area'
            ],
            'deluxe' => [
                'base_price' => $this->getSetting('price_deluxe', 2000),
                'description' => 'Deluxe suite with premium amenities'
            ],
            'penthouse' => [
                'base_price' => $this->getSetting('price_penthouse', 3500),
                'description' => 'Penthouse with panoramic views'
            ],
        ];
    }

    /**
     * Apply promotional code
     */
    public function applyPromotionalCode(string $code, float $amount): array
    {
        // This would typically check against a database of promotional codes
        $promotionalCodes = [
            'WELCOME10' => ['discount' => 0.10, 'type' => 'percentage'],
            'SUMMER20' => ['discount' => 0.20, 'type' => 'percentage'],
            'FLAT50' => ['discount' => 50, 'type' => 'fixed'],
        ];

        if (!isset($promotionalCodes[$code])) {
            return [
                'success' => false,
                'message' => 'Invalid promotional code',
                'discount' => 0,
                'total' => $amount
            ];
        }

        $promo = $promotionalCodes[$code];
        $discount = $promo['type'] === 'percentage' 
            ? $amount * $promo['discount']
            : $promo['discount'];

        $total = max(0, $amount - $discount);

        return [
            'success' => true,
            'message' => 'Promotional code applied successfully',
            'discount' => round($discount, 2),
            'total' => round($total, 2)
        ];
    }
}
