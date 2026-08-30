<?php

namespace App\Modules\Guests\Domain;

use Illuminate\Database\Eloquent\Collection;

interface GuestRepositoryInterface
{
    public function findById(int $id): ?Guest;
    
    public function findByEmail(string $email): ?Guest;
    
    public function findByIdentityNumber(string $identityNumber): ?Guest;
    
    public function findByPhone(string $phone): ?Guest;
    
    public function all(): Collection;
    
    public function create(array $data): Guest;
    
    public function update(Guest $guest, array $data): Guest;
    
    public function delete(Guest $guest): bool;
    
    public function search(array $filters): Collection;
    
    public function getWithReservations(int $id): ?Guest;
}
