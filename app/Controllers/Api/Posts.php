<?php namespace App\Controllers\Api;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Models\PostsModel;
use CodeIgniter\I18n\Time;
use CodeIgniter\Files\File;


class Posts extends Controller {
    use ResponseTrait;
    public function __construct() {
        $this->post_model = new PostsModel();
    }
    public function handlePost() {
        $request = $this->request;
        if($request->isAJAX()){
            helper(['form', 'url']);
			$validation = \Config\Services::validation();
        
            $session = session();
            $post_body = $request->getPost('post_body');
            $post_type = $request->getPost('post_type');
            $post_title = $request->getPost('post_title');
            $post_id = $request->getPost('post_id');
            $post_slug = $request->getPost('post_slug'); 
            $poster_id = $session->get('data')['user_id'];
            $slug = url_title($post_title, '-', true);            
            $is_edit = $post_id ?? false;
            $file = $request->getFile('post_thumbnail');
            $data = [];
            if( !$validation->run($data, 'post') ){
                return $this->fail($validation->getErrors('post_thumbnail')['post_thumbnail']);
            }
            if($post_type !== 'post'){
                $page_exist = $this->post_model->where('post_type', $post_type)->where('post_title', $post_title)->find();
                if($page_exist){
                   return $this->fail('Page Already Exists');
                }
            }
            if( $file->isValid() ){
                $name = 'iBlogg-' . $file->getRandomName();
                $upload_ok = $file->move(ROOTPATH . 'public/uploads/', $name);
                if($upload_ok){
                    $path = 'uploads/' . $name;
                    $data['post_thumbnail']  = $path;
                }
            }
            $data['post_type'] = $post_type;
            $data['post_body'] = $post_body;
            $data['post_title'] = $post_title;
            if($post_type == 'post'){
                $slug_prefix = $slug . ':' . strtotime('now');
                
                $data['post_slug'] = $is_edit ? (explode(':', $post_slug)[0] == $slug ? $post_slug : $slug_prefix) : $slug_prefix;
            } else {
                $data['post_slug'] = $slug;
            }

            if($is_edit) {
                $data['post_id']  = $post_id;
            } else {
                $data['poster_id'] = $poster_id;
            }
            $this->post_model->save($data);
            if( !$is_edit ){
                return $this->respondCreated([
                    'message' => 'success',
                    'data' => $data,
                    'posted' => true
                ]);
            } else {
                $data = $this->post_model->find($post_id);
                return $this->respond(['data' => $data, 'edited' => true], 200);
            }
        }
    }
    public function delete($post_type = 'post', $post_id = null){
        $request = $this->request;
        if( $request->isAJAX() && $request->getMethod() == 'delete' ){
            if( $post_id ){
                $data = $this->post_model->where('post_type', $post_type)->find($post_id);
                if( $data ){
                    $delete_ok =  $this->post_model->delete($post_id);
                    if( $delete_ok ) {
                        return $this->respondDeleted([
                            'data' => $data,
                            'message' => 'success'
                        ]);
                    }
                } 
                return $this->failNotFound('Invalid Post Id');
            } else {
                $data = $this->post_model->where('post_type', $post_type)->findColumn('post_id');
                $this->post_model->delete($data);
                return $this->respondDeleted([
                    'data' => $data,
                    'message' => 'success'
                ]);
            }
        }
    }
}
?>