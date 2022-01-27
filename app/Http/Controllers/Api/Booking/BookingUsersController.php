<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Resources\Booking\BookingUsersResource;
use App\Repository\Booking\BookingUsers\BookingUsersRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BookingUsersController extends Controller
{
    /**
     * @var BookingUsersRepositoryInterface
     */
    private $bookingUsersRepository;

    public function __construct(BookingUsersRepositoryInterface $bookingUsersRepository)
    {
        $this->bookingUsersRepository = $bookingUsersRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return BookingUsersResource::collection($this->bookingUsersRepository->all());
    }

    /**
     * @param  Request $request
     * @return BookingUsersResource
     */
    public function store(Request $request)
    {
        $bookingUsers = $this->bookingUsersRepository;
        foreach ($request->user_id as $elem){
            $bookingUsers->firstOrCreate(['user_id' => $elem, 'booking_id' => $request->booking_id]);
        }
    }

    /**
     * @param  $id
     * @return Model|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        if($this->bookingUsersRepository->find($id) != null) {
            return $this->bookingUsersRepository->find($id);
        } else {
            return response()->json(['message' => 'This booking not found for show'],404);
        }
    }

    /**
     * @param Request $request
     * @param  $id
     * @return bool|\Illuminate\Http\JsonResponse|Response
     */
    public function update(Request $request, $id)
    {
        $bookingUsers = $this->bookingUsersRepository->find($id);
        if($bookingUsers != null){
            return  $bookingUsers->update($request->all());
        } else {
            return response()->json(['message' => 'This booking not found for update'],404);
        }

    }

    /**
     * @param  $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function destroy($id)
    {
        if($this->bookingUsersRepository->deleteById($id) != null){
            return $this->bookingUsersRepository->deleteById($id);
        } else {
            return response()->json(['message' => 'This booking not found for delete'],404);
        }
    }
}
