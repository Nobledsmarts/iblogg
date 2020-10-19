<?php namespace App\Controllers\Template;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Controllers\Admin;
use App\Controllers\User;
use App\Models\SettingsModel;
use App\Libraries\Setup;
use CodeIgniter\CodeIgniter;

class View extends Controller {
    use ResponseTrait;
    public function __construct() {
        $setup = new Setup();
		$setup->create();
        $this->admin = new Admin\Views();
        $this->user = new User\Views();
        $this->settings_model = new SettingsModel();
    }
    public function view($page_dir = null){
        $request = $this->request;
        $is_adminpage = $page_dir == 'admin' ? true : false;
        $page = $request->getVar('page');
        $slug = $request->getVar('slug');
        $limit = $request->getVar('limit');
        $offset = $request->getVar('offset');
        if($request->isAJAX()){
            if( !$is_adminpage ){
                $res = $this->settings_model->select('post_settings')->find()[0];

                $settings = json_decode($res['post_settings']);

                $user_post_per_page = $limit ? $limit : $settings->user_posts_per_page;

                $usersrch_post_per_page = $limit ? $limit : $settings->usersrch_posts_per_page;

                if($page == 'index'){
                    $this->user->index($request, $user_post_per_page, $offset);
                } elseif($page == 'post' || $page == 'page'){
                    $this->user->singlePost($request, $slug, $page);
                } elseif($page == 'searchpage'){
                    $this->user->searchPage($request, $usersrch_post_per_page, $offset);
                } 
            } else {
                $res = $this->settings_model->select('post_settings, file_settings')->findAll()[0];

                $post_settings = json_decode($res['post_settings']);
                $file_settings = json_decode($res['file_settings']);
                // var_dump(($post_settings->));
                $user_posts_per_page = $post_settings->user_posts_per_page;
                $files_per_page = $file_settings->files_per_page;

                $post_per_page = $limit ? $limit : $user_posts_per_page;
                $files_per_page = $limit ? $limit : $files_per_page;

                $session = session();
                $is_admin = $session->get('isAdmin');
                $login = $session->get('isLoggedIn');
                $is_admin = $is_admin ? $is_admin == true : false;
                $isLoggedIn = $login ? $login == true : false;
                if( !$is_admin && !$isLoggedIn ){
                    $data = [];
                    if( $page != 'login' ) $data['message'] = 'Access Denied Login To Continue';
                    // if($page == 'login' )
                    return view('admin/login', $data);
                } else {
                    if($page == 'index'){
                        $this->admin->index($request);
                    } elseif($page == 'handlepost'){
                        $this->admin->handlePost($request);
                    } elseif($page == 'login'){
                        return $this->respond($session->get('data'));
                    } elseif($page == 'posts'){
                        $this->admin->postsPage($request, $post_settings->admin_posts_per_page, $offset);
                    } elseif($page == 'files'){
                        $this->admin->filesPage($request, $files_per_page, $offset);
                    } elseif($page == 'newfile'){
                        $this->admin->newFilesPage($request, $files_per_page, $offset, $page);
                    } elseif($page == 'settings'){
                        $this->admin->settingsPage($request);
                    } else if($page == 'comments'){
                        $this->admin->commentsPage($request);
                    }
                    else if($page == 'users'){
                        $this->admin->usersPage($request);
                    }
                }
            }
        } else {
            return view('forbidden');
        }
    }
     
     
}