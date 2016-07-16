<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  Real Estate CMS Pro
 *
 * Author: Lucky Sharma
 *         jumboteam726@gmail.com
 *
 * Website: http://jumboteam.net
 *
 * Created:  04.15.2013
 */
class vacations extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->database();
        $this->lang->load('auth');
        $this->lang->load('admin_estate');
        $this->load->helper('language');
        $this->load->model('admin_estate_model');
		$this->load->model('admin_hrm_model');
		$this->load->model('admin_hrm_timesheet_model');
        $this->load->model('admin_vacation_model');
    }

    public function index() {
        $this->is_login(2);
        $this->data['ptitle'] = lang('estate_index_title');

        $this->admin_estate_model->paginationLimit(8, 0);
        $this->data['estates'] = $this->admin_estate_model->estates(NULL, array('id >=' => '0'), TRUE);

        $this->admin_estate_model->paginationLimit(8, 0);
        $this->data['blogs'] = $this->admin_estate_model->blogPage(NULL, 1, array('type' => '0'), TRUE);

        $this->admin_estate_model->paginationLimit(8, 0);
        $this->data['users'] = $this->admin_estate_model->getUser(NULL, TRUE);

        $this->data['imageCount'] = count(glob('uploads/{*.jpg,*.gif,*.png}', GLOB_BRACE));
        $this->data['estateCount'] = $this->admin_estate_model->num_row_getName('estate');
        $this->data['blogCount'] = $this->admin_estate_model->num_row_getName('blogpage', array('type' => '0'));
        $this->data['userCount'] = $this->admin_estate_model->num_row_getName('users');
        $this->data['showcaseCount'] = $this->admin_estate_model->num_row_getName('estate', array('showcase' => "1", 'publish' => "1"));

        $this->tmadmin->tmView('index', $this->data);
    }

    public function is_login($level = 2) {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin($level)) {
            $this->load->helper('admin');
            redirect(site_url(), 'refresh');
        }
    }

 
    public function clearCache() {
        $this->load->library('clr_output');
        $this->clr_output->clear_all_cache();
        redirect_back();
    }
    

    /*function paginationCreate($baseUrl, $totalRow, $perPage) {
        $pageConfig = array(
            'base_url' => site_url() . $baseUrl,
            'total_rows' => $totalRow,
            'per_page' => $perPage,
            'num_links' => 3,
            'full_tag_open' => '<div class="pagination"><ul>',
            'full_tag_close' => '</ul></div>',
            'first_link' => false,
            'last_link' => false,
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'prev_link' => '<i class="icon-double-angle-left"></i> Previous',
            'prev_tag_open' => '<li class="prev">',
            'prev_tag_close' => '</li>',
            'next_link' => 'Next <i class="icon-double-angle-right"></i>',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a href="#">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>'
        );

        $this->pagination->initialize($pageConfig);
        return $this->pagination->create_links();
    }*/
    
    function paginationCreate($baseUrl, $totalRow, $perPage,$extraUrl) {
        
        $pageConfig = array(
            'base_url' => site_url() . $baseUrl,
            'total_rows' => $totalRow,
            'per_page' => $perPage,
            'num_links' => 3,
            'full_tag_open' => '<div class="pagination"><ul>',
            'full_tag_close' => '</ul></div>',
            'first_link' => false,
            'last_link' => false,
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'prev_link' => '<i class="icon-double-angle-left"></i> Previous',
            'prev_tag_open' => '<li class="prev">',
            'prev_tag_close' => '</li>',
            'next_link' => 'Next <i class="icon-double-angle-right"></i>',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a href="#">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
        );
        
        if($extraUrl)
        {
                $pageConfig['suffix'] = '?'.$extraUrl;
                $pageConfig['first_url'] = site_url() . $baseUrl.'0?'.$extraUrl;
        }
                    
        $this->pagination->initialize($pageConfig);
        return $this->pagination->create_links();
    }
    	
        /*
         * 
         * 
         */
    public function add($id) {
            $this->is_login(3);
            if ($id != NULL) {
                $this->data['ptitle'] = lang('hrm_page_staff_update_title');                
                $this->data['title'] = lang('hrm_sidemenu_vacation_edit_vacation');
                $this->data['sub_title'] = lang('hrm_sidemenu_vacation_edit_form_heading');
                 
                $edit_data = $this->admin_vacation_model->vacationEdit($id);
                $this->data['fill_data'] = $edit_data[0];
                
            } else {
                $this->data['ptitle'] = lang('hrm_page_staff_add_title');
                $this->data['title'] = lang('hrm_sidemenu_vacation_new_vacation');
                $this->data['sub_title'] = lang('hrm_sidemenu_vacation_add_form_heading');
            }
            
            $this->data['userlist'] = $this->admin_hrm_model->userListArray();
            $this->data['statuslist'] = $this->admin_hrm_timesheet_model->statusListArray();
            
            //validation
            $this->form_validation->set_rules('data', '$this->lang->line("date")' ,'required');
            if ($this->form_validation->run() == true)
            {                   
                    if( $this->input->post('id'))
                    {
                    	$vacation_data = array(
                    	'user_id' => $this->input->post('user_id'),
    					'approval_status_id' => $this->input->post('approval_status_id'),
                    	);
                    	$dates = $this->input->post('data');
                    	
                    	$vacation_data['date'] =  $dates['Vacation']['date']['0'];
                    	$this->admin_vacation_model->vacationUpdate($vacation_data,$this->input->post('id'));
                    }
                	else
                    {
                    	$dates = $this->input->post('data');
                    	$vacation_data = array(
                    	'user_id' => $this->input->post('user_id'),
    					'approval_status_id' => $this->input->post('approval_status_id')
                    	);
                    	
                    	foreach ($dates['Vacation']['date'] as $date) {
                    		
							if(!empty($date)){
								$vacation_data['date'] =  $date;
								$this->admin_vacation_model->vacationInsert($vacation_data); 
							}
						}
                    }
                    redirect_back();
            }
            else
            {

                //$this->session->set_flashdata('message', $this->admin_hrm_timesheet_model->errors());
                //$this->session->set_flashdata('success', $this->admin_hrm_timesheet_model->messages());
            
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_vacation_model->errors() ? $this->admin_vacation_model->errors() : $this->session->flashdata('message')));
                $this->data['success'] = $this->session->flashdata('success');
            
                $this->tmadmin->tmView('hrm_panel/vacations_add', $this->data);
                
            }      
            
        
            //$this->tmadmin->tmView('vacations/vacations_add.php', $this->data);
	}
	
		/*
         * 
         * 
         */
        public function all(){
            
                        
            
            $this->is_login(3);
            
            $search = array(); 
            $arrQueryString = array();
            $strWhere = null;
            
            if($this->input->get('search-all'))
            {
                /*$search[] = 'timesheets.note Like "%'.$this->input->get('search-all').'%"';
                $arrQueryString['search-all']=$this->input->get('search-all');
                $strWhere = (count($search) ? '' . implode(' OR ', $search).'' :'');  
                */
            
            }
            if($this->input->get('filter'))
            {
                if($this->input->get('user_id'))
                {
                    $search[] = 'vacations.user_id ='.$this->input->get('user_id');
                    $arrQueryString['user_id']=$this->input->get('user_id');
                }    
                if($this->input->get('approval_status_id'))
                {
                   $search[] = 'vacations.approval_status_id='.$this->input->get('approval_status_id');
                   $arrQueryString['approval_status_id']=$this->input->get('approval_status_id');
                } 
                if($this->input->get('sdate') && $this->input->get('edate'))
                {
                    $search[] = '(DATE_FORMAT(vacations.date,"%Y-%m-%d") >= "'.$this->input->get('sdate').'"'
                              .' AND DATE_FORMAT(vacations.date,"%Y-%m-%d") <= "'.$this->input->get('edate').'")';
                    $arrQueryString['edate']=$this->input->get('edate');
                    $arrQueryString['sdate']=$this->input->get('sdate');
                }
                else if($this->input->get('sdate'))
                {  
                    $search[] = 'DATE_FORMAT(vacations.date,"%Y-%m-%d") = "'.$this->input->get('sdate').'"';
                    $arrQueryString['start_time']=$this->input->get('sdate');
                }    
                $arrQueryString['filter']=$this->input->get('filter');
                
                //$strWhere = (count($search) ? '"' . implode(' OR ', $search).'"' :'');
                $strWhere = (count($search) ? '' . implode(' OR ', $search).'' :'');
            }
            
            $query_string = urldecode(http_build_query($arrQueryString, '', '&amp;'));
            
            $this->admin_vacation_model->paginationLimit(1, $this->uri->segment(3));
            $this->data['pagination'] = $this->paginationCreate('/vacations/all/', $this->admin_vacation_model->num_row_vacation($strWhere), 1,$query_string);
            $this->data['userlist'] = $this->admin_hrm_model->userListArray();
            $this->data['statuslist'] = $this->admin_hrm_timesheet_model->statusListArray();	
            $this->data['vacation'] = $this->admin_vacation_model->getVacation(null,$strWhere);
            $this->data['ptitle'] = lang('vacation_page_all_title');
            $this->tmadmin->tmView('hrm_panel/vacation_all', $this->data);
        }
        
        /*
         * 
         * 
         */
        public function set_status($status, $id){
            
            // do we have the right userlevel?
            $this->is_login(3);
            if ($status) {
                $this->admin_vacation_model->setStatus($status,$id);
                $this->session->set_flashdata('message', lang('blog_message_deactive'));
            }
            redirect_back();
        }
        
        /*
         * 
         * 
         */
        public function ajaxSetStatus($status, $id){
            
            // do we have the right userlevel?
            $this->is_login(3);
            if ($status) {
                $result = $this->admin_vacation_model->setStatus($status,$id);
            }
            echo ($result? 'Ok' : '' );
        }
        

        public function calendar($userid=NULL) {
	/*
                echo "<PRE>++++";
                    print_r($this->session->all_userdata());                    
                echo "</PRE>++++";
                exit;*/
            $this->data['ptitle'] = lang('hrm_sidemenu_vacation_calendar_view_title');
            $this->data['title'] = lang('hrm_sidemenu_vacation_calendar_view_title');
            
            
            
                $arrLoginUser = $this->session->all_userdata();
                
		if(!isset($arrLoginUser['user_id'])) {
			$user = $arrLoginUser['user_id'];
		}
		
                $arrAllUsers = $this->admin_hrm_model->userListArray();
                
                $arrUsers = array();
                foreach($arrAllUsers as $objUser){
                    $arrUsers[$objUser->id] = $objUser->first_name. ' ' . $objUser->last_name ;
                }
                
            
                $this->data['calendarVacation'] = $this->admin_vacation_model->hrmVacation();
               
		$calendarData = array();
                
                foreach($this->data['calendarVacation'] as $calendarVacation) {
                   
                                $statusUpdate = '<div class="status-icons">';
				//submitted
				$statusUpdate .= '<a href="javascript:void(0);" onClick="changeVacationToSubmitted(\''.$calendarVacation['id'].'\',\''.site_url().'\')" class="change-status submitted ';
				
				if($calendarVacation['approval_status_id']=='1'){
					$statusUpdate .= 'status-active ';
				}
				$statusUpdate .= ' row-submitted-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= ' row-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '"  title="Submitted" alt="Submitted" timesheetid="';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '" statusid="1" ><i class="icon-question"></i></a>';
                                
                                //approved
				$statusUpdate .= '<a href="javascript:changeVacationToApproved(\''.$calendarVacation['id'].'\',\''.site_url().'\')" class="change-status approved ';
				if($calendarVacation['approval_status_id']=='2'){
					$statusUpdate .= 'status-active ';
				}
				$statusUpdate .= ' row-approved-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= ' row-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '"  title="Submitted" alt="Rejected" timesheetid="';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '" statusid="2" ><i class="icon-ok"></i></a>';
				
				//rejected
				$statusUpdate .= '<a href="javascript:changeVacationToRejected(\''.$calendarVacation['id'].'\',\''.site_url().'\')" class="change-status rejected ';
				if($calendarVacation['approval_status_id']=='3'){
					$statusUpdate .= 'status-active ';
				}
				$statusUpdate .= ' row-rejected-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= ' row-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '"  title="Submitted" alt="Submitted" timesheetid="';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '" statusid="3" ><i class="icon-ban-circle"></i></a>';
				
				//revision
				$statusUpdate .= '<a href="javascript:changeVacationToRevision(\''.$calendarVacation['id'].'\',\''.site_url().'\')" class="change-status revision ';
				if($calendarTimesheet['approval_status_id']=='4'){
					$statusUpdate .= 'status-active ';
				}
				$statusUpdate .= ' row-revision-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= ' row-';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '"  title="Needs Review" alt="Needs Review" timesheetid="';
				$statusUpdate .= $calendarVacation['id'];
				$statusUpdate .= '" statusid="4" ><i class="icon-refresh"></i></a>';

				$statusUpdate .= '<span class="spinner row-'. $calendarVacation['id'] .'"></i></span>';
				$statusUpdate .= '</div>';
                                
                    
                    
                    $calendarData[] = array(
					'id' => $calendarVacation['id'],
					'title'=> $arrUsers[$calendarVacation['user_id']],
					'start'=>$calendarVacation['date'],
					'end' => $calendarVacation['date'],
					'allDay' => true,
					'url' => site_url().'vacations/view/'.$calendarVacation['id'],
					'details' => "",
					'className' => 'status'.$calendarVacation['approval_status_id']. ' ts-'.$calendarVacation['id'],
					'toolTip' => "",
					'statusUpdate' => $statusUpdate
			);
                    /*echo "<PRE>";
                        print_r($calendarData);
                    echo "</PRE>";    exit;*/
                }
                $this->data['calendarVacation'] = $calendarData;
		
		$this->tmadmin->tmView('hrm_panel/calendar_vacation', $this->data);
	}
        
        /*
         * 
         * 
         */
        public function view($id) {
                
            
            if ($id != NULL) {
                $edit_data = $this->admin_vacation_model->vacationEdit($id);
               
               
                $edit_data[0]->date= date('M j, Y',strtotime($edit_data[0]->date));
                $this->data['fill_data'] = $edit_data[0];
                
            } 
            $this->data['ptitle'] = lang('hrm_sidemenu_vacation_details');
            $this->data['title'] = lang('hrm_sidemenu_vacation_details');
            $this->data['sub_title'] = lang('hrm_sidemenu_vacation_details');
            
            
                
            
            $this->data['userlist'] = $this->admin_hrm_model->userListArray();
            
            $arrUsers = array();
            foreach($this->data['userlist'] as $user){
                $arrUsers[$user->id] = $user->first_name. ' ' . $user->last_name ;
            }
            $this->data['userlist'] = $arrUsers;
            
                    
            $this->data['statuslist'] = $this->admin_hrm_timesheet_model->statusListArray();
        
            $this->tmadmin->tmView('hrm_panel/vacation_view', $this->data);
	}
}