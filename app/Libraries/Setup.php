<?php namespace App\Libraries;
    use App\Models\UsersModel;
    use App\Models\PostsModel;
    use App\Libraries\DbFunctions;
    use App\Models\CommentsModel;
    use App\Models\RolesModel;
    use App\Models\SettingsModel;

    class Setup {
        public function __construct(){
            $this->forge = \Config\Database::forge('startup');
        }
        public function create(){
            if($this->forge->createDatabase('iblogg', true)){
                $this->forge = \Config\Database::forge();
                $attributes = ['ENGINE' => 'InnoDB'];

                $this->create_users_table($attributes);
                $this->create_settings_table($attributes);
                $this->create_files_table($attributes);
                $this->create_roles_table($attributes);
                $this->create_posts_table($attributes);
                $this->create_comments_table($attributes);
            }
        }
        public function create_users_table($attributes){
            $user_fields  = $this->get_user_fields();
            $this->forge->addField($user_fields);
            $this->forge->addPrimaryKey('user_id');
            $this->forge->addUniqueKey('user_name');
            $this->forge->addUniqueKey('user_email');
            $this->forge->createTable('users', TRUE, $attributes);
        }
        public function create_settings_table($attributes){
            $settings_fields = $this->get_settings_fields();
            $this->forge->addField($settings_fields);
            $this->forge->createTable('settings', TRUE, $attributes);
        }
        public function create_files_table($attributes){
            $file_fields = $this->get_file_fields();
            $this->forge->addField($file_fields);
            $this->forge->addPrimaryKey('file_id');
            $this->forge->addForeignKey('file_uploader_id', 'users', 'user_id');
            $this->forge->createTable('files', true, $attributes);
        }
        public function create_roles_table($attributes){
            $role_fields = $this->get_role_fields();
            $this->forge->addField($role_fields);
            $this->forge->createTable('roles', TRUE, $attributes);
        }
        public function create_posts_table($attributes){
            $post_fields = $this->get_post_fields();
            $this->forge->addField($post_fields);
            $this->forge->addPrimaryKey('post_id');
            $this->forge->createTable('posts', true, $attributes);
        }
        public function create_comments_table($attributes){
            $comment_fields = $this->get_comment_fields();
            $this->forge->addField($comment_fields);
            $this->forge->addPrimaryKey('comment_id');
            $this->forge->createTable('comments', true, $attributes);
        }
        static function get_file_fields(){
            $fields = [
                'file_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'file_uploader_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                ],
                'file_path' => [
                    'type' => 'TEXT',
                ],
                'file_size' => [
                    'type' => 'INT',
                ],
                'file_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                ],
                'file_ext' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500,
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME'
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
            ];
            return $fields;
        }
        static function get_settings_fields(){
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'default' => 1,
                ],
                'user_settings' => [
                    'type' => 'TEXT'
                ],
                'file_settings' => [
                    'type' => 'TEXT'
                ],
                'post_settings' => [
                    'type' => 'TEXT'
                ],
                'comment_settings' => [
                    'type' => 'TEXT'
                ],
                'site_settings' => [
                    'type' => 'TEXT'
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME'
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ]
            ];
            return $fields;     
        }
        static function get_user_fields(){
            $fields = [
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'user_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'user_email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'user_avatar' => [
                    'type' => 'VARCHAR',
                    'constraint' => 1000,
                    'default' => 'assets/img/user.jpg'
                ],
                'user_role' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'default' => 'user'
                ],
                'user_password' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'default' => 'user'
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME'
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
            ];
            return $fields;
        }
        static function get_post_fields(){
            $fields = [
                'post_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'post_title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 1000,
                ],
                'post_slug' => [
                    'type' => 'VARCHAR',
                    'constraint' => 1000,
                ],
                'post_body' => [
                    'type' => 'TEXT'
                ],
                'post_thumbnail' => [
                    'type' => 'VARCHAR',
                    'constraint' => 1000,
                    'default' => 'uploads/post-thumbnail.jpg'
                ],
                'post_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'default' => 'post'
                ],
                'poster_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME'
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
            ];
            return $fields;
        }
        static function get_comment_fields(){
            $fields = [
                'comment_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'comment_author' => [
                    'type' => 'varchar',
                    'constraint' => 100,
                ],
                'author_ip' => [
                    'type' => 'varchar',
                    'constraint' => 50,
                ],
                'comment_body' => [
                    'type' => 'TEXT'
                ],
                'post_id' => [
                    'type' => 'INT',
                    'constraint' => 10,
                    'unsigned' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME'
                ],
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true
                ],
            ];
            return $fields;
        }
        static function get_role_fields(){
            $fields = [
                'role_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unique' => true
                ],
                'role_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 500
                ],
                'created_at' => [
                    'type' => 'DATETIME'
                ],
                'updated_at' => [
                    'type' => 'DATETIME'
                ],
                'deleted_at' => [
                    'type' => 'DATETIME'
                ]
            ];
            return $fields;
        }
        public function get_default_post(){
            $data = [
                'post_title' => 'iBlogg Default Post',
                'post_slug' => url_title('iBlogg Default Post', '-', true),
                'post_body' => 'Hello, This is a post generated dynamically login to <a href="/admin/login"> admin </a> panel to delete or <a href="/admin/editpost?postid=1"> edit </a> this post.',
                'poster_id' => 1,
            ];
            return $data;
        }
        public function get_default_comment(){
            $data = [
                'comment_author' => 'Admin',
                'author_ip' => \Config\Services::request()->getIPAddress(),
                'comment_body' => 'iBlogg default comment. this comment can deleted in Admin panel',
                'post_id' => 1
            ];
            return $data;
        }
        public function get_default_pages(){
            $data = [
                [
                    'post_title' => 'about',
                    'post_slug' => 'about',
                    'post_type' => 'page',
                    'post_body' => 'About page generated dynamically login to admin panel to delete or edit this page.',
                    'poster_id' => 1,
                ],
                [
                    'post_title' => 'contact',
                    'post_slug' => 'contact',
                    'post_type' => 'page',
                    'post_body' => 'Contact page generated dynamically login to admin panel to delete or edit this page.',
                    'poster_id' => 1,
                ]
            ];
            return $data;
        }
        public function __destruct() {
            $user_model = new UsersModel();
            $post_model = new PostsModel();
            $settings_model = new SettingsModel();
            $roles_model = new RolesModel();
            $comments_model = new CommentsModel();

            $has_admin = $user_model->admins(null, 'Admin');
            $db = new DbFunctions();
            
			if( !$has_admin ){
				$db->create_admin();
            }
            if( !$settings_model->find(1) ){
                $data = [
                    'post_settings' => '{"user_posts_per_page":"6","usersrch_posts_per_page":"6","admin_posts_per_page":"10"}',
                    'file_settings' => '{"files_per_page":"6"}',
                    'user_settings' => '{"admin_user_per_page":"8"}',
                    'comment_settings' => '{"user_comments_per_page":"10", "admin_comments_per_page":"10"}',
                    'site_settings' => '{"site_tagline" : "A simple blog running on walkify and codeigniter", "page_position" : "top"}'
                ];
                $settings_model->save($data);
                $postcount  =  $post_model->countAllResults();
                if( !$postcount ) {
                    $post_model->save($this->get_default_post());
                    $post_model->save($this->get_default_pages()[0]);
                    $post_model->save($this->get_default_pages()[1]);
                    // $post_model->insertBatch($this->get_default_pages());
                    $comments_model->save($this->get_default_comment());
                }
                if( !$roles_model->countAllResults() ){
                    $role_data = [
                        [
                            'role_id' => 1,
                            'role_name' => 'admin',
                        ],
                        [
                            'role_id' => 2,
                            'role_name' => 'mod',
                        ],
                        [
                            'role_id' => 3,
                            'role_name' => 'user',
                        ]
                    ];
                    $roles_model->insertBatch($role_data);
                }
            }
        }
    }