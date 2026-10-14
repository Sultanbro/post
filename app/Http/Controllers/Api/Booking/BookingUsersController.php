<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Resources\Booking\BookingUsersResource;
use App\Models\Booking\BookingUsers;
use App\Repository\Booking\BookingUsers\BookingUsersRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
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
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * BookingUsersController constructor.
     * @param BookingUsersRepositoryInterface $bookingUsersRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(BookingUsersRepositoryInterface $bookingUsersRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->bookingUsersRepository = $bookingUsersRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return BookingUsersResource::collection($this->bookingUsersRepository->getByRoleCompany('show_booking_users'))
            ->additional($this->departmentRepository->getAccessCompany('show_booking_users'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $bookingUsers = $this->bookingUsersRepository;
        foreach ($request->user_id as $elem){
            $bookingUsers->firstOrCreate(['user_id' => $elem, 'booking_id' => $request->booking_id]);
        }
        return response()->json(['message' => 'Users of booking create', 'success'=> true,],200);
    }

    /**
     * @param BookingUsers $bookingUsers
     * @return BookingUsersResource|\Illuminate\Http\JsonResponse
     */
    public function show(BookingUsers $bookingUsers)
    {
        return new BookingUsersResource($this->bookingUsersRepository->firstByRoleCompany($bookingUsers, 'show_booking_users'));
    }

    /**
     * @param Request $request
     * @param  $id
     * @return bool|\Illuminate\Http\JsonResponse|Response
     */
    public function update(Request $request, $id)
    {
        $bookingUsers = $this->bookingUsersRepository->find($id);
        if(!is_null($bookingUsers)){
            return response()->json(['message' => 'Booking or user updated','success' => $bookingUsers->update($request->all())],200);
        } else {
            return response()->json(['message' => 'This booking not found for update','error' => 'Enter correct id'],404);
        }
    }

    /**
     * @param  $id
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function destroy($id)
    {
        if($this->bookingUsersRepository->find($id)){
            return response()->json(['message' => 'User delete from booking','success' => $this->bookingUsersRepository->deleteById($id)],200);
        } else {
            return response()->json(['message' => 'This booking not found for delete','error' => 'Enter correct id'],404);
        }
    }
}
