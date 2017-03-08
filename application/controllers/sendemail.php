<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SendEmail extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('user','',TRUE);
    $this->load->helper(array('form', 'url'));
 }

 function index()
 {
	$this->form_validation->set_rules('username', 'Username', 'trim|required');
	$this->form_validation->set_rules('password', 'Password', 'trim|required');
	$config['upload_path'] = './uploads/';
	$config['allowed_types'] = 'gif|jpg|png|doc|pdf';
	$config['max_size']     = '100';
	$config['max_width'] = '1024';
	$config['max_height'] = '768';
	//$this->load->library('upload', $config);
print_r($_POST);	

 }

 
}
?>