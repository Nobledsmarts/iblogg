<?php namespace App\Controllers\Api;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Models\CommentsModel;
use App\Models\SettingsModel;
use App\Models\UsersModel;

class Comments extends Controller {
    use ResponseTrait;
    public function index($comment_id = null){
        $request = $this->request;
        if($request->isAJAX()){
            $req_method = $request->getMethod();
            if($req_method == 'get'){
                //
            } else if($req_method == 'post'){
                $comments_model = new CommentsModel();
                $users_model = new UsersModel();

                $session = session();
                $isLoggedIn = $session->get('isLoggedIn');
                $comment_body = $request->getVar('comment');
                $comment_author = trim($request->getVar('username'));
                $post_id = $request->getVar('post_id');
                $author_ip = $request->getIPAddress();
                $username_exist = $users_model->where('user_name', $comment_author)->find();
                if($username_exist && !$isLoggedIn){
                    return $this->fail('Choose a different username');
                }
                $comment_data = [
                    "comment_body" => $comment_body,
                    "comment_author" => $comment_author,
                    "post_id" => $post_id,
                    "author_ip" => $author_ip,
                ];
                $comment_ok = $comments_model->save($comment_data);
                if($comment_ok){
                    return $this->respondCreated([
                        'message' => 'success',
                        'data' => $comment_data,
                        'posted' => true
                    ]);
                }
            }
            else if($req_method == 'delete'){
                $comments_model = new CommentsModel();
                $users_model = new UsersModel();
                if( $comment_id ){
                    $data = $comments_model->find($comment_id);
                    if( $data ){
                        $delete_ok =  $comments_model->delete($comment_id);
                        if( $delete_ok ) {
                            return $this->respondDeleted([
                                'data' => $data,
                                'message' => 'success'
                            ]);
                        }
                    }
                    return $this->failNotFound('Not Found');
                } else {
                    $data = $comments_model->findColumn('comment_id');
                    $comments_model->delete($data);
                    return $this->respondDeleted([
                        'data' => $data,
                        'message' => 'success'
                    ]);
                }
            }
        }
    }
}
?>