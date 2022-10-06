<?php

namespace App\Services;

use App\Models\UserDirectory;
use Illuminate\Database\ConnectionInterface;

class ServiceDir implements IService
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

    public function getAllDirs(int $limit = 100, int $offset = 0, string $search = null)
    {
        $model = UserDirectory::orderBy("created_at", "desc");
        if (!empty($search)) {
            $model->whereRaw("dirname like ?", "%{$search}%");
        }

        $model->limit($limit);
        $model->offset($offset);
        $rows = $model->get();
        return $rows;
    }

    public function getDir(int $id)
    {
        $row = UserDirectory::find($id);
        return $row;
    }

    public function getDb()
    {
        return $this->db;
    }
}
