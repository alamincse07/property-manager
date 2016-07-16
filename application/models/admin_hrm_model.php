<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Human resource pro
*
* Author: Lucky Sharma
*         jumboteam726@gmail.com
*
* Website: http://www.jumboteam.net
*
* Created:  03.25.2014
*/

class admin_hrm_model extends CI_Model {

    /**
     * Limit
     *
     * @var string
     * */
    public $_ion_limit = NULL;

    /**
     * Offset
     *
     * @var string
     * */
    public $_ion_offset = NULL;

    /**
     * message (uses lang file)
     *
     * @var string
     * */
    protected $messages;

    /**
     * error message (uses lang file)
     *
     * @var string
     * */
    protected $errors;

    /**
     * error start delimiter
     *
     * @var string
     * */
    protected $error_start_delimiter;

    /**
     * error end delimiter
     *
     * @var string
     * */
    protected $error_end_delimiter;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->lang->load('admin_estate');
        $this->load->helper('date');

        //Load the session, CI2 as a library, CI3 uses it as a driver
        if (substr(CI_VERSION, 0, 1) == '2') {
            $this->load->library('session');
        } else {
            $this->load->driver('session');
        }

        //initialize messages and error
        $this->messages = array();
        $this->errors = array();
        $this->message_start_delimiter = "<p>";
        $this->message_end_delimiter = "</p>";
        $this->error_start_delimiter = "<p>";
        $this->error_end_delimiter = "</p>";
    }

    public function num_row_user($whr = NULL) {
        if ($whr != NULL) {
            $this->db->where($whr);
        } else {
            $this->db->where('publish', 1);
        }
        return $this->db->get('estate')->num_rows();
    }

    public function num_row_selectbox() {
        return $this->db->get('selectbox')->num_rows();
    }

    public function num_row_getName($table_name, $where = NULL) {
        if ($where != NULL) {
            $this->db->where($where);
        }
        return $this->db->get($table_name)->num_rows();
    }

    public function num_row_category() {
        return $this->db->get('category')->num_rows();
    }

    public function num_row_type() {
        return $this->db->get('estatetype')->num_rows();
    }

    public function groupListArray($id = NULL) {
    	 $this->db->select('*');
         $this->db->from('hrmgroups');
		 $query = $this->db->get()->result();
        return $query;
    }
		
	public function userListArray($id = NULL) {
    	$this->db->select('*');
        $this->db->from('staffusers');
		//$this->db->where('group_id !=',1);
			
		$query = $this->db->get()->result();
        return $query;
    }

	public function jobArray($id = NULL) {
    	$this->db->select('*');
        $this->db->from('jobs');
		$this->db->where('id', $id);
		$query = $this->db->get()->result();
        return $query;
    }


	
	// Register staff users
	public function staffRegister($username,$password,$group_id,$managerid,	$firstname,	$lastname,
									$email,	$phone,$active,	$photo,$jobname, $hsalary, $weeklyhours,
									$startdate,	$enddate) {
        if ($username != NULL) {
		try{
            $data = array(
                'username' =>$username,
				'password'=>$password,
				'email'=>$email,
				'group_id'=>$group_id,
				'first_name'=>$firstname,
				'last_name'=>$lastname,
				'phone'=>$phone,
				'parent_id'=>$managerid,
				'active'=>$active,
				'photo'=>$photo,
				'created'=> date("Y-m-d H:i:s")
            );
			$this->db->insert('staffusers', $data);
			$userid = $this->db->insert_id();
			
			$datajob=array(
				    'user_id' =>$userid,
					'name'=>$jobname,
					'hourly_salary'=>$hsalary,
					'expected_hours'=>$weeklyhours,
					'start_date'=>$startdate,
					'end_date'=>$enddate,
					'created'=> date("Y-m-d H:i:s"),
					'modified'=> date("Y-m-d H:i:s")
        	);
			$query = $this->db->insert('jobs', $datajob);
      	
		}catch(Exception $e)
		{
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		
		}
		
        } else {
            $this->set_error('hrm_staff_error');
            return false;
        }
         $this->set_message('hrm_staff_success');
        return  $query;
    }
	
	
	
	// Register JOb
	public function jobRegister($userid,$jobname,$hsalary,$weeklyhours,$startdate,$enddate) {
        if ($userid != NULL) {
            $data = array(
                'user_id' =>$userid,
				'name'=>$jobname,
				'hourly_salary'=>$hsalary,
				'expected_hours'=>$weeklyhours,
				'start_date'=>$startdate,
				'end_date'=>$enddate,
				'created'=> date("Y-m-d H:i:s"),
				'modified'=> date("Y-m-d H:i:s")
            );
			$query = $this->db->insert('jobs', $data);
        } else {
            $this->set_error('hrm_staff_error');
            return false;
        }
        //$this->set_message('hrm_staff_success');
        return true;
    }
	
	
    public function jobUpdate($username,$jobname,$hsalary,	$weeklyhours,$startdate,$enddate, $id = NULL) {
        if ($name != NULL and $id != NULL) {
            $data = array(
                'user_id' =>$userid,
				'name'=>$jobname,
				'hourly_salary'=>$hsalary,
				'expected_hours'=>$weeklyhours,
				'start_date'=>$startdate,
				'end_date'=>$enddate,
				'modified'=> date("Y-m-d H:i:s")
            );
            $this->db->where('id', $id);
            $query = $this->db->update('jobs', $data);
        } else {
            $this->set_error('estate_resident_error');
            return false;
        }
        $this->set_message('estate_resident_success');
        return true;
    }

		
	public function hrmStaffListArray($id = NULL) {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('staffusers');
            $query = $this->db->where('id', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('staffusers');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }
	
	public function hrmJobListArray($id = NULL) {
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('jobs');
            $query = $this->db->where('id', $id)->get()->result_array();
        } else {
            $this->db->select('*');
            $this->db->from('jobs');

            if (($this->_ion_limit != NULL) or ($this->_ion_offset != NULL)) {
                $this->db->limit($this->_ion_limit, $this->_ion_offset);
                $this->_ion_limit = NULL;
                $this->_ion_offset = NULL;
            }

            $query = $this->db->get()->result();
        }
        return $query;
    }
	
	
	/* Check if username exists */
	public function userNameExist($username) {
    	$this->db->select('*');
        $this->db->from('staffusers');
		$this->db->where('username =',$username);
		$query = $this->db->get()->result();
        return $query;
    }
	
	/* Check if email exists */
	public function emailExist($email) {
    	$this->db->select('*');
        $this->db->from('staffusers');
		$this->db->where('email =',$email);
		$query = $this->db->get()->result();
        return $query;
    }
	
	
	public function hrmTimesheet($id = NULL) {
            
        if ($id != NULL) {
            $this->db->select('*');
            $this->db->from('timesheets');
            $query = $this->db->where('user_id', $id)->get()->result_array();
            //echo "<BR>".$this->db->last_query();exit;
            //$query = $this->db->get()->result_array();
			//$query = $this->db->get()->result();
        }else{
            $this->db->select('*');
            $this->db->from('timesheets');
            $query = $this->db->get()->result_array();
            //echo "<BR>".$this->db->last_query();exit;
        }
        
        return $query;
    }
	
	
	
}