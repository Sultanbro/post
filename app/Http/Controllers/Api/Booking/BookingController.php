<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Services\Booking\BookingServiceInterface;
use App\Models\Booking\Booking;
use App\Models\Booking\BookingUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * @var BookingServiceInterface
     */
    private $bookingService;

    public function __construct(BookingServiceInterface $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return $this->bookingService->index();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request)
    {
        $user_id = Auth::id();
        return $this->bookingService->store($request->all(), $user_id);

    }

    /**
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return $this->bookingService->show($id);
    }

    /**
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        return $this->bookingService->update($id, $request->all());
    }

    /**
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->bookingService->delete($id);
    }
}
