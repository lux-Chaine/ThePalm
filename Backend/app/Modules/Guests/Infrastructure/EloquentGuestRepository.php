<?php

namespace App\Modules\Guests\Infrastructure;

use App\Modules\Guests\Domain\Guest;
use App\Modules\Guests\Domain\GuestRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentGuestRepository implements GuestRepositoryInterface
{
    public function findById(int $id): ?Guest
    {
        return Guest::find($id);
    }

    public function findByEmail(string $email): ?Guest
    {
        return Guest::where('email', $email)->first();
    }

    public function findByIdentityNumber(string $identityNumber): ?Guest
    {
        return Guest::where('identity_number', $identityNumber)->first();
    }

    public function findByPhone(string $phone): ?Guest
    {
        return Guest::where('phone', $phone)->first();
    }

    public function all(): Collection
    {
        return Guest::all();
    }

    public function create(array $data): Guest
    {
        return Guest::create($data);
    }

    public function update(Guest $guest, array $data): Guest
    {
        $guest->update($data);
        return $guest->fresh();
    }

    public function delete(Guest $guest): bool
    {
        return $guest->delete();
    }

    public function search(array $filters): Collection
    {
        $query = Guest::query();

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (isset($filters['phone'])) {
            $query->where('phone', 'like', '%' . $filters['phone'] . '%');
        }

        if (isset($filters['identity_number'])) {
            $query->where('identity_number', 'like', '%' . $filters['identity_number'] . '%');
        }

        if (isset($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (isset($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        return $query->get();
    }

    public function getWithReservations(int $id): ?Guest
    {
        return Guest::with('reservations')->find($id);
    }
}
