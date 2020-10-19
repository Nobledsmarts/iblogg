<?php namespace App\Models;

    use CodeIgniter\Model;

    class PostsModel extends Model{
        protected $table = 'posts';
        protected $primaryKey = 'post_id';
        protected $returnType = 'array';
        protected $useSoftDeletes = true;
        protected $useTimestamps = true;
        protected $allowedFields = ['post_title', 'post_body', 'post_thumbnail', 'post_slug', 'poster_id', 'post_type'];

        protected $createdField = 'created_at';
        protected $updatedField = 'updated_at';
        protected $deletedField = 'deleted_at';


        public function getPosts($column = null, $value = false, $post_type = 'post'){
            if( $column == null ){
                return $this->where('post_type', $post_type)->findAll();
            } 
            return $this->where('post_type', $post_type)
                        ->where([$column => $value])
                        ->first();
        }
    }
?>