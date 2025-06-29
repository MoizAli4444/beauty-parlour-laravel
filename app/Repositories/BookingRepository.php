<?php

// app/Repositories/BookingRepository.php

namespace App\Repositories;

use App\Models\Booking;
use App\Interfaces\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface
{
    public function all()
    {
        return Booking::all();
    }

    public function find($id)
    {
        return Booking::findOrFail($id);
    }

    public function store(array $data)
    {
        return Booking::create($data);
    }

    public function update($id, array $data)
    {
        $booking = Booking::findOrFail($id);
        $booking->update($data);
        return $booking;
    }

    public function delete($id)
    {
        return Booking::destroy($id);
    }
}


?>