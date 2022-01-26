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
        $rooms = $this->roomRepository->create(array_merge($request->all(), [
            'created_by' => $user_id,
            'updated_by' => $user_id
        ]));

        return new RoomResource($rooms);
    }

    /**
     * @param  int $id
     * @return Model
     */
    public function show($id)
    {
        return $this->roomRepository->find($id);
    }

    /**
     * @param  int $id
     * @param Request $request
     * @return bool|JsonResponse
     */
    public function update(Request $request, $id)
    {
       $room = $this->roomRepository->find($id);
        return  $room->update($request->all());
    }

    /**
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return $this->roomRepository->deleteById($id);
    }
}
