<?php

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\ItineraryController;
use App\Http\Controllers\Admin\LandmarkController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TermsAndConditionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuotationController;
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

Auth::routes(['verify' => true]);

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return view('auth.login');
    }
})->name('index');

Route::get('/list/cities', [CommonController::class, 'allCities']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::group(['middleware' => ['auth']], function () {

    Route::resources(['/landmarks' => LandmarkController::class]);
    Route::resources(['/operators' => OperatorController::class]);
    Route::resources(['/inquiries' => InquiryController::class]);
    Route::resources(['/cities' => CityController::class]);
    Route::resources(['/hotels' => HotelController::class]);
    Route::resources(['/templates' => TermsAndConditionController::class]);
    Route::resources(['/itinerary-templates' => ItineraryController::class]);
    Route::post('/create-new-operator', [OperatorController::class, 'create_new_operator'])->name('create-new-operator');

    //Rehman
    Route::get('/create-quotation-invoice-pdf/{id}', [QuotationController::class, 'createQuotationPDFInvoice'])->name('create-quotation-invoice-pdf');
    Route::get('/create-quotation-template-modal', [InquiryController::class, 'createQuotationTemplateModal'])->name('create-quotation-template-modal');
    Route::get('/list-quotation-templates', [InquiryController::class, 'listQuotationTemplates'])->name('list-quotation-templates');
    Route::get('/create-template-quotation/{id}', [QuotationController::class, 'createQuotationFromTemplate'])->name('create-template-quotation');

    Route::post('save-itinerary-detail', [ItineraryController::class, 'saveItineraryDetail'])->name('save-itinerary-detail');
    Route::get('/edit-itinerary-templates-detail/{id}', [ItineraryController::class, 'EditItineraryTemplateDetail'])->name('edit-itinerary-templates-detail');
    Route::get('/delete-itinerary-templates-detail/{id}', [ItineraryController::class, 'DeleteItineraryTemplateDetail'])->name('delete-itinerary-templates-detail');

    //Rehman
    Route::get('profile', [CommonController::class, 'profile'])->name('profile');
    Route::post('/update-profile/{id}', [UserController::class, 'update_user_profile'])->name('update-profile');
    Route::get('/open-bank-modal', [UserController::class, 'open_bank_modal'])->name('open-bank-modal');
    Route::post('/add-new-detail', [UserController::class, 'add_bank_details'])->name('add-new-detail');
    Route::get('/edit-bank-modal/{id}', [UserController::class, 'edit_bank_detail'])->name('edit-bank-modal');
    Route::post('/update-bank-detail/{id}', [UserController::class, 'update_bank_deatil'])->name('update-bank-detail');

    Route::resources(['roles' => RoleController::class]);
    Route::resources(['permissions' => PermissionController::class]);
    Route::resources(['staffs' => UserController::class]);

    Route::get('/staff-profile/{id}', [UserController::class, 'edit'])->name('staff-profile');
    Route::get('/roles-permission-assignment-list', [UserController::class, 'userRolesPermissionList'])->name('roles-permission-assignment-list');
    Route::get('edit-with-role-permissions/{id}', [UserController::class, 'editUserRolesPermissions'])->name('edit-with-role-permissions');
    Route::post('assign-role-permissions/{id}', [UserController::class, 'updateUserRolesPermissions'])->name('assign-role-permissions');

    //Quotation routes

    Route::get('/quotations', [QuotationController::class, 'index'])->name('quotations');
    Route::get('/quotations/create', [QuotationController::class, 'create'])->name('quotation-save');
    Route::post('/quotations/save', [QuotationController::class, 'saveBasicInformation'])->name('quotation-store');
    Route::get('/quotations/{id}', [QuotationController::class, 'edit'])->name('quotation-edit');
    Route::get('/itinerary-list-modal', [QuotationController::class, 'itineraryListModal'])->name('itinerary-list-modal');
    Route::get('/add-quotation-itinerary-modal', [QuotationController::class, 'addQuotationItineraryModal'])->name('add-quotation-itinerary-modal');
    Route::post('/add-quotation-itinerary', [QuotationController::class, 'addQuotationItinerary'])->name('add-quotation-itinerary');
    Route::delete('/remove-quotation-itinerary/{id}', [QuotationController::class, 'removeQuotationItinerary'])->name('remove-quotation-itinerary');
    Route::get('/landmarks/suggestions/{city}/{type}', [LandmarkController::class, 'landmarkSuggestions'])->name('landmarks-suggestions');

    Route::get('/add-quotation-hotel-modal', [QuotationController::class, 'addQuotationHotelModal'])->name('add-quotation-hotel-modal');
    Route::get('/add-quotation-meal-modal', [QuotationController::class, 'addQuotationMealModal'])->name('add-quotation-meal-modal');
    Route::get('/add-quotation-transport-modal', [QuotationController::class, 'addQuotationTransportModal'])->name('add-quotation-transport-modal');
    Route::get('/add-quotation-activity-modal', [QuotationController::class, 'addQuotationActivityModal'])->name('add-quotation-activity-modal');
    Route::get('/add-quotation-policies-modal', [QuotationController::class, 'addQuotationPoliciesModal'])->name('add-quotation-policies-modal');

    // Route::get('/add-quotation-itinerary-modal', [QuotationController::class, 'addQuotationItineraryModal'])->name('add-quotation-itinerary-modal');
    Route::post('/save-quotation-itinerary', [QuotationController::class, 'saveQuotationItinerary'])->name('save-quotation-itinerary');
    Route::post('/save-quotation-hotel', [QuotationController::class, 'saveQuotationHotel'])->name('save-quotation-hotel');
    Route::post('/save-quotation-hotel', [QuotationController::class, 'saveQuotationHotel'])->name('save-quotation-hotel');
    Route::post('/save-quotation-meal', [QuotationController::class, 'saveQuotationMeal'])->name('save-quotation-meal');
    Route::post('/save-quotation-transport', [QuotationController::class, 'saveQuotationTransport'])->name('save-quotation-transport');
    Route::post('/save-quotation-activity', [QuotationController::class, 'saveQuotationActivity'])->name('save-quotation-activity');
    Route::post('/save-quotation-policy', [QuotationController::class, 'saveQuotationPolicy'])->name('save-quotation-policy');
    Route::post('/save-quotation', [QuotationController::class, 'saveQuotation'])->name('save-quotation');
    Route::post('/save-quotation-invoice', [QuotationController::class, 'saveQuotationInvoice'])->name('save-quotation-invoice');

    Route::post('/save-quotation-service-types', [QuotationController::class, 'saveQuotationServiceTypes'])->name('save-quotation-service-types');
    Route::post('/save-quotation-notes', [QuotationController::class, 'saveQuotationNotesTypes'])->name('save-quotation-notes');
    Route::post('/quotation-image-upload', [QuotationController::class, 'saveQuotationImage'])->name('quotation-image-upload');
    Route::get('/list-quotation-images', [QuotationController::class, 'listQuotationImage'])->name('list-quotation-images');


    Route::delete('/remove-quotation-hotel/{id}', [QuotationController::class, 'deleteQuotationHotel'])->name('remove-quotation-hotel');
    Route::delete('/remove-quotation-service/{id}', [QuotationController::class, 'deleteQuotationService'])->name('remove-quotation-service');
    Route::delete('/remove-quotation-note/{id}', [QuotationController::class, 'deleteQuotationNote'])->name('remove-quotation-note');
    Route::delete('/remove-quotation-image/{id}', [QuotationController::class, 'deleteQuotationImage'])->name('remove-quotation-image');


    Route::post('/change-quotation-status', [QuotationController::class, 'changeQuotationStatus'])->name('change-quotation-status');






    Route::get('/existing/update/quotations', [QuotationController::class, 'updateLiveQuotationLink']);
    Route::get('/delete/test/quotations', [QuotationController::class, 'deleteTestQuotations']);
    Route::get('/quotations/templates', [QuotationController::class, 'quotationTemplates']);
    Route::get('/quotations/order/{id}', [QuotationController::class, 'convertQuotation']);
    // Route::get('/quotations/all-responses', [QuotationController::class, 'getAllResponses']);

    Route::post('/quotations/invoice/{id}/{version}', [QuotationController::class, 'invoice']);
    Route::post('/quotations/sendInvoice/{id}/{version}/{amount}', [QuotationController::class, 'sendInvoice']);
    Route::post('/quotations/emailInvoice/{id}', [QuotationController::class, 'emailInvoice']);
    Route::post('/quotations/{id}/{version}/itinerary', [QuotationController::class, 'saveItineraryRow']);
    Route::get('/quotations/invoices/{id}', [QuotationController::class, 'invoicesAll']);
    Route::get('/quotations/approved/{id}', [QuotationController::class, 'approvedVersion']);
    Route::post('/invoices/status/{id}', [QuotationController::class, 'updateInvoiceStatus']);
    Route::post('/quotations/getInvoices/{id}', [QuotationController::class, 'getInvoices']);
    Route::post('/quotations/approveInvoice/{id}', [QuotationController::class, 'approveInvoices']);
    Route::post('/quotations/revokeInvoice/{id}', [QuotationController::class, 'revokeInvoices']);
    Route::get('/quotations/pdf/{id}/{showPrice}/{showPriceItems}', [QuotationController::class, 'emailPDF']);

    Route::get('/quotations/details/{id}', [QuotationController::class, 'getQuotationData']);
    Route::post('/quotations/response', [QuotationController::class, 'submitQuotationResponse']);
    Route::post('/quotations/chat', [QuotationController::class, 'submitQuotationChat']);
    Route::post('/quotations/response/update', [QuotationController::class, 'updateQuotationResponse']);
    Route::get('/quotations/chat/{id}', [QuotationController::class, 'getQuotationChat']);
    Route::post('/quotations/changeStatus', [QuotationController::class, 'changeStatus']);

    // Route::get('/quotations/response/details/{id}', [QuotationController::class, 'getQuotationResponseDetails']);
    Route::get('/quotations/view/{id}/{version}', [QuotationController::class, 'show']);
    Route::post('/quotations/update/{id}/{version}', [QuotationController::class, 'update']);
    Route::get('/quotations/photos/{id}/{version}', [QuotationController::class, 'images']);
    Route::post('/quotations/addExtraMarkup', [QuotationController::class, 'addExtraMarkup']);
    Route::get('/quotations/getExtraMarkup/{quotationId}', [QuotationController::class, 'getExtraMarkup']);
    Route::delete('quotations/{id}/{version}', [QuotationController::class, 'removeQuotation']);

    Route::post('/quotations/photos/{id}/{version}', [QuotationController::class, 'quotationImages']);
    Route::delete('/quotations/photos/delete/{id}', [QuotationController::class, 'deleteImage']);

    //Quotation Response routes by mirza

    Route::get('/quotations-responses', [QuotationController::class, 'showResponses'])->name('quotations-responses');
    Route::get('/quotations-response-details/{id}', [QuotationController::class, 'getQuotationResponseDetails'])->name('quotations-response-details');
    Route::get('/quotations-chat/{id}', [QuotationController::class, 'getQuotationChat'])->name('quotations-chat');
    Route::post('/quotations-send-chat', [QuotationController::class, 'submitQuotationChat'])->name('quotations-send-chat');
    //Quotation routes ends


});
