<?php
use Restserver \Libraries\REST_Controller ;
Class branches extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        parent::__construct();
        $this->load->model('BengkelModel');
        $this->load->library('form_validation');
    }
    public function index_get(){
        return $this->returnData($this->db->get('branches')->result(), false);
    }
    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->BengkelModel->rules();
        if($id == null){
            array_push($rule,[
                    'field' => 'phoneNumber',
                    'label' => 'phoneNumber',
                    'rules' => 'required|valid_phoneNumber|is_unique[branches.phoneNumber]'
                ]
            );
        }
        else{
            array_push($rule,
                [
                    'field' => 'phoneNumber',
                    'label' => 'phoneNuber',
                    'rules' => 'required|valid_phoneNumber'
                ]
            );
        }
        $validation->set_rules($rule);
		if (!$validation->run()) {
			return $this->returnData($this->form_validation->error_array(), true);
        }
        $branchess = new UserData();
        $branchess->name = $this->post('name');
        $branchess->address = $this->post('address');
        $branchess->phoneNumber = $this->post('phoneNumber');
        if($id == null){
            $response = $this->BengkelModel->store($branchess);
        }else{
            $response = $this->BengkelModel->update($branchess,$id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->BengkelModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }
    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}
Class UserData{
    public $name;
    public $address;
    public $phoneNumber;
}