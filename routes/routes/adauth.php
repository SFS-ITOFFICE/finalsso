<?php

use App\Http\Controllers\AdauthController;

// https://finalsso.sfs.or.kr/simplesaml/saml2/idp/SSOService.php

Route::get('/simplesaml/saml2/idp/SSOService.php', [AdauthController::class, 'create']);

Route::group(['prefix' => '/adauth'], function () {
    // test
    Route::get('test', [AdauthController::class, 'test'])->name('adauth.test');

    /* 사용자 인증 */
    Route::get('login/{returl?}', [AdauthController::class, 'create'])->name('adauth.login');
    Route::post('login', [AdauthController::class, 'store'])->name('adauth.store');

    Route::get('logout/{returl?}', [AdauthController::class, 'destroy'])->name('adauth.logout');

    Route::get('loginforce/{staffid}/{retsite}', [AdauthController::class, 'loginforce']);
});

