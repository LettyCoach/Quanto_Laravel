<?php
use \App\Http\Controllers\ApiController;
use \App\Http\Controllers\AppApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | API Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register API routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | is assigned the "api" middleware group. Enjoy building your API!
 * |
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// @formatter:off
Route::get('callback/stripe/oauth', [\App\Http\Controllers\ApiController::class, 'callbackStripeOauth']);

Route::get('v1/survey/get/{id}', [\App\Http\Controllers\ApiController::class, 'getSurvey']);
Route::get('v1/answers/get/{id}', [\App\Http\Controllers\ApiController::class, 'getAnswer']);
Route::get('v1/answers/get-list/{qid}', [\App\Http\Controllers\ApiController::class, 'getAnswers']);
Route::get('v1/questions/get/{id}', [\App\Http\Controllers\ApiController::class, 'getQuestion']);
Route::post('v1/client/save', [\App\Http\Controllers\ApiController::class, 'saveAnswers']);
Route::post('v1/client/pdf', [ApiController::class, 'pdf']);
Route::post('v1/client/uploadImg', [ApiController::class, 'uploadImg']);
Route::post('v1/client/uploadImgWithPath', [ApiController::class, 'uploadImgWithPath']);
Route::post('v1/client/uploadImgWithPathes', [ApiController::class, 'uploadImgWithPathes']);
Route::post('v1/client/uploadProfile', [ApiController::class, 'uploadProfile']);
Route::post('v1/client/uploadStamp', [ApiController::class, 'uploadStamp']);


//Auth
Route::post('login', [AppApiController::class, 'login']);
Route::post('register', [AppApiController::class, 'register']);
Route::post('getVerifyCode', [AppApiController::class, 'getVerifyCode']);
Route::post('verifyCode', [AppApiController::class, 'verifyCode']);
Route::post('resetPassword', [AppApiController::class, 'resetPassword']);
Route::post('getUserList', [AppApiController::class, 'getUserList']);

//product
Route::post('products/getList', [AppApiController::class, 'getProductList']);
Route::post('products/getByBarcode', [AppApiController::class, 'getProductByBarcode']);

//SaveItem
Route::post('products/getSaveItemList', [AppApiController::class, 'getSaveItemList']);
Route::post('products/addSaveItem', [AppApiController::class, 'addSaveItem']);

//Invoice Create
Route::post('products/makeInvoice', [AppApiController::class, 'makeInvoice']);

//Follow
Route::post('buyer/getUserList', [AppApiController::class, 'getUserListByBuyer_id']);
Route::post('buyer/setFollow', [AppApiController::class, 'setSupplyerFollow']);
Route::post('buyer/unsetFollow', [AppApiController::class, 'unsetSupplyerFollow']);

Route::post('supplyer/getFollowedList', [AppApiController::class, 'getFollowListBySupplyer_id']);
Route::post('supplyer/setFollowAccept', [AppApiController::class, 'setFollowAccept']);


// @formatter:on
