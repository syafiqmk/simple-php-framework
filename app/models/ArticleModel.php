<?php

namespace App\Models;

use System\Model;

class ArticleModel extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'created_at',
        'updated_at'
    ];

    /**
     * Get all articles
     *
     * @return array
     */
    public function all()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        return $this->query($query);
    }

    /**
     * Get article by ID
     *
     * @param int $id
     * @return array
     */
    public function find($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        return $this->queryOne($query, ['id' => $id]);
    }

    /**
     * Create new article
     *
     * @param array $data
     * @return int|bool
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->insert($data);
    }

    /**
     * Update article
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->updateById($id, $data);
    }

    /**
     * Delete article
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->deleteById($id);
    }

    /**
     * Get articles by user ID
     *
     * @param int $userId
     * @return array
     */
    public function getByUserId($userId)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC";
        return $this->query($query, ['user_id' => $userId]);
    }
}
