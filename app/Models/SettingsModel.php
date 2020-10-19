<?php namespace App\Models;

    use CodeIgniter\Model;

    class SettingsModel extends Model{
        protected $table = 'settings';
        protected $primaryKey = 'id';
        protected $returnType = 'array';
        protected $useSoftDeletes = true;
        protected $useTimestamps = true;
        protected $allowedFields = ['user_settings', 'file_settings', 'post_settings', 'comment_settings', 'site_settings'];

        protected $createdField = 'created_at';
        protected $updatedField = 'updated_at';
        protected $deletedField = 'deleted_at';

    }
?>