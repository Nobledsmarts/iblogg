<?php namespace App\Models;

    use CodeIgniter\Model;

    class RolesModel extends Model{
        protected $table = 'roles';
        protected $primaryKey = 'id';
        protected $returnType = 'array';
        protected $useSoftDeletes = true;
        protected $useTimestamps = true;

        protected $allowedFields = ['role_id', 'role_name'];

        protected $createdField = 'created_at';
        protected $updatedField = 'updated_at';
        protected $deletedField = 'deleted_at';

    }
?>