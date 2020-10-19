<?php namespace App\Controllers\Api;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Models\FilesModel;
use CodeIgniter\Files\File;

class Files extends Controller {
    use ResponseTrait;
    public function index(){
        return json_encode(['testing']);
    }
    public function __construct() {
        $this->file_model = new FilesModel();
    }
    public function handleFiles() {
        $request = $this->request;
        if($request->isAJAX()){
            $session = session();
            $file_id = $request->getPost('file_id');
            $file_path = $request->getPost('file_path');
            $is_edit = $file_id ?? false;
            $files = $request->getFileMultiple('attachments');
            $messages = [];
            $err_msgs = [];
            if($files){
                foreach($files as $attachment){
                    if($attachment->isValid() && !$attachment->hasMoved()){
                        $name = $attachment->getName();
                        // $file_ext = $attachment->guessExtension();
                        // $mimeType = $attachment->originalMemeType();
                        $query = $this->file_model->asArray()->like('file_path', $name, 'after')->orderBy('file_id', 'desc')->first();
                        
                        if( $query && !$is_edit){
                            $db_filename = $query['file_path'];
                            $split_name = explode('-', $db_filename);
                            $new_name = (int) $split_name[count($split_name) - 1];
                            $name = $new_name;
                        } elseif($is_edit) {
                            $edited_query = $this->file_model->find($file_id);
                            $the_filename = $edited_query['file_path'];
                            if($the_filename != $file_path){
                                $split_name = explode('-', $the_filename);
                                $new_name = (int) $split_name[count($split_name) - 1];
                                $name = $new_name;
                            }
                        }
                        $upload_ok = $attachment->move(ROOTPATH . 'public/uploads/', $name);
                        if( $upload_ok ){
                            $uploaded_file = new File(ROOTPATH . 'public/uploads/' . $name);
                            $path = 'uploads/' . $name;
                            $messages[$name] = 'success';
                            $data = [
                                    'file_path' => $is_edit ? $file_path : $uploaded_file->getRealPath(),
                                    'file_uploader_id' => $session->get('data')['user_id'],
                                    'file_ext' => $uploaded_file->guessExtension(),
                                    'file_size' => $uploaded_file->getSize(),
                                    'file_type' => $uploaded_file->getMimeType()
                                ];
                            if($is_edit){
                                $data['file_id'] = $file_id;
                            }
                            $data = $this->file_model->save($data);
            
                        } else {
                            $err_msgs[] = $name;
                            $messages[$name] = 'failed';
                        }
                    }

                }
                return $this->respond([
                    'errorMessages' => $err_msgs,
                    'messages' => $messages
                ], 200);
            }
        }
    }
    public function delete($file_id = null){
        $request = $this->request;
        if( $request->isAJAX() && $request->getMethod() == 'delete' ){
            if( $file_id ){
                $data = $this->file_model->find($file_id);
                if( $data ){
                    $delete_ok =  $this->file_model->delete($file_id);
                    if( $delete_ok ) {
                        return $this->respondDeleted([
                            'data' => $data,
                            'message' => 'success'
                        ]);
                    }
                }
                return $this->failNotFound('Not Found');
            } else {
                $data = $this->file_model->findColumn('file_id');
                $this->file_model->delete($data);
                return $this->respondDeleted([
                    'data' => $data,
                    'message' => 'success'
                ]);
            }
        }
    }
}
?>