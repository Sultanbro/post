<?php

namespace App\Http\Services\Email;

interface EmailServiceInterface
{
    /**
     * @param $params
     * @return mixed
     */
    public function sendResetPasswordEmail($params);

    /**
     * @param $file
     * @return mixed
     */
    public function saveFile($file);
}
