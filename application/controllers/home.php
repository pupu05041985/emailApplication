<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ob_start(); //we need to call PHP's session object to access it through CI
class Home extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		 $this->load->helper(array('form', 'url'));
	}

	function index(){ 
		if($this->session->userdata('logged_in')){
			  
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$this->load->view('home_view', $data);
		}else{
			//If no session, redirect to login page
			redirect('login', 'refresh');
		}
	}

	function logout(){
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('home', 'refresh');
	}
	function compose(){
		$data['msg']="";
		if($this->session->userdata('logged_in')){
		  
			$session_data = $this->session->userdata('logged_in');
			 
			$data['username'] = $session_data['username'];
			$emails="";
			$query = $this->db->query('SELECT username,email FROM users');
			foreach ($query->result() as $row){		
					$emails		.=	'"'.$row->email.'",';
			}
			$data['emails']=$emails;
			$this->load->view('compose_view', $data);
		}else{
		 //If no session, redirect to login page
		 redirect('login', 'refresh');
		}
		$this->load->helper(array('form'));

	}
	function sendemail(){
	 /*validations pending*/
	 //print_r($_POST);
		
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|doc|pdf';
		$config['max_size']     = '0';
		$this->load->library('upload', $config);

		$sender_id	="";
		
		$this->upload->do_upload('userfile');
			
		$data = array('upload_data' => $this->upload->data());

		
		$uploaded_file_name	=	$data['upload_data']['file_name'];
		$emails	=	substr($_POST['email'], 0, -2);
		$explode_emails=explode(",",$emails);
		$user_ids="";
		$query_email="";
		foreach($explode_emails as $value){
			$value = str_replace(' ', '', $value);
			$query_email.="email='".$value."' or ";
		}
		$query_email=substr($query_email,0,-3);
		$query = $this->db->query("SELECT id FROM users where $query_email");

		foreach ($query->result() as $row)
		{
			$user_ids.=$row->id.",";
		}
		$user_ids=substr($user_ids,0,-1);
		
		$data	=	array(
		'email_content' => $_POST['message'],
		'email_subject' => $_POST['subject'],
		'email_type' => 1,
		'attachment' => $uploaded_file_name		
		);
		$this->db->insert('email', $data);
		$insert_id 	= 	$this->db->insert_id();
		$sender_id	=	$_SESSION['logged_in']['id'];
		if($insert_id >	0){
			$trans_data	=	array(
				'email_id' 		=> $insert_id,
				'sender_id' 	=> $sender_id,
				'reciver_email' => $emails,
				'reciver_ids'	=>$user_ids
			);
			$this->db->insert('email_transaction', $trans_data);
			$data['msg']="Email Sent";
		}
		 
		$this->load->view('email_sent_view', $data);
		
	}
	function sent(){
		/* View Sent Emails */

		
		
		$sender_id	=	$_SESSION['logged_in']['id'];
		$this->db->select('*');
		$this->db->from('email');
		$this->db->join('email_transaction', 'email.email_id = email_transaction.email_id');
		$this->db->where('email_transaction.sender_id', $sender_id);
		$query = $this->db->get();
		
			$data_populate=$query->result();
		/* echo "<pre>";
		print_r($data_populate); */
		$data['populate']=$data_populate;
		
		$this->load->view('sent_view',$data);
		
	}
	function inbox(){
		
		$sender_id	=	$_SESSION['logged_in']['id'];
		$this->db->select('*');
		$this->db->from('email');
		$this->db->join('email_transaction', 'email.email_id = email_transaction.email_id');
		$this->db->join('users','email_transaction.sender_id=users.id');
		$this->db->like('email_transaction.reciver_ids', $sender_id);
		$query = $this->db->get();		
		$data_populate=$query->result();
		
		$data['populate']=$data_populate;
		$this->load->view('inbox_view',$data);
		
		
	}
	function view_email_inbox($id,$emailid){
		$email_id=$emailid;
		//echo "UPDATE `email` SET `status` = '2' WHERE `email`.`email_id` = $email_id";
		/*update read un read start*/
		$sql = "UPDATE `email` SET `status` = '2' WHERE `email`.`email_id` = $email_id";
		$this->db->query($sql);
		$this->db->affected_rows();
		/*update read un read end*/
		$this->db->select('*');
		$this->db->from('email');
		
		$this->db->join('email_transaction', 'email.email_id = email_transaction.email_id');
		$this->db->join('users','email_transaction.sender_id=users.id');
		$this->db->like(' email_transaction.email_id', $emailid);
		$query = $this->db->get();
		//echo "<pre>";
		//print_r($query->result());
		foreach($result = $query->result() as $data ){
			$data_populate['email']=$data->email;
			$data_populate['email_id']=$data->email_id;
			$data_populate['reciver_email']=$data->reciver_email;
			$data_populate['date_created']=date("D, d M Y H:i:s",strtotime($data->date_created));
			$data_populate['email_subject']=$data->email_subject;
			$data_populate['email_content']=$data->email_content;
			$data_populate['attachment']=$data->attachment;
			$data_populate['transaction_id']=$data->transaction_id;
			$data_populate['sender_id']=$data->sender_id;
		}
		
		$data_pop['populate']=$data_populate;
		$this->load->view('view_inbox_view',$data_pop);
	}
	function reply_email($trans_id,$id){

		$transaction_id=$trans_id;
		$emails="";
			$query = $this->db->query('SELECT username,email FROM users');
			foreach ($query->result() as $row){		
					$emails		.=	'"'.$row->email.'",';
			}
		$data['emails']=$emails;
		$this->db->select('*');
		$this->db->from('email_transaction');
		
		$this->db->join('email', 'email.email_id = email_transaction.email_id');
		$this->db->join('users','email_transaction.sender_id=users.id');
		$this->db->like(' email_transaction.transaction_id', $transaction_id);
		$query = $this->db->get();
		
		//print_r($query->result());
		foreach($result=$query->result() as $value){
			$data_populate['reply_email']=$value->reciver_email;
			$data_populate['subject']=$value->email_subject;
			$data_populate['message']=$value->email_content;
			$data_populate['from']=$value->email;
			$data_populate['attachment']=$value->attachment;
			$data_populate['date_created']=$value->date_created;
			
		}
		$data['populate']=$data_populate;
		$this->load->view('reply_view',$data);	
	}
	function replyemail(){
		$data['msg']="";
		if($_POST['file']==""){
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|doc|pdf';
			$config['max_size']     = '0';
			
			$sender_id	="";
			$this->load->library('upload', $config);
			$this->upload->do_upload('userfile');
				
			$data = array('upload_data' => $this->upload->data());

			
			$uploaded_file_name	=	$data['upload_data']['file_name'];
		}else{
			$uploaded_file_name= $_POST['file'];
		}
		$emails	=	$_POST['email'];
		
		$explode_emails=explode(",",$emails);
		$user_ids="";
		$query_email="";
		foreach($explode_emails as $value){
			$value = str_replace(' ', '', $value);
			$query_email.="email='".$value."' or ";
		}
		$query_email=substr($query_email,0,-3);
		$query = $this->db->query("SELECT id FROM users where $query_email");

		foreach ($query->result() as $row)
		{
			$user_ids.=$row->id.",";
		}
		$user_ids=substr($user_ids,0,-1);
		//$message=str_replace($_POST['message'],$_POST['message_add'])
		$message_intact=$_POST['message_intact'];
		$message_reply=$_POST['message_reply'];
		$message_add=$_POST['message_add'];
		$message=$_POST['message'];
		$message_final=str_replace($message_intact,'',$message_add,$count);
		$trim_message="<br>".$message_intact."<br>".$message_reply."<br>".$message_final."<br>";
		$data	=	array(
		'email_content' => $trim_message,
		'email_subject' => $_POST['subject'],
		'email_type' => 2,
		'attachment' => $uploaded_file_name		
		);
		$this->db->insert('email', $data);
		$insert_id 	= 	$this->db->insert_id();
		$sender_id	=	$_SESSION['logged_in']['id'];
		if($insert_id >	0){
			$trans_data	=	array(
				'email_id' 		=> $insert_id,
				'sender_id' 	=> $sender_id,
				'reciver_email' => $emails,
				'reciver_ids'	=> $user_ids,
				'reply_id'		=> $insert_id
			);
			$this->db->insert('email_transaction', $trans_data);
			$data['msg']="Email Replaied";
		} 
		 
		$this->load->view('email_sent_view', $data);
		
	}
	
	
	function forward_email($trans_id,$id){

		$transaction_id=$trans_id;
		$emails="";
		$query = $this->db->query('SELECT username,email FROM users');
		foreach ($query->result() as $row){		
				$emails		.=	'"'.$row->email.'",';
		}
		$data['emails']=$emails;
		$this->db->select('*');
		$this->db->from('email_transaction');
		
		$this->db->join('email', 'email.email_id = email_transaction.email_id');
		$this->db->join('users','email_transaction.sender_id=users.id');
		$this->db->like(' email_transaction.transaction_id', $transaction_id);
		$query = $this->db->get();
		
		//print_r($query->result());
		foreach($result=$query->result() as $value){
			$data_populate['reply_email']=$value->reciver_email;
			$data_populate['subject']=$value->email_subject;
			$data_populate['message']=$value->email_content;
			$data_populate['from']=$value->email;
			$data_populate['attachment']=$value->attachment;
			$data_populate['date_created']=$value->date_created;
			
		}
		$data['populate']=$data_populate;
		$this->load->view('forward_view',$data);	
	}
	function forwardemail(){
		$data['msg']="";
		if($_POST['file']==""){
			$config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|doc|pdf';
			$config['max_size']     = '0';
			
			$sender_id	="";
			$this->load->library('upload', $config);
			$this->upload->do_upload('userfile');				
			$data = array('upload_data' => $this->upload->data());
			$uploaded_file_name	=	$data['upload_data']['file_name'];
		}else{
			$uploaded_file_name= $_POST['file'];
		}
		$emails	=	$_POST['email'];
		
		$explode_emails=explode(",",$emails);
		$user_ids="";
		$query_email="";
		foreach($explode_emails as $value){
			$value = str_replace(' ', '', $value);
			$query_email.="email='".$value."' or ";
		}
		$query_email=substr($query_email,0,-3);
		$query = $this->db->query("SELECT id FROM users where $query_email");

		foreach ($query->result() as $row)
		{
			$user_ids.=$row->id.",";
		}
		$user_ids=substr($user_ids,0,-1);
		$message_intact=$_POST['message_intact'];
		$message_reply=$_POST['message_reply'];
		$message_add=$_POST['message_add'];
		$message=$_POST['message'];
		$message_final=str_replace($message_intact,'',$message_add,$count);
		$trim_message="<br>".$message_intact."<br>".$message_reply."<br>".$message_final."<br>";
		$data	=	array(
		'email_content' => $trim_message,
		'email_subject' => $_POST['subject'],
		'email_type' => 2,
		'attachment' => $uploaded_file_name		
		);
		$this->db->insert('email', $data);
		$insert_id 	= 	$this->db->insert_id();
		$sender_id	=	$_SESSION['logged_in']['id'];
		if($insert_id >	0){
			$trans_data	=	array(
				'email_id' 		=> $insert_id,
				'sender_id' 	=> $sender_id,
				'reciver_email' => $emails,
				'reciver_ids'	=> $user_ids,
				'reply_id'		=> $insert_id
			);
			$this->db->insert('email_transaction', $trans_data);
			$data['msg']="Email Replaied";
		} 
		 
		$this->load->view('email_sent_view', $data);
		
	}
	function email_trash($id,$email_id){
		//echo $id;
		/*update read un read start*/
		echo $sql = "UPDATE `email` SET `status` = '3' WHERE `email`.`email_id` = $email_id";
		$this->db->query($sql);
		$this->db->affected_rows();
		$data['msg']="Email moved to trash";
		//$this->load->view('email_sent_view', $data);
		redirect('home/inbox', 'refresh');
		/*update read un read end*/
		
	}

}

?>