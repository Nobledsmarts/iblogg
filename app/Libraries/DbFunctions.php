<?php namespace App\Libraries;
    use App\Models\UsersModel;

    class DbFunctions {
        public function __construct() {
            $this->user_model = new UsersModel();
        }
        public function hash_password(array $data){
            if(! isset($data['password'])) return $data;
            $data['user_password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
            return $data;
        }
        public function create_admin(){
            $data = [
				'user_name' => 'Admin',
				'user_email' => 'adminemail@walkify.blog',
				'user_role' => 'admin',
				'password' => 'adminpass',
				'user_password' => 'adminpass',
				'user_avatar' => 'uploads/user.jpg'
			];
			$data = $this->hash_password($data);
			$this->user_model->save($data);
        }
    }