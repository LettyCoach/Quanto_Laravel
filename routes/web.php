<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\frontendController;
use App\Http\Controllers\LPController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RandomController;
use App\Http\Controllers\ReferralInfoController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProductCategoryController;
use App\Http\Controllers\UserProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/test', [RandomController::class, 'index']);
Route::get('/', [SurveyController::class,'index'])->name('admin.surveys');

Route::get('/profile', [ProfileController::class,'index'])->name('profile');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::post('/profile/purpose', [ProfileController::class, 'purpose']);
Route::post('/profile/payment_method', [ProfileController::class, 'payment_method']);
Route::post('/profile/member', [ProfileController::class, 'member']);

Route::get('/admin', [SurveyController::class,'index'])->name('admin.surveys');

Route::get('/admin/users', [UserController::class,'index'])->name('admin.users');
Route::get('/admin/user/edit/{id}', [UserController::class,'edit'])->name('admin.user.edit');
Route::get('/admin/user/delete/{id}', [UserController::class,'delete'])->name('admin.user.delete');
Route::post('/admin/user/save', [ClientController::class,'save'])->name('admin.user.save');
Route::post('admin/user/upload', [UserController::class,'upload'])->name('admin.user.upload');

Route::get('/admin/clients', [ClientController::class,'index'])->name('admin.clients');
Route::get('/admin/client/show/{id}', [ClientController::class,'show'])->name('admin.client.show');
Route::post('/admin/client/sendMail', [ClientController::class,'clientSendMail'])->name('admin.client.sendMail');

Route::get('/admin/lps', [LPController::class,'index'])->name('admin.lps');
Route::get('/admin/lp/add', [LPController::class,'add'])->name('admin.lp.add');
Route::get('/admin/lp/delete/{id}', [LPController::class,'delete'])->name('admin.lp.delete');
Route::post('/admin/lp/save', [LPController::class,'save'])->name('admin.lp.save');
Route::get('/admin/lp/edit/{id}', [LPController::class,'edit'])->name('admin.lp.edit');
Route::post('admin/lp/upload', [LPController::class,'upload'])->name('admin.lp.upload');
Route::post('admin/lp/content', [LPController::class,'content'])->name('admin.lp.content');
Route::post('admin/lp/contentd', [LPController::class,'contentDelete'])->name('admin.lp.contentd');

Route::get('/admin/surveys', [SurveyController::class,'index'])->name('admin.surveys');
Route::get('/admin/survey/add', [SurveyController::class,'add'])->name('admin.survey.add');
Route::get('/admin/survey/delete/{id}', [SurveyController::class,'delete'])->name('admin.survey.delete');
Route::post('/admin/survey/save', [SurveyController::class,'save'])->name('admin.survey.save');
Route::get('/admin/survey/edit/{id}', [SurveyController::class,'edit'])->name('admin.survey.edit');
Route::get('/admin/formularSetting', [SurveyController::class,'formularSetting'])->name('admin.formularSetting');
Route::post('/admin/formularSetting/save', [SurveyController::class,'formularSave'])->name('admin.formularSetting.save');


Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
Route::post('/admin/orders/get', [OrderController::class, 'get'])->name('admin.order-details');
Route::post('/admin/orders/update', [OrderController::class, 'update'])->name('admin.order-update');
Route::post('/admin/comments/get', [OrderController::class, 'getComment'])->name('admin.get-comments');
Route::post('/admin/comments/update', [OrderController::class, 'updateComment'])->name('admin.update-comments');


Route::get('/admin/referralInfo', [ReferralInfoController::class,'index'])->name('admin.referralInfo');
Route::get('/admin/referralInfo/add', [ReferralInfoController::class,'add'])->name('admin.referralInfo.add');
Route::get('/admin/referralInfo/delete/{id}', [ReferralInfoController::class,'delete'])->name('admin.referralInfo.delete');
Route::post('/admin/referralInfo/save', [ReferralInfoController::class,'save'])->name('admin.referralInfo.save');
Route::get('/admin/referralInfo/edit/{id}', [ReferralInfoController::class,'edit'])->name('admin.referralInfo.edit');

