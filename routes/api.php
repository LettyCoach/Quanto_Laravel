<?php
use \App\Http\Controllers\ApiController;
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
Route::get('callback/stripe/oauth', [\App\Http\Controllers\ApiController::class,'callbackStripeOauth']);

Route::get('v1/survey/get/{id}', [\App\Http\Controllers\ApiController::class,'getSurvey']);
Route::get('v1/answers/get/{id}', [\App\Http\Controllers\ApiController::class,'getAnswer']);
Route::get('v1/answers/get-list/{qid}', [\App\Http\Controllers\ApiController::class,'getAnswers']);
Route::get('v1/questions/get/{id}', [\App\Http\Controllers\ApiController::class,'getQuestion']);
Route::post('v1/client/save', [\App\Http\Controllers\ApiController::class,'saveAnswers']);
Route::post('v1/client/pdf', [ApiController::class, 'pdf']);
Route::post('v1/client/uploadImg', [ApiController::class, 'uploadImg']);

Route::get('products/getByBarcode/{barcode}', function($barcode) {
    return response()->json([
        'name' => 'shirt',
        'price' => '',
        'detail' => $barcode,
        'amount' => '100',
        'category' => 'clothes',
        'option' => '',
        'other' => '',
        'img_url' => 'uploads/questions/33/cristiano-ronaldo-juventus-2019-20_rb0vgy1nfj8o11veyj09m1hn4.jpg',

    ]);
});

// @formatter:on