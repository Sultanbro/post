<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Resources\Booking\RoomResource;
use App\Repository\Booking\Room\RoomRepositoryInterface;
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

    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return RoomResource::collection($this->roomRepository->all());
    }

    /**
     * @param Request $request
     * @return RoomResource
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $rooms = $this->roomRepository->firstOrCreate(array_merge($request->all(), [
            'created_by' => $user_id,
            'updated_by' => $user_id
        ]));

        return new RoomResource($rooms);
    }

    /**
     * @param  $id
     * @return Model|JsonResponse
     */
    public function show($id)
    {
        if($this->roomRepository->find($id) != null){
            return $this->roomRepository->find($id);
        } else {
            return response()->json(['message' => 'This room not found for show'], 404);
        }
    }

    /**
     * @param $id
     * @param Request $request
     * @return bool|JsonResponse
     */
    public function update(Request $request, $id)
    {
       if($room = $this->roomRepository->find($id) != null){
           return  $room->update($request->all());
       } else {
           return response()->json(['message' => 'This room not found for update'], 404);
       }
    }

    /**
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        if($this->roomRepository->deleteById($id) != null){
            return $this->roomRepository->deleteById($id);
        } else {
            return response()->json(['message' => 'This room not found for delete'], 404);
        }
    }
}