///User Product Category
Route::get('/admin/userProductCategories', [UserProductCategoryController::class,'index'])->name('admin.userProductCategories');
Route::get('/admin/userProductCategory/create', [UserProductCategoryController::class,'create'])->name('admin.userProductCategory.create');
Route::get('/admin/userProductCategory/edit/{id}', [UserProductCategoryController::class,'edit'])->name('admin.userProductCategory.edit');
Route::post('/admin/userProductCategory/save', [UserProductCategoryController::class,'save'])->name('admin.userProductCategory.save');
Route::get('/admin/userProductCategory/delete/{id}', [UserProductCategoryController::class,'delete'])->name('admin.userProductCategory.delete');

///User Product 
Route::get('/admin/userProducts', [UserProductController::class,'index'])->name('admin.userProducts');
Route::get('/admin/userProduct/create', [UserProductController::class,'create'])->name('admin.userProdut.create');
Route::get('/admin/userProduct/delete/{id}', [UserProductController::class,'delete'])->name('admin.userProdut.delete');
Route::post('/admin/userProduct/save', [UserProductController::class,'save'])->name('admin.userProdut.save');
Route::get('/admin/userProduct/edit/{id}', [UserProductController::class,'edit'])->name('admin.userProdut.edit');


//Frontend
Route::get('/show/{id}', [frontendController::class,'index'])->name('frontend.index')->withoutMiddleware(['auth']);
Route::get('/thank-you/{id}', [frontendController::class, 'thanks'])->name('frontend.thanks')->withoutMiddleware(['auth']);
Route::get('/mypage/{id}', [frontendController::class, 'mypage'])->name('frontend.mypage')->withoutMiddleware(['auth']);
Route::post('/show/cart', [frontendController::class,'createCart'])->name('frontend.createCart')->withoutMiddleware(['auth']);
Route::post('/show/update', [frontendController::class, 'updateCart'])->name('frontend.updateCart')->withoutMiddleware(['auth']);
Route::get('/checkout', [frontendController::class,'checkout'])->name('frontend.checkout')->withoutMiddleware(['auth']);
Route::post('/update', [frontendController::class, 'updateCheckout'])->name('frontend.updateCheckout')->withoutMiddleware(['auth']);
Route::post('/stripe', [StripePaymentController::class,'stripePost'])->name('stripe.post')->withoutMiddleware(['auth']);
Route::post('/customer/login', [frontendController::class, 'login'])->name('frontend.login')->withoutMiddleware(['auth']);
Route::post('/customer/forget', [frontendController::class, 'forget'])->name('frontend.forget')->withoutMiddleware(['auth']);
Route::get('/customer/reset/{id}', [frontendController::class, 'reset'])->name('frontend.reset')->withoutMiddleware(['auth']);
Route::post('/customer/password', [frontendController::class, 'password'])->name('frontend.password')->withoutMiddleware(['auth']);
Route::post('/customer/register', [frontendController::class, 'register'])->name('frontend.register')->withoutMiddleware(['auth']);
Route::post('/customer/logout', [frontendController::class, 'logout'])->name('frontend.logout')->withoutMiddleware(['auth']);
Route::post('/customer/address', [frontendController::class, 'address'])->name('frontend.address')->withoutMiddleware(['auth']);
Route::post('/customer/card', [frontendController::class, 'card'])->name('frontend.card')->withoutMiddleware(['auth']);
Route::post('/customer/postcode', [frontendController::class, 'postcode'])->name('frontend.postcode')->withoutMiddleware(['auth']);
Route::post('/customer/info', [frontendController::class, 'info'])->name('frontend.info')->withoutMiddleware(['auth']);
Route::post('/customer/get', [frontendController::class, 'get'])->name('frontend.get')->withoutMiddleware(['auth']);


Route::get('/lp/{id}', [LPController::class,'show'])->name('frontend.lp')->withoutMiddleware(['auth']);


//product manage
