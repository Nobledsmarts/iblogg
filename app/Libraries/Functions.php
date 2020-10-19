<?php namespace App\Libraries;

    use App\Models\CommentsModel;
    use App\Models\PostsModel;
    use CodeIgniter\I18n\Time;
    use App\Models\SettingsModel;
    use App\Models\UsersModel;

    class Functions {
        public function __construct() {
            $this->posts_model = new PostsModel();
            $this->comments_model = new CommentsModel();
            $this->settings_model = new SettingsModel();
        }
        public function timeago($date){
            $time = Time::parse($date);
            return $time->humanize();
        
        }
        public function slice_words($str, $word_length, $type = 'words') {
            helper('text');
            if($type == 'words'){
                $end_char = str_word_count($str) <= $word_length ? '' : '...';
                return word_limiter($str, $word_length, $end_char);
            } else {
                $end_char = strlen($str) <= $word_length ? '' : '...';
                return character_limiter($str, $word_length, $end_char);
            }
        }
        public function pagiate($link, $track, $page = ''){
            $pagination = "";
            $track = $track ?? 'page';
            if($link['active'] == true){
                echo "
                <li class='page-item active'>
                    <a class='page-link'>
                        ${link['title']}
                    </a>
                </li>
                ";
            } else {
                echo "
                <li class='page-item'>
                    <a class='page-link' href='#/${page}?${track}=${link['title']}'>
                        ${link['title']}
                    </a>
                </li>
                ";
            }
            return $pagination;
        }
        public function form_query_str($get_vars){
            $get_arr = array_keys($get_vars);
            if( !empty($get_vars) ){
                $str = '?';
                for($i = 0; $i < count($get_arr); $i++){
                    $key = $get_arr[$i];
                    $value = $get_vars[$key];
                    $str .= $key . '=' . $value;
                    if( ($i + 1) != count($get_arr) ){
                        $str .= '&';
                    }
                }
                return $str;
            }
            return '';
        }
        
        public function get_read_type($file){
            $file_type = [
                'audio' => ['mp3', 'wav', 'opus', 'mpga', 'aac', 'flac', 'wma', 'mk4'],
                'picture' => ['jpg', 'jpeg', 'png', 'gif'],
                'video' => ['mp4', 'mpg', 'm4a', 'm4v', 'f4v', 'f4v', 'm4b', 'm4r', 'f4b', 'mov', 'webm', '3gp', '3gp2', '3g2', '3gpp', '3gpp2', 'ogg', 'oga', 'ogv', 'ogx', 'wmv', 'wma', 'asf', 'flv', 'avi'],
                'docs' => ['zip', 'pdf', 'epub', 'txt', 'mobi'],
            ];
            $fFile = (explode('.', $this->trim_file($file['file_path'])));
            $fFile_type = strtolower(end($fFile));
            reset($fFile);
            foreach($file_type as $key => $values){
                if(array_search($fFile_type, $values) !== false){
                    return $key;
                };
            }
            return 'others';
        }
        public function get_icon_class($type){
            $icon_class = '';
            if($type == 'picture'){
                $icon_class = 'fa-photo-video';
            } else if($type == 'audio'){
                $icon_class = 'fa-music' ;
            } else if($type == 'video'){
                $icon_class = 'fa-video' ;
            } else {

            }
            return $icon_class;
        }
        public function display_file($file){
            helper('number');
            $file_size = number_to_size($file['file_size']);
            $rType = $this->get_read_type($file);
            $icon_class = $this->get_icon_class($rType);
            
            $str = "<div class='card'>";
            $str_body = "<ul class='list-group'>
                            <li class='list-group-item'>
                                <b> name :</b> <input class='form-control' value='{$this->trim_file($file['file_path'])}'>
                                
                            </li>
                            <li class='list-group-item'>
                                <b> size :</b> {$file_size}
                            </li>
                            <li class='list-group-item'>
                                <b> link :</b> <input class='form-control' value='{$file['file_path']}'>
                            </li>
                        </ul>";
            $str.="
                <div class='card-header bg-light d-flex file- justify-content-between'>
                    <div>
                        <span class='fa {$icon_class} theme-blue'></span>
                    </div>
                    <div>
                        <span class='fa fa-clock theme-blue'></span>
                        <span class='theme-blue'>
                        {$this->timeAgo($file['created_at'])}
                        </span>
                    </div>
                </div>
                <img class='file-list-img d-none card-img-top' src='uploads/{$this->trim_file($file['file_path'])}'>
                    <div class='card-body'>
                        {$str_body}
                    </div>";
            $str.= "
                <div class='card-footer d-flex file- justify-content-end'>
                    <button class='mx-2 d-none btn btn-outline-primary btn-sm' onclick='router.routeTo('/admin/editpost?postid={$file['file_id']}')'>
                        Edit
                    </button>
                    <button class='btn btn-act btn-sm mx-2' onclick=\"triggerDelete({$file['file_id']}, 'file')\">
                        Delete
                    </button>
                </div>";
            
            $str.= "</div>";
            return $str;

        }
        public function trim_file($str){
            $uploadIndex = strpos($str, DIRECTORY_SEPARATOR . 'uploads');
            return substr($str, $uploadIndex + 9);
        }
        public function user_by_Id($id){
            if( $id ){
                $users = new UsersModel();
                $name = $users->asArray()
                        ->select('user_name')
                        ->where('user_id', $id)->findAll()[0];
                return $name['user_name'];
            }
            return '';
        }
        public function comment_page($post_id, $comment_id){
            $res = $this->settings_model->select('comment_settings')
                    ->findAll()[0];
            $comment_settings = json_decode($res['comment_settings']);
            $limit = $comment_settings->user_comments_per_page;

            $post = $this->posts_model->find($post_id);

            $all_comments = $this->comments_model->where('post_id', $post_id)->orderBy('comment_id', 'desc')->findAll();

            $total = count($all_comments);
            $per_page = $limit;
            $divide_total = (int) ceil($total / $per_page);
            $pages = [];
            $loop_offset = 0;
            for($i = 0; $i < $divide_total; $i++){
                $pages[] = array_slice($all_comments, $loop_offset, $per_page);
                $loop_offset+= $per_page;
            }
            $k = null;
            $cid = null;
            for($a = 0; $a < count($pages); $a++){
                $ad = $pages[$a];
                for($b = 0; $b < count($ad); $b++){
                    $bd = $ad[$b];
                    if($bd['comment_id'] == $comment_id){
                        $k = $a;
                        $cid = $b;
                    }
                }
            }
            $k++;
            $cid++;
            return $post['post_slug'] . '?cid=' . $cid . '&page=' . $k;
        }
       function  do_searchForm($url = '/admin/posts'){
            return "<form class='' onsubmit=\"searchPosts('" . $url . "')\">
                <table cellspacing='0' cellpadding='0' class='w-100'>
                    <tr>
                        <td align='right' class='bg-light w-100 p-1 nav-form-td rounded-left'>
                            <input type='text' name='s' placeholder='search' class='w-100 bg-light border-0' value='@{{s}}'>
                        </td>
                        <td align='left' class='btn-light nav-form-td rounded-right'>
                            <button class='btn'>
                                <span class='fa theme-blue fa-search'></span>
                            </button>
                        </td>
                    </tr>
                </table>
            </form>";
        }
        public function do_page_list(){
            $post_model = new PostsModel();
            $pages = $post_model->where('post_type', 'page')->findAll();
            if( !empty($pages) ){
                $str ='<ul class="d-none page-list list-unstyled d-block list-inline h-100 py-1">';
                foreach($pages as $page){
                    $str.="<li class='p-1'>
                            <a href='/page/{$page["post_slug"]}' class='px-4 py-2 badge shadow-sm badge-pill badge-light'>
                            {$page['post_title']}
                            </a>
                        </li>";
                }
                $str.="</ul>";
                return $str;
            }
            return "";
        }
        public function do_sidepost_list($limit = 10){
            $postsModel = new PostsModel();
            $posts = $postsModel->where('post_type', 'post')->orderBy('post_id', 'desc')->findAll($limit);
            $str = '<div class="list-group" align="left">';
            foreach($posts as $post){
                $str.="<a href='/post/{$post["post_slug"]}' class='list-group-item list-group-item-action'>
                    {$post["post_title"]}
                </a>";
            }
            $str.="</div>";
            return $str;
        }
        public function commentsCount($post_id){
            $comments_model = new CommentsModel();
            if($post_id) return count($comments_model->where('post_id', $post_id)->findAll());
            return count($comments_model->findAll());
        }
    }
?>