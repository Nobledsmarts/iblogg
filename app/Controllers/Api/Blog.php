<?php namespace App\Controllers\Api;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Controllers\Api\Posts;
use App\Controllers\Api\Users;
use App\Controllers\Api\Comments;
use CodeIgniter\CodeIgniter;

class Blog extends Controller {
    use ResponseTrait;
    public function __construct() {
        $this->posts_cont = new Posts();
        $this->users_cont = new Users();
        $this->comments_cont = new Comments();
    }
	public function index() {
        $request = $this->request;
        if($request->isAJAX()){
            return $this->respond([], 200);
        }
    }
    public function posts($post_id = null, $post_slug = null) {
        $request = $this->request;
        if($request->isAJAX()){
           return $this->posts_cont->posts($this, $request, $post_id, $post_slug);
        }
    }
    public function comments($comments_id, $post_id){
        $request = $this->request;
        if($request->isAJAX()){
            $this->comments_cont->posts($request, $comments_id, $post_id);
        }
    }
    public function users($user_id = null){
        $request = $this->request;
        if($request->isAJAX()){
           $data = $this->users_cont->users($request, $user_id);
           return $this->respond([
                'data' => $data
           ]);
           
        }
    }
    public function admins($user_id = null){
        $request = $this->request;
        if($request->isAJAX()){
           $data = $this->users_cont->admins($request, $user_id);
           return $this->respond([
                'data' => $data
           ]);           
        }
    }
}