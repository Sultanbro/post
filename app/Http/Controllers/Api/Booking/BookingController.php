<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Services\Booking\BookingServiceInterface;
use App\Repository\Booking\BookingRepositoryInterface;
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
    private $bookingRepository;

    public function __construct(BookingServiceInterface $bookingService, BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingService = $bookingService;
        $this->bookingRepository = $bookingRepository;
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
     * @param $id
     * @param $request
     * @return mixed|void
     */
    public function update($id, $request)
    {
        $booking = $this->bookingRepository->find($id);
        if(!is_null($booking)){
            return response()->json(['message' => 'Booking updated','success' => $booking->update($request)],200);
        } else {
            return response()->json(['message' => 'This booking not found for update','error' => 'Enter correct id'],404);
        }
    }

    /**
     * @param  $id
     * @return JsonResponse|Response
     */
    public function destroy($id)
    {
        if($this->bookingRepository->find($id)){
            return response()->json(['message' => 'Booking deleted','success' => $this->bookingRepository->deleteById($id)],200);
        }else {
            return response()->json(['message' => 'This booking not found for delete', 'error' => 'Enter correct id'],404);
        }
    }
}
