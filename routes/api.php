<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('user', [\App\Http\Controllers\AuthController::class, 'getUser']);
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('user/{id}/update', [\App\Http\Controllers\AuthController::class, 'update']);
    Route::post('user/{id}/get', [\App\Http\Controllers\User\UserController::class, 'get']);
    Route::post('user/{id}/update/password', [\App\Http\Controllers\AuthController::class, 'updatePassword']);

    Route::post('employee/all', [\App\Http\Controllers\Employee\EmployeeController::class, 'all']);
    Route::post('employee/list', [\App\Http\Controllers\Employee\EmployeeController::class, 'list']);
    Route::post('employee/names', [\App\Http\Controllers\Employee\EmployeeController::class, 'getName']);
    Route::post('employee/{id}/get', [\App\Http\Controllers\Employee\EmployeeController::class, 'get']);
    Route::post('employee/status/all', [\App\Http\Controllers\Employee\EmployeeStatusController::class, 'all']);
    Route::post('employee/store', [\App\Http\Controllers\Employee\EmployeeController::class, 'store']);
    Route::post('employee/{id}/update', [\App\Http\Controllers\Employee\EmployeeController::class, 'update']);
    Route::post('employee/{id}/delete', [\App\Http\Controllers\Employee\EmployeeController::class, 'destroy']);
    Route::post('employee/birthday/month', [\App\Http\Controllers\Employee\EmployeeController::class, 'birthday']);

    Route::post('application/all', [\App\Http\Controllers\Application\ApplicationController::class, 'all']);
    Route::post('application/list', [\App\Http\Controllers\Application\ApplicationController::class, 'list']);
    Route::post('application/list/new', [\App\Http\Controllers\Application\ApplicationController::class, 'countNew']);
    Route::post('application/{id}/get', [\App\Http\Controllers\Application\ApplicationController::class, 'get']);
    Route::post('application/store', [\App\Http\Controllers\Application\ApplicationController::class, 'store']);
    Route::post('application/category/all', [\App\Http\Controllers\Application\ApplicationCategoryController::class, 'all']);
    Route::post('application/{id}/update', [\App\Http\Controllers\Application\ApplicationController::class, 'update']);
    Route::post('application/{id}/delete', [\App\Http\Controllers\Application\ApplicationController::class, 'destroy']);
    Route::get('application/send/mail', [\App\Http\Controllers\MailController::class, 'application_create']);
    Route::post('application/{id}/comment/send', [\App\Http\Controllers\Application\ApplicationController::class, 'comment']);

    Route::post('application/files/store', [\App\Http\Controllers\Application\ApplicationFileController::class, 'store']);

    Route::post('event/all', [\App\Http\Controllers\MainEvent\MainEventController::class, 'allEvents']);
    Route::post('event/get', [\App\Http\Controllers\MainEvent\MainEventController::class, 'getEvents']);
    Route::post('event/{id}/info', [\App\Http\Controllers\MainEvent\MainEventController::class, 'get']);
    Route::post('event/{id}/info/update', [\App\Http\Controllers\MainEvent\MainEventController::class, 'update']);
    Route::post('event/store', [\App\Http\Controllers\MainEvent\MainEventController::class, 'store']);
    Route::post('event/{id}/delete', [\App\Http\Controllers\MainEvent\MainEventController::class, 'deleteEventsEmployee']);
    Route::post('event/employee/all', [\App\Http\Controllers\MainEvent\MainEventController::class, 'allEventsEmployee']);
    Route::post('event/employee/get', [\App\Http\Controllers\MainEvent\MainEventController::class, 'getEventsEmployee']);
    Route::post('event/status/all', [\App\Http\Controllers\MainEvent\MainEventController::class, 'statuses']);

    Route::post('feed/all', [\App\Http\Controllers\Feed\FeedController::class, 'all']);
    Route::post('feed/list', [\App\Http\Controllers\Feed\FeedController::class, 'list']);
    Route::post('feed/store', [\App\Http\Controllers\Feed\FeedController::class, 'store']);
    Route::post('feed/{id}/get', [\App\Http\Controllers\Feed\FeedController::class, 'get']);
    Route::post('feed/{id}/update', [\App\Http\Controllers\Feed\FeedController::class, 'update']);
    Route::post('feed/{id}/delete', [\App\Http\Controllers\Feed\FeedController::class, 'destroy']);
    Route::post('feed/category', [\App\Http\Controllers\Feed\FeedController::class, 'category']);

    Route::post('message/send/feed', [\App\Http\Controllers\Feed\FeedController::class, 'sendMessage']);
    Route::post('message/delete/feed/{id}', [\App\Http\Controllers\Feed\FeedController::class, 'deleteMessage']);
    Route::post('message/get/feed', [\App\Http\Controllers\Feed\FeedController::class, 'getMessage']);

    Route::post('news/list', [\App\Http\Controllers\News\NewsController::class, 'list']);
    Route::post('news/all', [\App\Http\Controllers\News\NewsController::class, 'all']);
    Route::post('news/store', [\App\Http\Controllers\News\NewsController::class, 'store']);
    Route::post('news/{id}/get', [\App\Http\Controllers\News\NewsController::class, 'get']);
    Route::post('news/{id}/update', [\App\Http\Controllers\News\NewsController::class, 'update']);
    Route::post('news/viewer/store', [\App\Http\Controllers\News\NewsController::class, 'storeViewer']);

    Route::post('docs/type/all', [\App\Http\Controllers\DocsTypeController::class, 'all']);
    Route::post('docs/agency/all', [\App\Http\Controllers\DocsAgencyController::class, 'all']);

    Route::post('docs/template/store', [\App\Http\Controllers\DocsTemplate\DocsTemplateController::class, 'store']);
    Route::post('docs/template/list', [\App\Http\Controllers\DocsTemplate\DocsTemplateController::class, 'list']);
    Route::post('docs/template/{id}/update', [\App\Http\Controllers\DocsTemplate\DocsTemplateController::class, 'uploadDoc']);
    Route::post('docs/template/{id}/get', [\App\Http\Controllers\DocsTemplate\DocsTemplateController::class, 'get']);
    Route::post('docs/template/{id}/library', [\App\Http\Controllers\DocsTemplate\DocsTemplateController::class, 'library']);
    Route::post('docs/template/{id}/delete', [\App\Http\Controllers\DocsTemplate\DocsTemplateController::class, 'destroy']);

    Route::post('docs/regulation/store', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'store']);
    Route::post('docs/regulation/list', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'list']);
    Route::post('docs/regulation/{id}/update', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'update']);
    Route::post('docs/regulation/file/{id}/delete', [\App\Http\Controllers\DocsRegulation\DocsRegulationFilesController::class, 'destroy']);
    Route::post('docs/regulation/{id}/update/docs', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'updateDocs']);
    Route::post('docs/regulation/viewer/store', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'storeViewer']);

    Route::post('docs/regulation/{id}/get', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'get']);
    Route::post('docs/regulation/{id}/library', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'library']);
    Route::post('docs/regulation/{id}/delete', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'destroy']);
    Route::post('docs/regulation/{id}/image/docs/get', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'getDocsImage']);

    Route::post('docs/trust/list', [\App\Http\Controllers\DocsTrust\DocsTrustController::class, 'list']);
    Route::post('docs/trust/store', [\App\Http\Controllers\DocsTrust\DocsTrustController::class, 'store']);
    Route::post('docs/trust/{id}/delete', [\App\Http\Controllers\DocsTrust\DocsTrustController::class, 'destroy']);
    Route::post('docs/trust/{id}/get', [\App\Http\Controllers\DocsTrust\DocsTrustController::class, 'get']);
    Route::post('docs/trust/{id}/history', [\App\Http\Controllers\DocsTrust\DocsTrustController::class, 'history']);
    Route::post('docs/trust/{id}/update', [\App\Http\Controllers\DocsTrust\DocsTrustController::class, 'update']);

    Route::post('docs/contract/store', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'store']);
    Route::post('docs/contract/list', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'list']);
    Route::post('docs/contract/{id}/delete', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'delete']);
    Route::post('docs/contract/{id}/get', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'get']);
    Route::post('docs/contract/{id}/update', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'update']);
    Route::get('export/docs/contract', [\App\Http\Controllers\ExportController::class, 'docs_contract_export']);
    Route::post('agent/list', [\App\Http\Controllers\AgentController::class, 'list']);
    Route::post('agent/store', [\App\Http\Controllers\AgentController::class, 'store']);

    Route::post('docs/contract/payment/store', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'storePayment']);
    Route::post('docs/contract/payment/{id}/get', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'getPayment']);
    Route::post('docs/contract/{id}/additional/get', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'getAdditional']);
    Route::post('docs/contract/additional/{id}/delete', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'deleteAdditional']);

    Route::post('docs/contract/payment/{id}/delete', [\App\Http\Controllers\DocsContract\DocsContractController::class, 'deletePayment']);

    Route::post('polling/store', [\App\Http\Controllers\Polling\PollingController::class, 'store']);
    Route::post('polling/list', [\App\Http\Controllers\Polling\PollingController::class, 'list']);
    Route::post('polling/comment', [\App\Http\Controllers\Polling\PollingController::class, 'comments']);
    Route::post('polling/comment/store', [\App\Http\Controllers\Polling\PollingController::class, 'storeComment']);

    Route::post('polling/{id}/get', [\App\Http\Controllers\Polling\PollingController::class, 'get']);
    Route::post('polling/{id}/update', [\App\Http\Controllers\Polling\PollingController::class, 'update']);
    Route::post('polling/{id}/delete', [\App\Http\Controllers\Polling\PollingController::class, 'destroy']);

    Route::post('polling/choice/store', [\App\Http\Controllers\Polling\PollingChoiceController::class, 'store']);
    Route::post('polling/last', [\App\Http\Controllers\Polling\PollingController::class, 'last']);
    Route::post('polling/{id}/count', [\App\Http\Controllers\Polling\PollingController::class, 'choiceCount']);

    Route::post('department/all', [\App\Http\Controllers\DepartmentController::class, 'all']);
    Route::post('department/{id}/employee', [\App\Http\Controllers\DepartmentController::class, 'getEmployee']);

    Route::post('statuses/all', [\App\Http\Controllers\StatusController::class, 'all']);
    Route::post('profession/all', [\App\Http\Controllers\ProfessionController::class, 'all']);

    Route::get('export/employee', [\App\Http\Controllers\ExportController::class, 'employee_export']);

    Route::post('gallery/store', [\App\Http\Controllers\Gallery\GalleryController::class, 'store']);
    Route::post('gallery/{catalog_id}/get', [\App\Http\Controllers\Gallery\GalleryController::class, 'get']);
    Route::post('gallery/catalog', [\App\Http\Controllers\Gallery\GalleryCatalogController::class, 'all']);
    Route::post('gallery/catalog/store', [\App\Http\Controllers\Gallery\GalleryCatalogController::class, 'store']);
    Route::post('gallery/{catalog_id}/delete', [\App\Http\Controllers\Gallery\GalleryController::class, 'destroy']);

    Route::post('role/{id}/get', [\App\Http\Controllers\User\RoleController::class, 'get']);
    Route::post('role/list', [\App\Http\Controllers\User\RoleController::class, 'list']);
    Route::post('role/store', [\App\Http\Controllers\User\RoleController::class, 'store']);
    Route::post('role/{id}/update', [\App\Http\Controllers\User\RoleController::class, 'update']);
    Route::post('role/{id}/employee', [\App\Http\Controllers\User\RoleController::class, 'getRoleEmployee']);

    Route::post('learning/list', [\App\Http\Controllers\LearningMaterialsController::class, 'index']);
    Route::post('learning/{id}/get', [\App\Http\Controllers\LearningMaterialsController::class, 'show']);
    Route::post('learning/{id}/delete', [\App\Http\Controllers\LearningMaterialsController::class, 'destroy']);
    Route::post('learning/store', [\App\Http\Controllers\LearningMaterialsController::class, 'store']);
    Route::post('learning/{id}/update', [\App\Http\Controllers\LearningMaterialsController::class, 'update']);
    Route::post('learning/catalog/store', [\App\Http\Controllers\LearningMaterialsController::class, 'storeCatalog']);
    Route::post('learning/catalog', [\App\Http\Controllers\LearningMaterialsController::class, 'catalog']);
    Route::post('learning/catalog/{id}/get', [\App\Http\Controllers\LearningMaterialsController::class, 'catalogDetail']);
    Route::post('learning/catalog/{id}/delete', [\App\Http\Controllers\LearningMaterialsController::class, 'catalogDelete']);
    Route::post('learning/viewer/store', [\App\Http\Controllers\LearningMaterialsController::class, 'storeViewer']);

    Route::post('quote/store', [\App\Http\Controllers\QuoteController::class, 'store']);
    Route::post('quote/list', [\App\Http\Controllers\QuoteController::class, 'list']);
    Route::post('quote/getLast', [\App\Http\Controllers\QuoteController::class, 'getLast']);
    Route::post('quote/{id}/get', [\App\Http\Controllers\QuoteController::class, 'get']);
    Route::post('quote/{id}/update', [\App\Http\Controllers\QuoteController::class, 'update']);
    Route::post('quote/{id}/delete', [\App\Http\Controllers\QuoteController::class, 'destroy']);

    Route::post('booking/conference/store', [\App\Http\Controllers\BookingController::class, 'storeConference']);
    Route::post('booking/conference/today', [\App\Http\Controllers\BookingController::class, 'getTodayConference']);
    Route::post('booking/conference/today/last', [\App\Http\Controllers\BookingController::class, 'getLastTodayConference']);
    Route::post('booking/{id}/conference/delete', [\App\Http\Controllers\BookingController::class, 'deleteConference']);

    Route::post('npa/store', [\App\Http\Controllers\NPALinkController::class, 'store']);
    Route::post('npa/{id}/delete', [\App\Http\Controllers\NPALinkController::class, 'delete']);
    Route::post('npa/list', [\App\Http\Controllers\NPALinkController::class, 'list']);

    Route::post('project/store', [\App\Http\Controllers\Project\ProjectController::class, 'store']);
    Route::post('project/list', [\App\Http\Controllers\Project\ProjectController::class, 'list']);
    Route::post('project/{id}/get', [\App\Http\Controllers\Project\ProjectController::class, 'get']);
    Route::post('project/{id}/update', [\App\Http\Controllers\Project\ProjectController::class, 'update']);
    Route::post('project/{id}/delete', [\App\Http\Controllers\Project\ProjectController::class, 'delete']);
    Route::post('project/employee/store', [\App\Http\Controllers\Project\ProjectController::class, 'storeEmployee']);
    Route::post('project/employee/remove', [\App\Http\Controllers\Project\ProjectController::class, 'removeEmployee']);
    Route::post('project/employee/invite', [\App\Http\Controllers\Project\ProjectController::class, 'inviteEmployee']);

    Route::post('message/send/project', [\App\Http\Controllers\Project\ProjectChatController::class, 'store']);
    Route::post('message/delete/project/{id}', [\App\Http\Controllers\Project\ProjectChatController::class, 'delete']);
    Route::post('message/get/project', [\App\Http\Controllers\Project\ProjectChatController::class, 'get']);
    Route::post('message/get/files/project', [\App\Http\Controllers\Project\ProjectChatController::class, 'files']);

});

Route::prefix('sanctum')->namespace('API')->group(function () {
    Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('token', [\App\Http\Controllers\AuthController::class, 'token']);
});

Route::post('docs/regulation/list', [\App\Http\Controllers\DocsRegulation\DocsRegulationController::class, 'list']);
