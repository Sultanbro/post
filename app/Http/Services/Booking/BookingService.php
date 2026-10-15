<?php

namespace App\Http\Services\Booking;

use App\Http\Resources\Booking\BookingResource;
use App\Models\Booking\Booking;
use App\Models\Booking\Room;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookingService implements BookingServiceInterface
{
    private function rooms($permission)
    {
        $roles = request()->user()->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('slug', $permission);
        })->get();
        $query = Room::query();
        if (!$roles->contains(function ($role) { return $role->company_id === null; })) {
            $query->whereIn('company_id', $roles->pluck('company_id'));
        }
        return $query;
    }

    private function booking($id, $permission)
    {
        return Booking::whereIn('room_id', $this->rooms($permission)->select('id'))->findOrFail($id);
    }

    public function index()
    {
        return BookingResource::collection(Booking::whereIn('room_id', $this->rooms('show_booking')->select('id'))->get());
    }

    public function show($id)
    {
        return new BookingResource($this->booking($id, 'show_booking'));
    }

    private function validated(array $data): array
    {
        return Validator::make($data, [
            'room_id' => 'required|integer|exists:rooms,id',
            'begin' => 'required|date_format:Y-m-d H:i:s',
            'end' => 'required|date_format:Y-m-d H:i:s|after:begin',
            'description' => 'nullable|string|max:255',
        ])->validate();
    }

    public function store($request, $user_id)
    {
        return $this->save($this->validated($request), $user_id);
    }

    public function update($id, array $request, $user_id)
    {
        $booking = $this->booking($id, 'update_booking');
        return $this->save($this->validated(array_merge($booking->toArray(), $request)), $user_id, $booking);
    }

    private function save(array $data, $user_id, ?Booking $booking = null)
    {
        $permission = $booking ? 'update_booking' : 'create_booking';
        $this->rooms($permission)->findOrFail($data['room_id']);
        return DB::transaction(function () use ($data, $user_id, $booking, $permission) {
            // Serialize writes for a room, including requests with no existing bookings.
            Room::whereKey($data['room_id'])->lockForUpdate()->firstOrFail();
            if ($booking) {
                $booking = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();
                $this->rooms($permission)->findOrFail($booking->room_id);
            }
            $overlap = Booking::where('room_id', $data['room_id'])
                ->where('begin', '<', $data['end'])->where('end', '>', $data['begin']);
            if ($booking) $overlap->where('id', '<>', $booking->id);
            if ($overlap->exists()) {
                return response()->json(['success' => false, 'error' => 'Зал уже занят на это время.'], 409);
            }
            if ($booking) {
                $booking->update(array_merge($data, ['updated_by' => $user_id]));
            } else {
                $booking = Booking::create(array_merge($data, ['created_by' => $user_id, 'updated_by' => $user_id]));
            }
            return response()->json(['success' => true, 'error' => '', 'data' => new BookingResource($booking)]);
        });
    }

    public function destroy($id)
    {
        $this->booking($id, 'delete_booking')->delete();
        return response()->json(['message' => 'Booking deleted', 'success' => true]);
    }
}
