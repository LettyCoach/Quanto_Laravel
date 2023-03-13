<?php
use \App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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
// Route::get('callback/stripe/oauth', [\App\Http\Controllers\ApiController::class, 'callbackStripeOauth']);

// Route::get('v1/survey/get/{id}', [\App\Http\Controllers\ApiController::class, 'getSurvey']);
// Route::get('v1/answers/get/{id}', [\App\Http\Controllers\ApiController::class, 'getAnswer']);
// Route::get('v1/answers/get-list/{qid}', [\App\Http\Controllers\ApiController::class, 'getAnswers']);
// Route::get('v1/questions/get/{id}', [\App\Http\Controllers\ApiController::class, 'getQuestion']);
// Route::post('v1/client/save', [\App\Http\Controllers\ApiController::class, 'saveAnswers']);
Route::post('v1/client/pdf', [ApiController::class, 'pdf']);
Route::post('v1/client/uploadImg', [ApiController::class, 'uploadImg']);
Route::post('v1/client/uploadImgWithPath', [ApiController::class, 'uploadImgWithPath']);


Route::post('register', function(Request $request) {
    // print_r($request->all());
    return response()->json([
        'status' => 'success',
    ]);
});

Route::post('login', function (Request $request) {

    // print_r($request->json()->all());
    return response()->json([
        'id' => 2,
        'name' => 'Papiko',
        'full_name' => 'サンプル株式会社',
        'email' => 'laneandyumiko@gmail.com',
        'zip_code' => '157-0399',
        'address' => '東京都渋谷区渋谷1-1-1',
        'phone_number' => '03-0123-4567',
        'profile_url' => 'https://quanto3.com/uploads/users/Free-Logo-Maker-Get-Custom-Logo-Designs-in-Minutes-Looka_(11).png',
    ]);
});

Route::get('password/reset', function () {

    Mail::send([], [], function ($message) {
        $message->to('laneandyumiko@gmail.com')
            ->subject('Reset Password')
            ->setBody('<h1>0123</h1>', 'text/html');
    });
});

Route::post('products/makeInvoice', function (Request $request) {

    // print_r($request->json()->all());
    return response()->json([
        'name' => 'invoice',
        'pdf_url' => 'uploads/products/sample01.pdf',
    ]);
});

Route::get('products/getByBarcode/{barcode}', function ($barcode) {
    return response()->json([
        'name' => 'shirt',
        'price' => '50',
        'detail' => $barcode,
        'barcode' => $barcode,
        'amount' => '100',
        'category' => 'clothes',
        'option' => '',
        'other' => '',
        'img_url' => 'uploads/answers/480/1044246_h1_001.jpg',
    ]);
});

Route::post('products/makeInvoiceTest', [ApiController::class, 'makeInvoice']);


// @formatter:on