<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller {

 function __construct()
 {
   parent::__construct();
   $this->load->model('user','',TRUE);
 }

 function index(){
   //This method will have the credentials validation
   $this->load->library('form_validation');

   $this->form_validation->set_rules('username', 'Username', 'trim|required');
   $this->form_validation->set_rules('password', 'Password', 'trim|required');

   if($this->form_validation->run() == FALSE){
    
     $this->load->view('login_view');
   }else{
	   
	$username = $this->input->post('username');
	$password = $this->input->post('password');

   //query the database
   $result = $this->user->login($username, $password);
	
	   if($result){
		 $sess_array = array();
		 foreach($result as $row){
		   $sess_array = array(
			 'id' => $row->id,
			 'username' => $row->username,
			 'email' => $row->email,
		   );
		  
		   $this->session->set_userdata('logged_in', $sess_array);
		    //print_r($sess_array);exit;
		 }
		 //return TRUE;
		// $this->load->view('login_view');
		 redirect('home', 'refresh');
	   }else{
		 //$this->form_validation->set_message('check_database', 'Invalid username or password');
		  $this->load->view('login_view');
	   }  
     //Go to private area
     
   }

 }

 function check_database($password){
   //Field validation succeeded.  Validate against database
   $username = $this->input->post('username');

   //query the database
   $result = $this->user->login($username, $password);

   if($result){
     $sess_array = array();
     foreach($result as $row){
       $sess_array = array(
         'id' => $row->id,
         'username' => $row->username
       );
       $this->session->set_userdata('logged_in', $sess_array);
     }
     return TRUE;
   }else{
     $this->form_validation->set_message('check_database', 'Invalid username or password');
     return false;
   }
 }
}
?>