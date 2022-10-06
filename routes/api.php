<?php

use App\Http\Controllers\Api\FileManager;
use App\Http\Controllers\Api\Presentation;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("/file-manager")->controller(FileManager::class)
    ->group(function () {
        Route::get("/dirs", "handleDirListing");
        Route::get("/list", "handleListContent");
        Route::get("/delete/{fileuuid}", "handleDeleteFileByUuid");
        Route::post("/upload", "handleUploadFile");
    });

Route::prefix("/presentation")->controller(Presentation::class)
    ->group(function () {
        Route::get("/view/{fileuuid}", "handleViewFileByUuid");
        Route::get("/download/{fileuuid}", "handleDownloadFileByUuid");
    });
