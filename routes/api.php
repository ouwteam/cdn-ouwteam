<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FileManager;
use App\Http\Controllers\Api\Presentation;
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

Route::prefix("/auth")->controller(AuthController::class)
    ->group(function () {
        Route::post("/login", "login");
    });

Route::middleware('auth:sanctum')->prefix("/file-manager")->controller(FileManager::class)
    ->group(function () {
        Route::get("/dirs", "handleDirListing");
        Route::get("/list", "handleListContent");
        Route::get("/list/{dirId}", "handleGetContentByDir");
        Route::get("/delete/{fileuuid}", "handleDeleteFileByUuid");
        Route::post("/upload", "handleUploadFile");
    });

Route::prefix("/presentation")->controller(Presentation::class)
    ->group(function () {
        Route::get("/view/{fileuuid}", "handleViewFileByUuid")->name("api.view_url");
        Route::get("/download/{fileuuid}", "handleDownloadFileByUuid");
    });
