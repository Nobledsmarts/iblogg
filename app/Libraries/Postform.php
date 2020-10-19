<?php namespace App\Libraries;
    class Postform {
        public function get($is_edit, $post_type, $post_id = null, $post = null){
            $str = "";
            $post_title = $post ? $post['post_title'] : '';
            $post_body = $post ? $post['post_body'] : '';
            $title_placeHolder = $post_type == 'post' ? 'title' : 'name';

            $str.= "
                <form class='' onsubmit='handlePost()'>
                    <div class='form-group'>
                        <label class='text-capitalize'>
                            $post_type $title_placeHolder
                        </label>
                        <input type='text' placeholder='Enter $post_type $title_placeHolder' class='form-control' id='post_title' name='post_title' value='$post_title'>
                    </div>
                    <div class='form-group'>
                        <label>Attachment</label>";
                         if(esc($is_edit)){
                              $str.= "<br><div>
                                        <img class='img-fluid img-thumbnail' style='height : 200px; width:300px' src={$post['post_thumbnail']}>
                                    </div><br>";
                         } else {
                            $str.= '<br><div>
                                <img class="img-fluid img-thumbnail d-none" style="height : 200px; width:300px" id="imgPreview">
                                </div><br>';
                         }
                         $str.="
                                <div class='custom-file'>
                                    <input id='post_thumbnail' name='post_thumbnail' accept='image/*' type='file' class='custom-file-input' onchange='updateFileInput()'>
                                    <label data-text='Choose File' class='custom-file-label'> Choose File </label>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label class='text-capitalize'>
                                    $post_type Content
                                </label>
                                <textarea id='post_body' name='post_body' class='form-control border' rows='12' cols='12' placeholder='Enter $post_type content'>$post_body</textarea>
                            </div>
                            <div class='form-group my-3'>
                                <button type='submit' class='btn btn-block btn-primary submit-btn'>
                                    Submit
                                </button>
                            </div>
                            <input type='hidden' name='post_type' value='{$post_type}'>
                            
                            ";
                        if(esc($is_edit)){
                           $str.= "<input type='hidden' name='post_id' value='$post_id'>";
                           $str.= "<input type='hidden' name='post_slug' value='{$post['post_slug']}'>";
                        }
            $str.='
            </form>';
            return $str;
        }
    }