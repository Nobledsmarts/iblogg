<?php namespace App\Models;

    use CodeIgniter\Model;

    class UsersModel extends Model{
        protected $table = 'users';
        protected $primaryKey = 'user_id';
        protected $returnType = 'array';
        protected $useSoftDeletes = true;
        protected $useTimestamps = true;
        protected $allowedFields = ['user_name', 'user_email', 'user_avatar', 'user_role', 'user_password'];

        protected $beforeInsert = ['hashPassword'];
        protected $beforeUpdate = ['hashPassword'];


        protected $createdField = 'created_at';
        protected $updatedField = 'updated_at';
        protected $deletedField = 'deleted_at';

        public function registerUser(array $data){
            return $this->insert($data);
        }
        
        public function registerAdmin(){
            
        }
        public function users($userid = null, $username = null){
            if( $userid ){
                $user = $this->find($userid);
                return [$user];
            } elseif($username){
                $user = $this->where('user_name', $username)->first();
                return [$user];
            } else {
                return $this->findAll();
            }
        }
        public function admins($userid = null, $login = null){
            if( $userid ){
                $user = $this->find($userid)->where('user_role', 'admin');
                return $user;
            } elseif($login){
                $user = $this->select(['user_id', 'user_name', 'user_role', 'user_password'])->where('user_role', 'admin')
                            ->where('user_name', $login)
                            ->orWhere('user_email', $login)->first();
                return $user;
            } else {
                return $this->where('user_role', 'admin')->findAll();
            }
        }
        
        public function hashPassword(array $data){
            if(! isset($data['password'])) return $data;
            $data['user_password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
            return $data;
        }
    }