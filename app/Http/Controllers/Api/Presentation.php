<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ServiceFile;
use App\Utils\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Presentation extends Controller
{
    public function handleViewFileByUuid(Request $request, string $uuid)
    {
        $service = ServiceFile::getInstance(DB::connection());
        $file = $service->getFileByUuid($uuid);

        return response()->file($file->filename, [
            "content-type" => $file->mimeType
        ]);
    }

    public function handleDownloadFileByUuid(Request $request, string $uuid)
    {
        $service = ServiceFile::getInstance(DB::connection());
        $file = $service->getFileByUuid($uuid);

        return response()->download($file->filename);
    }
}
