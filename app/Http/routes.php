<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//地圖
Route::group(['prefix' => 'map'], function () {
    Route::get('/', 'MapController@index')->name('map.index');
});

//會員（須完成信箱驗證）
Route::group(['middleware' => ['auth', 'email']], function () {
    //攤位類型管理
    //權限：type.manage
    Route::resource('type', 'TypeController', [
        'except' => [
            'show',
        ],
    ]);
    //攤位管理
    //權限：booth.manage
    Route::post('booth/{booth}/updateCode', 'BoothController@updateCode')->name('booth.updateCode');
    Route::get('booth/downloadQRCode/{booth?}', 'BoothController@downloadQRCode')->name('booth.downloadQRCode');
    Route::any('booth/data', 'BoothController@anyData')->name('booth.data');
    Route::resource('booth', 'BoothController');
    //網站設定
    //權限：setting.edit
    Route::group(['middleware' => 'permission:setting.manage'], function () {
        Route::resource('setting', 'SettingController', [
            'only' => [
                'index',
                'update',
            ],
        ]);
    });
    //會員管理
    //權限：user.manage、user.view
    Route::resource('user', 'UserController', [
        'except' => [
            'create',
            'store',
        ],
    ]);
    //角色管理
    //權限：role.manage
    Route::group(['middleware' => 'permission:role.manage'], function () {
        Route::resource('role', 'RoleController', [
            'except' => [
                'show',
            ],
        ]);
    });
    //打卡集點相關
    Route::group(['prefix' => 'check'], function () {
        Route::get('/', 'CheckController@getIndex')->name('check.index');
        Route::get('record', 'CheckController@getRecord')->name('check.record');
        //重新綁定模型，並以code作為篩選條件
        Route::model('checkBooth', 'App\Booth');
        Route::bind('checkBooth', function ($code) {
            return \App\Booth::where('code', $code)->first();
        });
        Route::get('{checkBooth}', 'CheckController@getBooth')->name('check.booth');
        Route::post('{checkBooth}', 'CheckController@postBooth')->name('check.booth');
    });
    //打卡集點記錄管理
    Route::group(['middleware' => 'permission:point.manage'], function () {
        Route::get('point/downloadXlsxFile', 'PointController@downloadXlsxFile')->name('point.downloadXlsxFile');
        Route::any('point/data', 'PointController@anyData')->name('point.data');
        Route::resource('point', 'PointController', [
            'except' => [
                'show',
                'edit',
                'update',
            ],
        ]);
    });
    //抽獎券管理
    Route::group(['middleware' => 'permission:ticket.manage'], function () {
        Route::any('ticket/data', 'TicketController@anyData')->name('ticket.data');
        Route::resource('ticket', 'TicketController', [
            'only' => [
                'index',
                'destroy',
            ],
        ]);
    });
    //會員資料
    Route::group(['prefix' => 'profile'], function () {
        //查看會員資料
        Route::get('/', 'ProfileController@getProfile')->name('profile');
        //編輯會員資料
        Route::get('edit', 'ProfileController@getEditProfile')->name('profile.edit');
        Route::put('update', 'ProfileController@updateProfile')->name('profile.update');
    });
});

//會員系統
//麵包屑要求對全部路由命名，因此將 Route::auth() 複製出來自己命名
//Route::auth();
Route::group(['namespace' => 'Auth'], function () {
    // Authentication Routes...
    Route::get('login', 'AuthController@showLoginForm')->name('auth.login');
    Route::post('login', 'AuthController@login')->name('auth.login');
    Route::get('logout', 'AuthController@logout')->name('auth.logout');
    // Registration Routes...
    Route::get('register', 'AuthController@showRegistrationForm')->name('auth.register');
    Route::post('register', 'AuthController@register')->name('auth.register');
    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'PasswordController@showResetForm')->name('auth.password.reset');
    Route::post('password/email', 'PasswordController@sendResetLinkEmail')->name('auth.password.email');
    Route::post('password/reset', 'PasswordController@reset')->name('auth.password.reset');
    //修改密碼
    Route::get('change-password', 'PasswordController@getChangePassword')->name('auth.change-password');
    Route::put('update-password', 'PasswordController@updatePassword')->name('auth.update-password');
    //驗證信箱
    Route::get('resend', 'AuthController@resendConfirmMailPage')->name('auth.resend-confirm-mail');
    Route::post('resend', 'AuthController@resendConfirmMail')->name('auth.resend-confirm-mail');
    Route::get('confirm/{confirmCode}', 'AuthController@emailConfirm')->name('auth.confirm');
});

Route::get('/', function () {
    return view('index');
})->name('index');
