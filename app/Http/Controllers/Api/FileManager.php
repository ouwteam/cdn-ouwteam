<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BussinessException;
use App\Http\Controllers\Controller;
use App\Services\ServiceDir;
use App\Services\ServiceFile;
use App\Utils\FormatResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileManager extends Controller
{
    public function handleDirListing(Request $request)
    {
        $service = ServiceDir::getInstance(DB::connection());
        $dirs = $service->getAllDirs($request->user(), $request->limit ?? 100, $request->start ?? 0, $request->search);

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
        $service = ServiceDir::getInstance(DB::connection());
        $contents = $service->getDirsWithContent($request->user(), $request->limit ?? 100, $request->start ?? 0, $request->search);

        return response()->json(new FormatResponse(
            200,
            "",
            [
                "dirs" => $contents
            ]
        ));
    }

    public function handleDeleteFileByUuid(Request $request)
    {
    }

    public function handleUploadFile(Request $request)
    {
        $file = $request->file("attachment");
        $service = ServiceFile::getInstance(DB::connection());
        $status = 200;
        $message = "";
        $uploaded = "";

        try {
            if (empty($file)) {
                throw new BussinessException("Invalid upload attachment");
            }

            if (empty($request->dir_id)) {
                throw new BussinessException("Invalid dir ID");
            }

            $uploaded = $service->uploadFile($request->user(), $file, $request->dir_id);
        } catch (\Throwable $th) {
            $status = 400;
            $message = "Unknow reason";
            if ($th instanceof BussinessException) {
                $message = $th->getMessage();
            }
        }

        return response()->json(new FormatResponse($status, $message, ["uploaded" => $uploaded]), $status);
    }
}
