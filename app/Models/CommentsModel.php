<?php namespace App\Models;

    use CodeIgniter\Model;

    class CommentsModel extends Model{
        protected $table = 'comments';
        protected $primaryKey = 'comment_id';
        protected $returnType = 'array';
        protected $useSoftDeletes = true;
        protected $useTimestamps = true;
        protected $allowedFields = ['comment_author', 'comment_body', 'post_id', 'author_ip'];

        protected $createdField = 'created_at';
        protected $updatedField = 'updated_at';
        protected $deletedField = 'deleted_at';

        public function get($limit, $offset, $post_id){
            $comments = $post_id ? $this->where('post_id', $post_id) : $this;
            return $comments->orderBy('comment_id', 'desc')->findAll($limit, ($offset - 1) * $limit);
        }
    }
    
?>