<?php

namespace App\Services;

use App\Exceptions\BussinessException;
use App\Models\User;
use App\Models\UserFile;
use App\Services\IService;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Storage;

class ServiceFile implements IService
{
    private static $instance;
    private $db;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }

    public static function getInstance(ConnectionInterface $db)
    {
        if (empty(static::$instance)) {
            static::$instance = new self($db);
        }

        return static::$instance;
    }

    public function getAllFiles(int $limit = 100, int $offset = 0, string $search = null)
    {
        $model = UserFile::orderBy("created_at", "desc");
        if (!empty($search)) {
            $model->whereRaw("filename like ?", "%{$search}%");
        }

        $model->limit($limit);
        $model->offset($offset);
        $rows = $model->get();
        return $rows;
    }

    public function getFile(int $id)
    {
        $row = UserFile::find($id);
        return $row;
    }

    public function getFileByUuid(string $uuid)
    {
        $model = UserFile::with(['user'])->where("uuid", $uuid);
        return $model->first();
    }

    public function deleteFile(int $id)
    {
        $row = UserFile::find($id);
        if (empty($row)) {
            throw new BussinessException("File not found");
        }

        try {
            $userDir = $row->user->getUserDirName();
            Storage::disk($row->disk)->delete("{$userDir}/{$row->filename}");
            return $row->delete();
        } catch (\Throwable $th) {
            error_log("deleteFile failed: ");
            error_log($th->getMessage());
        }

        return false;
    }

    public function deleteFileByUuid(string $uuid)
    {
        $row = $this->getFileByUuid($uuid);
        if (empty($row)) {
            throw new BussinessException("File not found");
        }

        return $this->deleteFile($row->id);
    }

    public function uploadFile(User $user, \Illuminate\Http\UploadedFile $file, $dir_id)
    {
        $fileName = \Illuminate\Support\Str::uuid();
        $fileExt = strtolower($file->getClientOriginalExtension());
        if (!$this->isAllowedExt($fileExt)) {
            throw new BussinessException("Invalid file extention");
        }

        try {
            $storedPath = $file->store($user->getUserDirName());
            if ($storedPath == false) {
                throw new BussinessException("Upload file failed");
            }

            $model = new UserFile();
            $model->uuid = $fileName;
            $model->user_id = $user->id;
            $model->dir_id = $dir_id;
            $model->slug = $storedPath;
            $model->filename = $storedPath;
            $model->disk = "local";
            $model->mimeType = $file->getMimeType();
            $model->clientExt = $fileExt;
            $model->clientSize = $file->getSize();
            $model->originalFileName = $file->getClientOriginalName();
            $model->save();

            return $model;
        } catch (\Throwable $th) {
            throw new BussinessException("Upload failed: " . $th->getMessage());
        }

        return null;
    }

    public function isAllowedExt($ext)
    {
        $exts = ["jpg", "png", "gif", "jpeg", "zip", "tar", "doc", "pdf", "docx", "xls", "xlsx"];
        return in_array($ext, $exts);
    }

    public function getDb()
    {
        return $this->db;
    }
}
