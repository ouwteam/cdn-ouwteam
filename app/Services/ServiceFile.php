<?php

use App\Models\UserFile;
use App\Services\IService;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Storage;

class ServiceFile implements IService {
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
        $model = UserFile::where("uuid", $uuid);
        return $model->first();
    }

    public function deleteFile(int $id)
    {
        $row = UserFile::find($id);
        if(empty($row)) {
            throw new BussinessException("File not found");
        }

        try {
            Storage::disk($row->disk)->delete($row->filename);
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
        if(empty($row)) {
            throw new BussinessException("File not found");
        }

        return $this->deleteFile($row->id);
    }

    public function getDb()
    {
        return $this->db;
    }
}