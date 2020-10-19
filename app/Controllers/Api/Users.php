<?php namespace App\Controllers\Api;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Models\UsersModel;


class Users extends Controller {
    use ResponseTrait;
    public function __construct() {
        $this->user_model = new UsersModel();
    }
    public function users($userid = null){
        $request = $this->request;
        if($request->isAJAX()){
            $data = $this->user_model->users($userid);
            return $this->respond($data);
        }
    }
    public function admins($userid = null){
        $request = $this->request;
        if($request->isAJAX()){
            $data = $this->user_model->admins($userid);
            return $this->respond($data);
        }
    }
    public function adminLogin($login, $userpass){
        $request = $this->request;
        if($request->isAJAX()){
            $admins = $this->user_model->admins('', $login);
            $session = session();
            if( $admins ){
                $db_pass = $admins['user_password'];
                $is_admin = password_verify($userpass, $db_pass);
                if( $is_admin ) {
                    unset($admins['user_password']);
                    $session_data = [
                        'isAdmin' => true,
                        'isLoggedIn' => true,
                        'data' => $admins,
                    ];
                    $session->set($session_data);
                    return $this->respond($session_data);
                } else {
                return $this->respond([
                        'isAdmin' => false,
                        'data' => null,
                        'isLoggedIn' => false,
                    ]);
                }
            }
            return $this->respond([
                'data' => null,
                'isLoggedIn' => false
            ]);
        }
    }
    public function logout(){
        $request = $this->request;
        if($request->isAJAX()){
            $session = session();
            $session->destroy();
            return $this->respond([
                'data' => null,
                'isLoggedIn' => false,
                'isAdmin' => false
            ]);
        }
    }
}
?>