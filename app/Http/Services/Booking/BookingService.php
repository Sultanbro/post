<?php


namespace App\Http\Services\Booking;

use App\Http\Resources\Booking\BookingResource;
use App\Repository\Booking\BookingRepositoryInterface;


class BookingService implements BookingServiceInterface
{
    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;

    /**
     * @param BookingRepositoryInterface $bookingRepository
     */
    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function index()
    {
        return BookingResource::collection($this->bookingRepository->all());
    }

    /**
     * @param $request
     * @param $user_id
     * @return BookingResource|mixed
     */
    public function store($request, $user_id)
    {
        $success = true;
        $available = false;
        $begin = date('Y-m-d H:i:s', strtotime($request['begin']));
        $end = date('Y-m-d H:i:s', strtotime($request['end']));

        $records = $this->bookingRepository->whereBetween($request);

        if($records){
            foreach ($records as $record){
                if(strtotime($end) >= strtotime($record->end) && strtotime($end) <= strtotime($record->begin)){
                    $available = true;
                } elseif (strtotime($begin) >= strtotime($record->begin) && strtotime($begin) <= strtotime($record->end)){
                    $available = true;
                }
            }
        }
        if($available){
            $success = false;
            $error = 'Зал уже занят на это время.Забронировал '.$user_id;
        } else {
            $booking = $this->bookingRepository->create(array_merge($request,[
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]));

            new BookingResource($booking);
        }

        return response()->json(['success'=>$success,'error' => isset($error) ? $error : '']);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|null
     */
    public function show($id)
    {
        if($this->bookingRepository->find($id) != null){
            return $this->bookingRepository->find($id);
        } else {
            return response()->json(['message' => 'This booking not found for show'],404);
        }
    }

    /**
     * @param $id
     * @param $request
     * @return mixed|void
     */
    public function update($id, $request)
    {
        $booking = $this->bookingRepository->find($id);
        if($booking != null){
            return $booking->update($request);
        } else {
            return response()->json(['message' => 'This booking not found for update'],404);
        }
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function delete($id)
    {
        if($this->bookingRepository->deleteById($id) != null){
            return $this->bookingRepository->deleteById($id);
        }else {
            return response()->json(['message' => 'This booking not found for delete'],404);
        }
    }
}
