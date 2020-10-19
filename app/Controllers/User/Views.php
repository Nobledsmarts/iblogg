<?php namespace App\Controllers\User;

use App\Controllers\Home;
use App\Models\CommentsModel;
use CodeIgniter\Controller;
use App\Models\PostsModel;
use App\Models\SettingsModel;
use App\Libraries\Pager;


class Views extends Controller {
    public function __construct() {
        $this->home = new Home();
        $this->posts_model = new PostsModel();
        $this->settings_model = new settingsModel();

    }
    function index($request, $limit, $offset){
        $res = $this->settings_model->select('site_settings')->find()[0];
        $settings = json_decode($res['site_settings']);
        $site_tagline = $settings->site_tagline;

        $posts_query  =  $this->posts_model->asArray()->where('post_type', 'post')->orderBy('post_id', 'desc');
        $posts = $posts_query->paginate($limit,'link', $offset);
        $pagination_count = $posts_query->countAllResults();
        
        $data['posts'] = $posts;
        $data['posts_count'] = $pagination_count;
        $data['pager'] = $this->posts_model->pager;
        $data['site_tagline'] = $site_tagline;
        $data['limit'] = (int) $limit;

        $this->home->view('user', 'index', $data);
    }
    function singlePost($request, $slug = null, $page){
        $session = session();
        $res = $this->settings_model->select('comment_settings')->find()[0];
        $settings = json_decode($res['comment_settings']);
        $limit = $request->getVar('limit');
        $offset = (int) $request->getVar('offset') ?? 1;
        $comments_perpage = $limit ? $limit : $settings->user_comments_per_page;
        $comments_model = new CommentsModel();
        $post_data = $this->posts_model->getPosts('post_slug', $slug, $page);
        $comments = $comments_model->get($comments_perpage, $offset, $post_data['post_id']);
        $comments = $comments_model->where('post_id', $post_data['post_id'])->orderBy('comment_id', 'desc')->findAll($comments_perpage, ($offset - 1) * $comments_perpage);
        $Allcomments = $comments_model->where('post_id', $post_data['post_id'])->findAll();
        $username = $session->get('data') ? $session->get('data')['user_name'] : '';
        $data['username'] = $username;
        if($slug != null ){
            $data['post'] = $post_data;
            $data['comments'] = $comments;
            $details = [
                'total' => count($Allcomments),
                'per_page' => $comments_perpage,
                'offset' => $offset,
            ];
            $pager = new Pager();
            $pagination = $pager->get_pagination($details);
            $data['c_pagination'] = $pagination;
            $data['total_comments'] = $details['total'];
            $data['limit'] = $details['per_page'];
            $this->home->view('user', 'single' . $page, $data);
        }
    }

    public function searchPage($request, $limit, $offset){
        $post_model = new PostsModel();
        $s = $request->getVar('s');
        $posts = $post_model 
                    ->where('post_type', 'post')
                    ->like('post_title', $s)
                    ->orderBy('post_id', 'desc')
                    ->findAll($limit, ($offset - 1) * $limit);
        $data['isSearch'] = true;
        $count = count($posts);
        $details = [
            'total' => $count,
            'per_page' => $limit,
            'offset' => $offset,
        ];
        $pager = new Pager();
        $pagination = $pager->get_pagination($details);
        $data['posts'] = $posts;
        $data['posts_limit'] = $limit;
        $data['pager'] = $this->posts_model->pager;
        $data['post_count'] = $count;
        $data['s'] = $s ;
        $data['pagination'] = $pagination;
        $this->home->view('user', 'searchpage', $data);
    }
}