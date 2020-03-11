<?php

namespace App\Service;

class ApiProcessor
{
    /**
     * @param array $data
     * @return mixed
     */
    public function call(array $data)
    {
        $curl = curl_init();
        $url = sprintf("%s?%s", 'http://www.omdbapi.com', http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result);
    }
}