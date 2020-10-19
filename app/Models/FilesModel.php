<?php namespace App\Models;

    use CodeIgniter\Model;

    class FilesModel extends Model{
        protected $table = 'files';
        protected $primaryKey = 'file_id';
        protected $returnType = 'array';
        protected $useSoftDeletes = true;
        protected $useTimestamps = true;

        protected $allowedFields = ['file_path', 'file_uploader_id', 'file_size', 'file_type', 'file_ext'];

        protected $createdField = 'created_at';
        protected $updatedField = 'updated_at';
        protected $deletedField = 'deleted_at';


        public function getFiles($column, $value = false){
            if( $column == false ){
                return $this->findAll();
            } 
            return $this->asArray()
                        ->where([$column => $value])
                        ->first();
            
        }
    }
?>