<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Resources\Booking\RoomResource;
use App\Http\Services\Booking\BookingServiceInterface;
use App\Models\Booking\Room;
use App\Repository\Booking\Room\RoomRepositoryInterface;
use App\Repository\Client\ClientRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{

    /**
     * @var RoomRepositoryInterface
     */
    private $roomRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * RoomController constructor.
     * @param RoomRepositoryInterface $roomRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(RoomRepositoryInterface $roomRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->roomRepository = $roomRepository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return RoomResource::collection($this->roomRepository->getByRoleCompany('index_room'))
            ->additional($this->departmentRepository->getAccessCompany('index_room'));
    }

    /**
     * @param Request $request
     * @param ClientRepositoryInterface $clientRepository
     * @return JsonResponse
     */
    public function store(Request $request, ClientRepositoryInterface $clientRepository)
    {
        $user_id = Auth::id();
        $company_id = $clientRepository->find($request->company_id);
        if(!is_null($company_id)){
            $rooms = $this->roomRepository->firstOrCreate(array_merge($request->all(), [
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]));
            return response()->json(['message' => 'Room created', 'success' => true, 'data' => new RoomResource($rooms)], 200);
        } else {
            return response()->json(['message' => 'This company not found for create', 'error' => 'Enter correct id'], 404);
        }
    }

    /**
     * @param Room $room
     * @return RoomResource|JsonResponse
     */
    public function show(Room $room)
    {
        return new RoomResource($this->roomRepository->firstByRoleCompany($room, 'show_room'));
    }

    /**
     * @param $id
     * @param Request $request
     * @return bool|JsonResponse
     */
    public function update(Request $request, $id)
    {
       if($this->roomRepository->find($id)){
           $room = $this->roomRepository->find($id);
           return  response()->json(['message' => 'Room updated', 'success' => $room->update($request->all())], 200);
       } else {
           return response()->json(['message' => 'This room not found for update','error' => 'Enter correct id'], 404);
       }
    }

    /**
     * @param $id
     * @param BookingServiceInterface $bookingService
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if($this->roomRepository->find($id)){
            return response()->json(['message' => 'Room delete','success' => $this->roomRepository->deleteById($id)],200);
        } else {
            return response()->json(['message' => 'This room not found for delete','error' => 'Enter correct id'], 404);
        }
    }
}
