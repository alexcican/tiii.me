<?php
namespace Modules\ModSample\Controllers;
use Resources\Controller;

class Home extends Controller {
    
    public function index(){
        
        $arr = explode('\\', __NAMESPACE__);
        
        $data = array(
            'title' => $this->model->data->hello() . $arr[1],
            'moduleName' => $arr[1]
        );
        
        $this->output('template', $data );
    }
}