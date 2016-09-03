<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OAuthController extends Controller
{
    public function index()
    {
        //檢查設定
        $OAuthUrl = env('FCU_API_URL_OAUTH');
        $clientId = env('FCU_API_CLIENT_ID');
        $clientUrl = env('FCU_API_CLIENT_URL');
        if (!$OAuthUrl || !$clientId || !$clientUrl) {
            return back()->with('warning', '目前未開放');
        }
        $data = [
            'client_id'  => $clientId,
            'client_url' => $clientUrl,
        ];
        //重導向到OAuth登入頁面
        $redirectUrl = $OAuthUrl . '?' . http_build_query($data);

        return redirect($redirectUrl);
    }

    public function login(Request $request)
    {
        $userCode = $request->get('user_code');
        if (!$userCode) {
            return redirect()->route('index')->with('warning', '登入失敗');
        }

        dd($request, $request->all());
        //TODO: 利用User Code取得學號
        //TODO: 嘗試找出使用者
    }
}
