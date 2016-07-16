<?php

class mod_users extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_user_list($limit=false, $start='', $perpage='') {
       $con 	 	= " where 1=1 ";
	   $limit_sql	= '';
        if (@$_REQUEST['user_full_name']) {
            $con.=" and first_name like '%{$_REQUEST['user_full_name']}%' or last_name like '%{$_REQUEST['user_full_name']}%'";
        }
        if (@$_REQUEST['user_email']) {
            $con.=" and email='{$_REQUEST['user_email']}'";
        }
        if ($limit) {
            $limit_sql.=" limit $start,$perpage";
        }
        $query = $this->db->query("select * from tbl_user  $con order by user_id  asc $limit_sql");
		
		//$query = $this->db->query("select * from tbl_user");
        return $query->result();
    }
	
	function deleteuser($id)
	{
		$this->db->delete('tbl_user', array('user_id' => $id)); 
	}

    function save_user() {
        
        $data 	= array(
						"first_name" 	=> trim($_POST['user_first_name']), 
						'last_name' 	=> $_POST['user_last_name'], 
						'email' 		=> $_POST['user_email'], 
						'password' 		=> md5(trim($_POST['user_password'])), 
						'city' 			=> $_POST['user_city'], 
						'zip_code' 		=> $_POST['user_zip_code'], 
						'address'		=> $_POST['user_address']);
						
        $this->db->insert("tbl_user", $data);
        return $this->db->insert_id();
    }

    function update_user($uid='') {
		
		if($_POST['user_password']!='') {
         $data 	= array(
						"first_name" 	=> trim($_POST['user_first_name']), 
						'last_name' 	=> $_POST['user_last_name'], 
						'email' 		=> $_POST['user_email'], 
						'password' 		=> md5(trim($_POST['user_password'])), 
						'city' 			=> $_POST['user_city'], 
						'zip_code' 		=> $_POST['user_zip_code'], 
						'address'		=> $_POST['user_address']);
						
		}
		else {
			
			  $data 	= array(
						"first_name" 	=> trim($_POST['user_first_name']), 
						'last_name' 	=> $_POST['user_last_name'], 
						'email' 		=> $_POST['user_email'], 
						'city' 			=> $_POST['user_city'], 
						'zip_code' 		=> $_POST['user_zip_code'], 
						'address'		=> $_POST['user_address']);
						
		}
						
			
        $this->db->update("tbl_user", $data, array('user_id' => $uid));
        return $uid;
    }

    function confirm_password() {
        $status = 1;
        $password = $_POST['old_password'];
        $user_id = $this->session->userdata('user_id');
        $sql = "SELECT * FROM users WHERE user_id = ? AND user_password = ? and user_status= ?";
        $query = $this->db->query($sql, array($user_id, $password, $status));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
     function do_update_password() {
        $password = $_POST['new_password'];
        $user_id = $this->session->userdata('user_id');
        $sql = "update users
                set user_password={$this->db->escape($password)}
                where user_id=$user_id";
        return $this->db->query($sql);
    }
	
	
	
	

}
?>
