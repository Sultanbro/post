<?php


namespace App\Http\Services\Centcoin;


interface CentcoinServiceInterface
{

    /**
     * @param $request
     * @param $created_id
     * @return mixed
     */
    public function transaction($request, $created_id);

    /**
     * @param $request
     * @param $created_id
     * @return mixed
     */
    public function applyOperation($request,$created_id);

    /**
     * @param $request
     * @return mixed
     */
    public function statusApply($request);

}
