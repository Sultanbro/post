<?php


namespace App\Http\Services\Centcoin;


interface CentcoinServiceInterface
{

    public function index();

    /**
     * @param $id
     * @return mixed
     */
    public function show($id);

    /**
     * @param $request
     * @param $created_id
     * @return mixed
     */
    public function centcoinApply($request,$created_id);

    /**
     * @param $request
     * @param $created_id
     * @return mixed
     */
    public function store($request, $created_id);

    /**
     * @param $request
     * @param $created_id
     * @return mixed
     */

    public function operationCoins($request,$created_id);

    /**
     * @param $request
     * @return mixed
     */
    public function statusApply($request);

}
