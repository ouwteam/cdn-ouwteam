<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ServiceDir;
use App\Utils\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileManager extends Controller
{
    public function handleDirListing(Request $request)
    {
        $service = ServiceDir::getInstance(DB::connection());
        $dirs = $service->getAllDirs($request->limit ?? 100, $request->start ?? 0, $request->search);

        return response()->json(new FormatResponse(
            200, 
            "",
            [
                "dirs" => $dirs
            ] 
        ));
    }

    public function handleListContent(Request $request)
    {
        
    }

    public function handleDeleteFileByUuid(Request $request)
    {

    }

    public function handleUploadFile(Request $request)
    {

    }
}
