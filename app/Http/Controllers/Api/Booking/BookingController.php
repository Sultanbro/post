<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Services\Booking\BookingServiceInterface;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private $bookingService;

    public function __construct(BookingServiceInterface $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function index() { return $this->bookingService->index(); }
    public function show($id) { return $this->bookingService->show($id); }
    public function store(Request $request)
    {
        return $this->bookingService->store($request->all(), $request->user()->id);
    }
    public function update(Request $request, $id)
    {
        return $this->bookingService->update($id, $request->all(), $request->user()->id);
    }
    public function destroy($id) { return $this->bookingService->destroy($id); }
}
