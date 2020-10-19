<?php
namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\PostsModel;
use App\Models\FilesModel;
use App\Models\UsersModel;
use App\Controllers\Home;
use App\Libraries\Pager;
use App\Models\CommentsModel;
use App\Models\SettingsModel;

class Views extends Controller {
    public function __construct() {
        $this->home = new Home();
    }
    public function index($request){
        $posts_model = new PostsModel();
        $files_model = new FilesModel();
        $users_model = new UsersModel();
         
        $total_file_count = $files_model->orderBy('file_id', 'desc')->countAllResults(false);

        $total_user_count = $users_model->orderBy('user_id', 'desc')->countAllResults(false);
        $total_post_count = $posts_model->orderBy('post_id', 'desc')->countAllResults(false);
        $post_count = $posts_model->where('post_type', 'post')->orderBy('post_id', 'desc')->countAllResults(false);
        $page_count = $posts_model->where('post_type', 'page')->orderBy('post_id', 'desc')->countAllResults(false);
        $posts_model = new PostsModel();
        $posts  =  $posts_model->where('post_type', 'post')->orderBy('post_id', 'desc')->findAll(10);

        $data['posts'] = $posts;
        $data['post_count'] = $post_count;
        $data['page_count'] = $page_count;
        $data['total_post_count'] = $total_post_count;
        $data['total_user_count'] = $total_user_count;
        $data['total_file_count'] = $total_file_count;
        
        $this->home->view('admin', 'index', $data);
    }
    public function handlePost($request){
        $posts_model = new PostsModel();
        $post_id = $request->getVar('post_id');
        $post_type = $request->getVar('post_type');
        $is_page = $post_type == 'pages' ? true : false;
        $page = $post_type == 'pages' ? 'page' : 'post';
        $data = [
            'page' => $page,
            'post_type' => $post_type,
            'is_page' => $is_page
        ];

        if( $post_id ){
            $data['is_edit'] = true;
            $data['post_id'] = $post_id;
            $post = $posts_model->where('post_type', $page)->find($post_id);
            if( !empty($post) ){
                $data['post'] = $post;
            } else {
                $data['post'] = [];
            }
        } else {
            $data['is_edit'] = false;
        }
        $this->home->view('admin', 'handlepost', $data);
    }
    public function postsPage($request, $limit, $offset){
        $posts_model = new PostsModel();
        $post_type = $request->getVar('post_type');
        $is_page = $post_type == 'pages' ? true : false;
        $page = $post_type == 'pages' ? 'page' : 'post';

        $data = [
            'page' => $page,
            'post_type' => $post_type,
            'is_page' => $is_page
        ];
        $is_page = $page == 'pages' ? true : false;
        $s = $request->getVar('s');

        $posts_query = $posts_model->where('post_type', $page);

        if($s){
            $data['isSearch'] = true;
            $posts_query->like('post_title', $s);
            $posts_query->orLike('post_body', $s);
        } 
        $posts = $posts_query->orderBy('post_id', 'desc')->findAll($limit, ($offset - 1) * $limit);
        $pagination_count = count($posts_model->where('post_type', $page)->findAll());

        $details = [
            'total' => $pagination_count,
            'per_page' => $limit,
            'offset' => $offset,
        ];
        $pager = new Pager();
        $pagination = $pager->get_pagination($details);
        
        $data['posts'] = $posts;
        $data['posts_limit'] = $limit;
        $data['post_count'] =  $pagination_count;
        $data['pagination'] = $pagination;
        $data['total_post_count'] = $pagination_count;
        $data['s'] = $s ;
        $data['is_page'] = $is_page;

        $this->home->view('admin', 'posts', $data);
    }
     
    public function filesPage($request, $limit, $offset){
        $files_model = new FilesModel();
        
        $s = $request->getVar('s');
        $files_query  =  $files_model;
        if($s){
            $data['isSearch'] = true;
            $files_query->like('file_path', $s);
            $files_query->orLike('file_path', $s, 'before');
        } 
        $files_query->orderBy('file_id', 'desc');
        $files = $files_query->paginate($limit,'link', $offset);
        $pagination_count = $files_query->countAllResults();

        $total_file_count = $files_model->countAllResults(false);
        
        $data['files'] = $files;
        $data['files_limit'] = $limit;
        $data['pager'] = $files_model->pager;
        $data['files_count'] = $pagination_count;
        $data['s'] = $s ;
        $data['total_file_count'] = $total_file_count;

        $this->home->view('admin', 'files', $data);
    }
    public function newFilesPage($request, $limit, $offset){
        $files_model = new FilesModel();

        $file_id = $request->getVar('file_id');
        $data = [];
        if( $file_id ){
            $data['is_edit'] = true;
            $data['file_id'] = $file_id;
            $file = $files_model->getPosts('post_id', $file_id);
            if( !empty($file) ){
                $data['file'] = $file;
            } else {
                $data['file'] = [];
            }
        } else {
            $data['is_edit'] = false;
        }
        $this->home->view('admin', 'newfile', $data);
    }
    public function settingsPage($request){
        $settings_model = new SettingsModel();
        $res = $settings_model->findAll();
        $ress = $res[0];
        foreach($ress as $key => $value){
            $ress[$key] = json_decode($value);
        }
        $this->home->view('admin', 'settings', $ress);
    }
    public function commentsPage($request){
        $comments_model = new CommentsModel();
        $settings_model = new SettingsModel();

        $offset = $request->getVar('offset');
        $limit = $request->getVar('limit');
        $offset = (int) $request->getVar('offset') ?? 1;

        $res = $settings_model->select('comment_settings')->find()[0];
        $settings = json_decode($res['comment_settings']);

        $comments_perpage = $limit ? $limit : $settings->admin_comments_per_page;

        $comments_query =  $comments_model->orderBy('comment_id', 'desc');

        // $comments = $comments_query->paginate($comments_perpage,'link', $offset);

        $comments = $comments_query->findAll($comments_perpage, ($offset - 1) * $comments_perpage );

        $pagination_count = $comments_query->countAllResults();
        $details = [
            'total' => $pagination_count,
            'per_page' => $comments_perpage,
            'offset' => $offset,
        ];
        $pager = new Pager();
        $pagination = $pager->get_pagination($details);
        $data['comments'] = $comments;
        $data['pagination'] = $pagination;
        $data['comments_limit'] = $comments_perpage;
        // $data['pager'] = $comments_model->pager;
        $data['comments_count'] = $pagination_count;
        // $data['total_comment_count'] = $pagination_count;

        $this->home->view('admin', 'comments', $data);
    }
    public function usersPage($request){
        $users_model = new UsersModel();
        
        $offset = $request->getVar('offset') || 1;
        $limit = $request->getVar('limit') || 10;
        $users_query =  $users_model->orderBy('user_id', 'desc');

        $users = $users_query->paginate($limit,'link', $offset);

        $pagination_count = $users_query->countAllResults();
        
        $data['users'] = $users;
        $data['users_limit'] = $limit;
        $data['pager'] = $users_model->pager;
        $data['users_count'] = $pagination_count;
        $data['total_user_count'] = $pagination_count;;

        $this->home->view('admin', 'users', $data);
    }
}
