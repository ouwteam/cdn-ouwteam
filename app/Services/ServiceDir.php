<?php

namespace App\Services;

use App\Models\User;
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

    /**
     * @return ServiceDir
     */
    public static function getInstance(ConnectionInterface $db)
    {
        if (empty(static::$instance)) {
            static::$instance = new self($db);
        }

        return static::$instance;
    }

    public function getAllDirs(User $user, int $limit = 100, int $offset = 0, string $search = null)
    {
        $model = UserDirectory::whereBelongsTo($user);
        if (!empty($search)) {
            $model->whereRaw("dirname like ?", "%{$search}%");
        }

        $model->limit($limit);
        $model->offset($offset);
        $rows = $model->get();
        return $rows;
    }

    public function getDirsWithContent(User $user, int $limit = 100, int $offset = 0, string $search = null)
    {
        $model = UserDirectory::whereBelongsTo($user);
        if (!empty($search)) {
            $model->whereRaw("dirname like ?", "%{$search}%");
        }

        $model->limit($limit);
        $model->offset($offset);
        $rows = $model->with(['files'])->get();
        return $rows;
    }

    public function getDir(int $id)
    {
        $row = UserDirectory::find($id);
        return $row;
    }

    public function isValidDir(int $dirId) {
        return !($this->getDir($dirId) == null);
    }

    public function getDb()
    {
        return $this->db;
    }
}
