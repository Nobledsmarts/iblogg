<?php namespace App\Controllers\Api;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use App\Models\SettingsModel;


class Settings extends Controller {
    use ResponseTrait;
    public function __construct() {
        $this->settings = new SettingsModel();
    }
    public function save(){
        $request = $this->request;
        if($request->isAJAX()){
            $settings = $request->getVar('settings');
            foreach($settings as $key => $value){
                $settings[$key] = json_encode($value);
            }
            $settings['id'] = 1;
            $save_ok = $this->settings->save($settings);
            if( $save_ok ){
                return $this->respond([
                    'message' => 'success'
                ]);
            }
        }
    }
}