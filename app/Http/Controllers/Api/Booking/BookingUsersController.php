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
            $bookingUsers->create(['user_id' => $elem, 'booking_id' => $request->booking_id]);
        }
    }

    /**
     * @param int $id
     * @return Model
     */
    public function show($id)
    {
        return $this->bookingUsersRepository->find($id);
    }

    /**
     * @param Request $request
     * @param  $id
     * @return bool|Response
     */
    public function update(Request $request, $id)
    {
        $bookingUsers = $this->bookingUsersRepository->find($id);
        return  $bookingUsers->update($request->all());
    }

    /**
     * @param  $id
     * @return Response
     */
    public function destroy($id)
    {
        return $this->bookingUsersRepository->deleteById($id);
    }
}
