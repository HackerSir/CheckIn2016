<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class FcuApiService
{
    /**
     * @param string $nid
     * @return array|null
     */
    public function getStuInfo($nid)
    {
        $nid = strtoupper($nid);
        if (empty(trim($nid))) {
            return null;
        }
        //API資訊
        $apiUrl = env('FCU_API_URL_GET_STU_INFO');
        $apiClientId = env('FCU_API_CLIENT_ID');
        if (!$apiUrl || !$apiClientId) {
            return null;
        }
        //請求
        $client = new Client();
        $option = [
            'verify' => false,
            'query'  => [
                'client_id' => $apiClientId,
                'id'        => $nid,
            ],
        ];
        try {
            //送出請求並取得結果
            $response = $client->request('GET', $apiUrl, $option);
        } catch (ClientException $e) {
            //忽略例外
            $response = $e->getResponse();
        }
        //回應
        $responseStatusCode = $response->getStatusCode();
        $responseJson = json_decode($response->getBody());
        //若無法轉成json，表示未順利連上API（API可能回應404，因此無法透過ResponseStatusCode判斷連線成功失敗）
        if (!$responseJson) {
            return null;
        }
        if (!isset($responseJson->UserInfo[0]->status) || $responseJson->UserInfo[0]->status != 1) {
            return null;
        }
        try {
            $userInfo = (array) $responseJson->UserInfo[0];
        } catch (Exception $e) {
            return null;
        }

        return $userInfo;
    }

    public function getLoginUser($userCode)
    {
        if (empty(trim($userCode))) {
            return null;
        }
        //API資訊
        $apiUrl = env('FCU_API_URL_GET_LOGIN_USER');
        $apiClientId = env('FCU_API_CLIENT_ID');
        if (!$apiUrl || !$apiClientId) {
            return null;
        }
        //請求
        $client = new Client();
        $option = [
            'verify' => false,
            'query'  => [
                'client_id' => $apiClientId,
                'user_code' => $userCode,
            ],
        ];
        try {
            //送出請求並取得結果
            //FIXME: Class 'Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory' not found
            $response = $client->request('GET', $apiUrl, $option);
        } catch (ClientException $e) {
            //忽略例外
            $response = $e->getResponse();
        }
        //回應
        $responseStatusCode = $response->getStatusCode();
        $responseJson = json_decode($response->getBody());
        //若無法轉成json，表示未順利連上API（API可能回應404，因此無法透過ResponseStatusCode判斷連線成功失敗）
        if (!$responseJson) {
            return null;
        }
        if (!isset($responseJson->UserInfo[0]->status) || $responseJson->UserInfo[0]->status != 1) {
            return null;
        }
        try {
            $userInfo = (array) $responseJson->UserInfo[0];
        } catch (Exception $e) {
            return null;
        }

        return $userInfo;
    }
}
