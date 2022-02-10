<?php


namespace App\Http\Services\Booking;

use App\Http\Resources\Booking\BookingResource;
use App\Repository\Booking\BookingRepositoryInterface;
use App\Repository\Client\Department\DepartmentRepositoryInterface;


class BookingService implements BookingServiceInterface
{
    /**
     * @var BookingRepositoryInterface
     */
    private $bookingRepository;
    /**
     * @var DepartmentRepositoryInterface
     */
    private $departmentRepository;

    /**
     * @param BookingRepositoryInterface $bookingRepository
     * @param DepartmentRepositoryInterface $departmentRepository
     */
    public function __construct(BookingRepositoryInterface $bookingRepository, DepartmentRepositoryInterface $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function index()
    {
        return BookingResource::collection($this->bookingRepository->getByRoleCompany('index_booking'))
            ->additional($this->departmentRepository->getAccessCompany('index_booking'));
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
     * @return BookingResource
     */
    public function show($id)
    {
        return new BookingResource($this->bookingRepository->firstByRoleCompanyAndModelId($id, 'show_booking'));
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
     * @param $id
     * @return bool|mixed
     */
    public function delete($id)
    {
        if($this->bookingRepository->find($id)){
            return response()->json(['message' => 'Booking deleted','success' => $this->bookingRepository->deleteById($id)],200);
        }else {
            return response()->json(['message' => 'This booking not found for delete', 'error' => 'Enter correct id'],404);
        }
    }
}
