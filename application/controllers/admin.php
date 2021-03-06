<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

/**
 * @property admin_estate_model $admin_estate_model
 */
class admin extends CI_Controller
{

    function __construct()
    {
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
        //$this->load->library('grocery_CRUD');
    }

    public function index()
    {
        $this->session->set_userdata('page', 0);
        $this->is_login(2);

        redirect('/admin/estateAll', 'refresh');exit;
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

    public function is_login($level = 2)
    {
        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$this->ion_auth->is_admin($level)) {
            $this->load->helper('admin');
            //todo: fix that uncomment later
            //redirect(site_url(), 'refresh');
            $this->session->set_userdata('not_admin', 1);
        }else{
            $this->session->set_userdata('not_admin', 0);
        }

        #\application\helpers\Generic::_setTrace($_SESSION);
    }

    // Real Estate Panel - Adding, label, confirm, deleting
    public function estateAll()
    {
        $this->is_login(3);

        $not_admin=$this->session->userdata('not_admin');
        //\application\helpers\Generic::_setTrace($admin);

        $whr=null;
        if($not_admin && $this->ion_auth->get_user_id()){

            $whr= array('addedUserID =' => $this->ion_auth->get_user_id());
        }
        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateAll/', $this->admin_estate_model->num_row_user(), 10);

        $this->data['estates'] = $this->admin_estate_model->estates(null,$whr);
        $this->data['ptitle'] = lang('estate_page_all_title');
        $this->session->set_userdata('page', 1);
        $this->tmadmin->tmView('estate_panel/estate_all', $this->data);
    }

    public function estate_getdatatableajax()
    {
        $this->load->library('datatables');

        $act_url = ($estate->publish) ? site_url() . "admin/estateDeactivate/$1" : site_url() . "admin/estateActivate/$1";
        $pub_url = ($estate->publish) ? 'icon-check' : 'icon-check-empty';
        $show_url = ($estate->showcase) ? site_url() . "admin/estateShowcaseOff/$1" : site_url() . "admin/estateShowcaseOn/$1";
        $bookmark = ($estate->showcase) ? 'icon-bookmark' : 'icon-bookmark-empty';

        $opt = "<center><div class=\"btn-group\" style=\"margin:0;\">
    				<a class=\"tooltp btn btn-warning btn-xs\" data-toggle=\"tooltip\" title=\"" . lang('edit') . "\" href=\"" . site_url() . "admin/estateEdit/$1\"><i class=\"fa fa-edit\"></i></a>
    				<a class=\"tooltp btn btn-success btn-xs\" data-toggle=\"tooltip\" title=\"" . lang('activate') . "\" href=\"" . $act_url . "\"><i class=\"fa " . $pub_url . "\"></i></a>
    				<a class=\"tooltp btn btn-primary btn-xs\" data-toggle=\"tooltip\" title=\"" . lang('showcase_active') . "\" href=\"" . $show_url . "\"><i class=\"fa " . $bookmark . "\"></i></a>
    				<a class=\"tooltp btn btn-danger btn-xs\" data-toggle=\"tooltip\" title=\"" . lang('delete') . "\" href=\"" . site_url() . "admin/estateDelete/$1\" role=\"button\" data-bb=\"confirm\"><i class=\"fa fa-trash-o\"></i></a>
    			</div></center>";

        $this->datatables
            ->select('estate.id,estate.photo,estate.title,estate.price,estatetype.estateName,category.catName,estate.country,estate.province,estate.city,estate.postal_code,estate.addedDate')
            ->from('estate')
            ->join('category', 'estate.catID = category.cid', 'LEFT')
            ->join('estatetype', 'estate.estateTypeID = estatetype.eid', 'LEFT')
            ->order_by('estate.id', 'DESC')
            ->unset_column('estate.id')
            ->unset_column('estate.photo')
            ->add_column("Photo", '<img src="/uploads/thumbs/$1" border="0" width="80" />', "estate.photo")
            ->add_column("Action", $opt, "estate.id");


        echo $this->datatables->generate();
    }

    public function estateShowcase()
    {
        $this->is_login(3);
        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateShowcase/', $this->admin_estate_model->num_row_user(array('publish' => 1, 'showcase' => 1)), 10);

        $whr = array(
            'publish' => 1,
            'showcase' => 1
        );
        $this->data['estates'] = $this->admin_estate_model->estates(NULL, $whr);
        $this->data['ptitle'] = lang('estate_page_showcase_title');
        $this->session->set_userdata('page', 1);
        $this->tmadmin->tmView('estate_panel/showcase', $this->data);
    }
    public function estateAdd()
    {
        $this->is_login(3);
        $this->data['ptitle'] = lang('estate_page_add_title');

        $this->load->helper('cms');

        //display the create user form
        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
        $this->data['success'] = $this->session->flashdata('success');

        $this->load->helper('country');
        $this->session->set_userdata('page', 1);
        $this->data['address'] = array(
            'name' => 'address',
            'id' => 'address',
            'type' => 'text',
            'class' => 'span',
            'value' => $this->form_validation->set_value('address'),
        );

        $this->data['postal_code'] = array(
            'name' => 'postal_code',
            'id' => 'postal_code',
            'type' => 'text',
            'class' => 'span',
            'value' => $this->form_validation->set_value('postal_code'),
        );

        $this->data['lat'] = array(
            'name' => 'lat',
            'id' => 'lat',
            'required' => 'required',
            'type' => 'text',
            'class' => 'span',
            'value' => $this->form_validation->set_value('lat'),
        );

        $this->data['lon'] = array(
            'name' => 'lon',
            'id' => 'lon',
            'required' => 'required',
            'type' => 'text',
            'class' => 'span',
            'value' => $this->form_validation->set_value('lon'),
        );

        $this->data['country'] = countryList();
        $this->data['province'] = array();
        $this->data['city'] = array(
            'name' => 'city',
            'id' => 'city',
            'required' => 'required',
            'type' => 'text',
            'value' => $this->form_validation->set_value('city'),
        );

        $this->session->set_userdata('page', 1);
        $this->tmadmin->tmView('estate_panel/estate_add', $this->data);
    }

    public function estateAdd2()
    {
        $optional_rate=array();
        $this->is_login(3);
        $this->data['ptitle'] = lang('estate_page_add_title');
        //if(isset($_REQUEST['country'])){(print_r($_REQUEST));}
        if ($this->input->get('country'))
            $this->data['get_country'] = $this->input->get('country');
        if ($this->input->get('postal_code'))
            $this->data['get_postal_code'] = $this->input->get('postal_code');
        if ($this->input->get('province'))
            $this->data['get_province'] = $this->input->get('province');
        if ($this->input->get('city'))
            $this->data['get_city'] = $this->input->get('city');
        if ($this->input->get('address'))
            $this->data['get_address'] = $this->input->get('address');
        if ($this->input->get('lat') && $this->input->get('lon'))
            $this->data['get_gps'] = $this->input->get('lat') . ", " . $this->input->get('lon');



        if(isset($_REQUEST['start_date'][0],$_REQUEST['end_date'][0]) && $_REQUEST['start_date'][0]!='' && $_REQUEST['end_date'][0]!=''){

            foreach($_REQUEST['start_date'] as $k=>$val){
                if(!empty($_REQUEST['start_date'][$k]) && !empty($_REQUEST['end_date'][$k]) ){
                    $single_rate['start_date']=$_REQUEST['start_date'][$k];
                    $single_rate['end_date']=$_REQUEST['end_date'][$k];
                    $single_rate['rate_title']=$_REQUEST['rate_title'][$k];
                    $single_rate['min_los']=$_REQUEST['min_los'][$k];
                    $single_rate['nightly']=$_REQUEST['nightly'][$k];
                    $single_rate['weekly']=$_REQUEST['weekly'][$k];
                    $optional_rate[]=$single_rate;
                }

            }
        }



        $this->load->helper('cms');

        //validate form input
        $this->form_validation->set_rules('title', $this->lang->line('estate_title'), 'xss_clean');
        $this->form_validation->set_rules('price', $this->lang->line('estate_price'), 'alpha_numeric|integer');
        $this->form_validation->set_rules('photo', $this->lang->line('estate_photo'), 'xss_clean');
        $this->form_validation->set_rules('category', $this->lang->line('estate_category'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'address' => $this->input->post('address'),
                'gps' => $this->input->post('gps'),
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'price' => $this->input->post('price'),
                /*Advance price rates*/
                'optional_rates' => $optional_rate,
                //'start_date' => $this->input->post('start_date'),
                //'end_date' => $this->input->post('end_date'),
                'default_min_los' => $this->input->post('default_min_los'),
                'default_nightly' => $this->input->post('default_nightly'),
                'default_weekly' => $this->input->post('default_weekly'),
                /*End Advance price rates*/
                'country' => $this->input->post('country'),
                'province' => $this->input->post('province'),
                'city' => $this->input->post('city'),
                'postal_code' => $this->input->post('postal_code'),
                'category' => $this->input->post('category'),
                'estatetype' => $this->input->post('estatetype'),
                'user_id' => $this->session->userdata('user_id'),
                'photo' => ($this->input->post('images')),
                'checkbox' => $this->input->post('checkbox'),
                'telephone' => $this->input->post('telephone'),
                // this is  url of details page of owner
                'gsm' => $this->input->post('gsm'),
                'email' => $this->input->post('email'),
                'guest_count' => $this->input->post('guest_count'),
                'sleep' => $this->input->post('sleep'),
                'room' => $this->input->post('room'),
                'bathroom' => $this->input->post('bathroom'),
                'heating' => $this->input->post('heating'),
                'squaremeter' => $this->input->post('squaremeter'),
                'squarefoot' => $this->input->post('squarefoot'),
                'buildstatus' => $this->input->post('buildstatus'),
                'floor' => $this->input->post('floor'),
                'publish' => $this->input->post('publish'),
                'fk' => $this->input->post('fk'),
                'vrbo' => $this->input->post('vrbo'),
                'ht' => $this->input->post('ht'),
                'vast' => $this->input->post('vast'),
                'bk' => $this->input->post('bk'),
                'airbnb' => $this->input->post('airbnb'),
                'rm' => $this->input->post('rm'),
                'hw' => $this->input->post('hw'),
                'otalo' => $this->input->post('otalo'),
                'showcase' => $this->input->post('showcase'),
                'not_available_date_list' => $this->input->post('not_available_date_list'),
            );
            if ($data['title'] == "")
                $data['title'] = "Vacation Rental";
            if ($data['price'] == "")
                $data['price'] = 0;
            if ($data['content'] == "")
                $data['content'] = '';
            if ($data['telephone'] == "")
                $data['telephone'] = '';
            if ($data['gsm'] == "")
                $data['gsm'] = '';
            if ($data['email'] == "")
                $data['email'] = '';
            if ($data['room'] == "")
                $data['room'] = '';
            if ($data['bathroom'] == "")
                $data['bathroom'] = '';
            if ($data['heating'] == "")
                $data['heating'] ='';
            if ($data['squaremeter'] == "")
                $data['squaremeter'] = '';
            if ($data['squarefoot'] == "")
                $data['squarefoot'] = '';
            if ($data['buildstatus'] == "")
                $data['buildstatus'] = '';
            if ($data['floor'] == "")
                $data['floor'] = '';
            if ($data['photo'] == false)
                $data['photo'] = array(NULL);
        }


       // print_r($data);die;
        if ($this->form_validation->run() == true && $this->admin_estate_model->register($data)) {
            //die('saved');
            $this->session->set_flashdata('message', $this->admin_estate_model->errors());
            $this->session->set_flashdata('success', $this->admin_estate_model->messages());
            redirect('admin/estateAll');

        }
        else {

            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['checkboxCount'] = $this->admin_estate_model->propertyField()->num_rows();
            $this->data['checkboxName'] = $this->admin_estate_model->propertyField()->result();

            $this->data['attrib'] = $this->admin_estate_model->estateAttribArray();
            $this->data['type'] = array();

            foreach ($this->data['attrib'] as $arrFields) {
                $this->data['attrib'][$arrFields->eaid] = $arrFields->attribName;
            }


            $this->load->helper('country');

            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'required'=>'required',
                'value' => $this->form_validation->set_value('title')
            );

            $this->data['address'] = array(
                'name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'class' => 'span',
                'value' => $this->form_validation->set_value('address'),
            );

            $this->data['postal_code'] = array(
                'name' => 'postal_code',
                'id' => 'postal_code',
                'type' => 'text',
                'class' => 'span',
                'value' => $this->form_validation->set_value('postal_code'),
            );

            $this->data['gps'] = array(
                'name' => 'gps',
                'id' => 'gps',
                'type' => 'text',
                'class' => 'span',
                'value' => $this->form_validation->set_value('gps'),
            );

            $this->data['content'] = array(
                'name' => 'content',
                'id' => 'content',
                'type' => 'text',
                'value' => $this->form_validation->set_value('content'),
            );
            $this->data['price'] = array(
                'name' => 'price',
                'id' => 'price',
                'type' => 'text',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('price'),
            );
            /*Mine Advance price rates*/
            $this->data['rate_title'] = array(
                'name' => 'rate_title',
                'id' => 'rate_title',
                'type' => 'text',

                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('rate_title'),
            );
            $this->data['start_date'] = array(
                'name' => 'start_date',
                'id' => 'start_date',
                'type' => 'text',
                'onkeypress' => '',
                'value' => $this->form_validation->set_value('start_date'),
            );
            $this->data['end_date'] = array(
                'name' => 'end_date',
                'id' => 'end_date',
                'type' => 'text',
                'onkeypress' => '',
                'value' => $this->form_validation->set_value('end_date'),
            );
            $this->data['min_los'] = array(
                'name' => 'min_los',
                'id' => 'min_los',
                'type' => 'text',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('min_los'),
            );
            $this->data['nightly'] = array(
                'name' => 'nightly',
                'id' => 'nightly',
                'type' => 'text',
                'value' => $this->form_validation->set_value('nightly'),
            );
            $this->data['weekly'] = array(
                'name' => 'weekly',
                'id' => 'weekly',
                'type' => 'text',
                'value' => $this->form_validation->set_value('weekly'),
            );
            /*End Mine Advance price rates*/
            $this->data['country'] = countryList();
            $this->data['province'] = array();
            $this->data['city'] = array(
                'name' => 'city',
                'id' => 'city',
                'type' => 'text',
                'value' => $this->form_validation->set_value('city'),
            );
            for ($i = 0; $i <= $this->data['checkboxCount']; $i++) {
                $this->data['checkbox'][$i] = array(
                    'name' => 'checkbox[]',
                    'id' => 'checkbox' . $i,
                    'type' => 'checkbox'
                );
            }
            $this->data['category'] = $this->admin_estate_model->category();
            $this->data['estatetype'] = $this->admin_estate_model->estatetype();
            $this->data['telephone'] = array(
                'name' => 'telephone',
                'id' => 'telephone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('telephone'),
            );
            $this->data['gsm'] = array(
                'name' => 'gsm',
                'id' => 'gsm',
                'required'=>'required',
                'type' => 'text',
                'value' => $this->form_validation->set_value('gsm'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'email',

              //
                'required'=>'required',
                //'onkeypress' => 'return isNumber(event)',


                'value' => $this->form_validation->set_value('email'),
            );
           /* $this->data['guest_count'] = array(
                'name' => 'guest_count',
                'id' => 'guest_count',
                'type' => 'text',
                'value' => $this->form_validation->set_value('guest_count'),
            );*/

            $this->data['publish'] = array('name' => 'publish');
            $this->data['showcase'] = array('name' => 'showcase');
            $this->session->set_userdata('page', 1);
            //$this->data['room'] = $this->admin_estate_model->selectboxType('room');
            $this->data['bathroom'] = array(
                'name' => 'bathroom',
                'id' => 'bathroom',
                'type' => 'text',
                'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('bathroom'),
            );
            $this->data['room'] = array(
                'name' => 'room',
                'id' => 'room',
                'type' => 'text',
                'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('room'),
            );

            $this->data['sleep'] = array(
                'name' => 'sleep',
                'id' => 'sleep',
                'type' => 'text',
                'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('sleep'),
            );
            $this->data['squarefoot'] = array(
                'name' => 'squarefoot',
                'id' => 'sleep',
                'type' => 'text',
                //'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('squarefoot'),
            );
            $this->data['squaremeter'] = array(
                'name' => 'squaremeter',
                'id' => 'squaremeter',
                'type' => 'text',
               // 'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('squaremeter'),
            );
            $this->data['floor'] = array(
                'name' => 'floor',
                'id' => 'floor',
                'type' => 'text',
                //'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $this->form_validation->set_value('floor'),
            );
           // $this->data['bathroom'] = $this->admin_estate_model->selectboxType('bathroom');
            $this->data['heating'] = $this->admin_estate_model->selectboxType('heating');
           // $this->data['squaremeter'] = $this->admin_estate_model->selectboxType('squaremeter');
          //  $this->data['squarefoot'] = $this->admin_estate_model->selectboxType('squarefoot');
            $this->data['buildstatus'] = $this->admin_estate_model->selectboxType('buildstatus');
          //  $this->data['floor'] = $this->admin_estate_model->selectboxType('floor');

            $this->tmadmin->tmView('estate_panel/estate_add2', $this->data);
        }
    }

    public function estateEdit($id)
    {
        $this->is_login(3);
        $this->data['ptitle'] = lang('estate_page_edit_title');

        $this->load->helper('country');

        $reData = $this->admin_estate_model->estateEdit($id);
        $this->data['id_estate'] = $id;
        $this->data['coordinates'] = $reData[0]->gps;
        $this->data['country'] = countryList();
        $this->data['countryAC'] = $reData[0]->country;
        $this->data['province'] = json_decode(stateList(NULL, $reData[0]->country));
        $this->data['provinceAC'] = $reData[0]->province;
        $this->session->set_userdata('page', 1);
        if ($reData[0]->gps != '')
            list($lat, $lon) = explode(",", $reData[0]->gps);
        else
            $lat = $lon = '';


        $this->data['city'] = array(
            'name' => 'city',
            'id' => 'city',
            'required' => 'required',
            'type' => 'text',
            'value' => $reData[0]->city,
        );

        $this->data['address'] = array(
            'name' => 'address',
            'id' => 'address',
            'type' => 'text',
            'class' => 'span',
            'value' => $reData[0]->address,
        );

        $this->data['postal_code'] = array(
            'name' => 'postal_code',
            'id' => 'postal_code',
            'type' => 'text',
            'class' => 'span',
            'value' => $reData[0]->postal_code,
        );

        $this->data['lat'] = array(
            'name' => 'lat',
            'id' => 'lat',
            'required' => 'required',
            'type' => 'text',
            'class' => 'span',
            'value' => trim($lat),
        );

        $this->data['lon'] = array(
            'name' => 'lon',
            'id' => 'lon',
            'required' => 'required',
            'type' => 'text',
            'class' => 'span',
            'value' => trim($lon),
        );

        $this->data['telephone'] = array(
            'name' => 'telephone',
            'required'=>'required',
            'id' => 'telephone',
            'type' => 'text',
            'value' => $reData[0]->telephone,
        );
        $this->data['gsm'] = array(
            'name' => 'gsm',
            'id' => 'gsm',
            'type' => 'text',
            'value' => $reData[0]->gsm,
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $reData[0]->email,
        );

        $this->data['publish'] = array('name' => 'publish');
        $this->data['publishAC'] = ($reData[0]->publish) ? TRUE : FALSE;
        $this->data['publishAC2'] = (!$reData[0]->publish) ? TRUE : FALSE;
        $this->data['showcase'] = array('name' => 'showcase');
        $this->data['showcaseAC'] = ($reData[0]->showcase) ? TRUE : FALSE;

        $this->tmadmin->tmView('estate_panel/estate_edit', $this->data);

    }

    public function estateEdit2($id)
    {
        $this->is_login(3);
        $this->data['ptitle'] = lang('estate_page_edit_title');

        if ($this->input->get('country'))
            $this->data['get_country'] = $this->input->get('country');
        if ($this->input->get('province'))
            $this->data['get_province'] = $this->input->get('province');
        if ($this->input->get('city'))
            $this->data['get_city'] = $this->input->get('city');
        if ($this->input->get('address'))
            $this->data['get_address'] = $this->input->get('address');
        if ($this->input->get('postal_code'))
            $this->data['get_postal_code'] = $this->input->get('postal_code');
        //if($this->input->get('gps'))
        //$this->data['get_gps'] = $this->input->get('gps');
        if ($this->input->get('lat') && $this->input->get('lon'))
            $this->data['get_gps'] = $this->input->get('lat') . "," . $this->input->get('lon');
        if ($this->input->get('telephone'))
            $this->data['get_telephone'] = $this->input->get('telephone');
        if ($this->input->get('gsm'))
            $this->data['get_gsm'] = $this->input->get('gsm');
        if ($this->input->get('email'))
            $this->data['get_email'] = $this->input->get('email');
        if ($this->input->get('publish'))
            $this->data['get_publish'] = $this->input->get('publish');
        if ($this->input->get('draft'))
            $this->data['get_draft'] = $this->input->get('draft');

        $this->data['id_estate'] = $id;

        //validate form input
        $this->form_validation->set_rules('title', $this->lang->line('estate_title'), 'xss_clean');
        $this->form_validation->set_rules('price', $this->lang->line('estate_price'), 'alpha_numeric|integer');
        $this->form_validation->set_rules('category', $this->lang->line('estate_category'), 'xss_clean');
        $optional_rate=array();
        if(isset($_REQUEST['start_date'][0],$_REQUEST['end_date'][0]) && $_REQUEST['start_date'][0]!='' && $_REQUEST['end_date'][0]!=''){

            foreach($_REQUEST['start_date'] as $k=>$val){
                if(!empty($_REQUEST['start_date'][$k]) && !empty($_REQUEST['end_date'][$k]) ){
                    $single_rate['start_date']=$_REQUEST['start_date'][$k];
                    $single_rate['end_date']=$_REQUEST['end_date'][$k];
                    $single_rate['rate_title']=$_REQUEST['rate_title'][$k];
                    $single_rate['min_los']=$_REQUEST['min_los'][$k];
                    $single_rate['nightly']=$_REQUEST['nightly'][$k];
                    $single_rate['weekly']=$_REQUEST['weekly'][$k];
                    $optional_rate[]=$single_rate;
                }

            }
        }
        if ($this->form_validation->run() == true) {


            $data = array(
                'address' => $this->input->post('address'),
                'gps' => $this->input->post('gps'),
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'price' => $this->input->post('price'),
                'country' => $this->input->post('country'),
                'province' => $this->input->post('province'),
                'city' => $this->input->post('city'),
                'postal_code' => $this->input->post('postal_code'),
                'category' => $this->input->post('category'),
                'estatetype' => $this->input->post('estatetype'),
                'user_id' => $this->session->userdata('user_id'),
                'photo' => $this->input->post('images'),
                'checkbox' => $this->input->post('checkbox'),
                'telephone' => $this->input->post('telephone'),
                // the gsm field will be used as property owner details page url
                'gsm' => $this->input->post('gsm'),
                'email' => $this->input->post('email'),
                'room' => $this->input->post('room'),
                'sleep' => $this->input->post('sleep'),
                'bathroom' => $this->input->post('bathroom'),
                'heating' => $this->input->post('heating'),
                'squaremeter' => $this->input->post('squaremeter'),
                'squarefoot' => $this->input->post('squarefoot'),
                'buildstatus' => $this->input->post('buildstatus'),
                'floor' => $this->input->post('floor'),
                'publish' => $this->input->post('publish'),
                'showcase' => $this->input->post('showcase'),
                'fk' => @$this->input->post('fk'),
                'vrbo' => @$this->input->post('vrbo'),
                'hw' => @$this->input->post('hw'),
                'bk' => @$this->input->post('bk'),
                'ht' => @$this->input->post('ht'),
                'rm' => @$this->input->post('rm'),
                'vast' => @$this->input->post('vast'),
                'airbnb' => @$this->input->post('airbnb'),
                'otalo' => @$this->input->post('otalo'),
                'default_min_los' => $this->input->post('default_min_los'),
                'default_nightly' => $this->input->post('default_nightly'),
                'default_weekly' => $this->input->post('default_weekly'),
                'optional_rates' => @$optional_rate,
                'not_available_date_list' => $this->input->post('not_available_date_list'),


            );


            if ($data['title'] == "")
                $data['title'] = "Vacation Rental";
            if ($data['price'] == "")
                $data['price'] = 0;
            if ($data['content'] == "")
                $data['content'] = '';
            if ($data['telephone'] == "")
                $data['telephone'] = '';
            if ($data['gsm'] == "")
                $data['gsm'] = '';
            if ($data['email'] == "")
                $data['email'] = '';
            if ($data['room'] == "")
                $data['room'] = '';
            if ($data['bathroom'] == "")
                $data['bathroom'] = '';
            if ($data['heating'] == "")
                $data['heating'] = '';
            if ($data['squaremeter'] == "")
                $data['squaremeter'] = '';
            if ($data['squarefoot'] == "")
                $data['squarefoot'] = '';
            if ($data['buildstatus'] == "")
                $data['buildstatus'] = '';
            if ($data['floor'] == "")
                $data['floor'] = '';
            if ($data['photo'] == false)
                $data['photo'] = array(NULL);
        }

        if ($this->form_validation->run() == true && $this->admin_estate_model->updateEstate($data, $id)) {
            //\application\helpers\Generic::_setTrace($data);
            $this->session->set_flashdata('message', $this->admin_estate_model->errors());
            $this->session->set_flashdata('success', $this->admin_estate_model->messages());
            redirect('admin/estateAll');
        } else {

            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');
            $this->data['checkboxCount'] = $this->admin_estate_model->propertyField()->num_rows();
            $this->data['checkboxName'] = $this->admin_estate_model->propertyALL($id)->result();

            $this->data['attrib'] = $this->admin_estate_model->estateAttribArray();

            $this->data['type'] = array();

            foreach ($this->data['attrib'] as $arrFields) {
                $this->data['attrib'][$arrFields->eaid] = $arrFields->attribName;
            }

            $this->load->helper('country');

            $reData = $this->admin_estate_model->estateEdit($id);
            $optional_rates = $this->admin_estate_model->priceRatesEdit($id);
            $not_available_dates = $this->admin_estate_model->unavailability($id);
            //\application\helpers\Generic::_setTrace($reData);
            $this->data['fk'] = $reData[0]->fk;
            $this->data['vrbo'] = $reData[0]->vrbo;
            $this->data['hw'] = $reData[0]->hw;
            $this->data['ht'] = $reData[0]->ht;
            $this->data['bk'] = $reData[0]->bk;
            $this->data['rm'] = $reData[0]->rm;
            $this->data['otalo'] = $reData[0]->otalo;
            $this->data['airbnb'] = $reData[0]->airbnb;
            $this->data['vast'] = $reData[0]->vast;

            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'required'=>'required',
                'value' => $reData[0]->title
            );

            $this->data['content'] = array(
                'name' => 'content',
                'id' => 'content',
                'type' => 'text',
                'value' => $reData[0]->content,
            );
            $this->data['price'] = array(
                'name' => 'price',
                'id' => 'price',
                'type' => 'text',
                'onkeypress' => 'return isNumber(event)',
                'value' => $reData[0]->price,
            );
            $this->data['default_min_los'] = array(
                'name' => 'default_min_los',
                'id' => 'default_min_los',
                'type' => 'text',
                'value' => $reData[0]->default_min_los,
            );
            $this->data['default_nightly'] = array(
                'name' => 'default_nightly',
                'id' => 'default_nightly',
                'type' => 'text',
                'value' => $reData[0]->default_nightly,
            );
            $this->data['default_weekly'] = array(
                'name' => 'default_weekly',
                'id' => 'default_weekly',
                'type' => 'text',
                'value' => $reData[0]->default_weekly,
            );
            $this->data['country'] = countryList();
            $this->data['countryAC'] = $reData[0]->country;
            $this->data['province'] = json_decode(stateList(NULL, $reData[0]->country));
            $this->data['provinceAC'] = $reData[0]->province;
            $this->data['city'] = array(
                'name' => 'city',
                'id' => 'city',
                'type' => 'text',
                'value' => $reData[0]->city,
            );
            for ($i = 0; $i <= $this->data['checkboxCount']; $i++) {
                $this->data['checkbox'][$i] = array(
                    'name' => 'checkbox[]',
                    'id' => 'checkbox' . $i,
                    'type' => 'checkbox'
                );
            }
            $this->data['photos'] = json_decode($reData[0]->photoGallery);
            $this->data['category'] = $this->admin_estate_model->category();
            $this->data['categoryAC'] = $reData[0]->catID;
            $this->data['estatetype'] = $this->admin_estate_model->estatetype();
            $this->data['estatetypeAC'] = $reData[0]->estateTypeID;
            /*Start optional rates*/
            $this->data['optional_rates'] =$optional_rates;
            $this->data['not_available_dates'] =$not_available_dates;
            /*End Rates rates*/
            $this->data['telephone'] = array(
                'name' => 'telephone',
                'id' => 'telephone',
                'required'=>'required',
                'type' => 'text',
                'value' => $reData[0]->telephone,
            );
            $this->data['sleep'] = array(
                'name' => 'sleep',
                'id' => 'sleep',
                'type' => 'text',
                'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $reData[0]->sleep
            );
            $this->data['gsm'] = array(
                'name' => 'gsm',
                'id' => 'gsm',
                'type' => 'text',
                'value' => $reData[0]->gsm,
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'email',

                //
                'required'=>'required',
                //'onkeypress' => 'return isNumber(event)',
                'value' => $reData[0]->email
            );


            $this->data['publish'] = array('name' => 'publish');
            $this->data['publishAC'] = ($reData[0]->publish) ? TRUE : FALSE;
            $this->data['publishAC2'] = (!$reData[0]->publish) ? TRUE : FALSE;
            $this->data['showcase'] = array('name' => 'showcase');
            $this->data['showcaseAC'] = ($reData[0]->showcase) ? TRUE : FALSE;



            $this->data['room'] = array(
                'name' => 'room',
                'id' => 'room',
                'type' => 'text',
                'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $reData[0]->room
            );


            $this->data['bathroom'] = array(
                'name' => 'bathroom',
                'id' => 'bathroom',
                'type' => 'text',
                'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $reData[0]->bathroom
            );


            $this->data['squarefoot'] = array(
                'name' => 'squarefoot',
                'id' => 'sleep',
                'type' => 'text',
                //'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' => $reData[0]->squarefoot
            );
            $this->data['squaremeter'] = array(
                'name' => 'squaremeter',
                'id' => 'squaremeter',
                'type' => 'text',
                // 'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' =>  $reData[0]->squaremeter
            );
            $this->data['floor'] = array(
                'name' => 'floor',
                'id' => 'floor',
                'type' => 'text',
                //'required'=>'required',
                'onkeypress' => 'return isNumber(event)',
                'value' =>  $reData[0]->floor
            );

            $this->data['heating'] = $this->admin_estate_model->selectboxType('heating');
            $this->data['heatingAC'] = $reData[0]->heating;
            $this->data['buildstatus'] = $this->admin_estate_model->selectboxType('buildstatus');
            $this->data['buildstatusAC'] = $reData[0]->buildstatus;
                      #  \application\helpers\Generic::_setTrace($this->data);
            $this->tmadmin->tmView('estate_panel/estate_edit2', $this->data);
        }
    }

    public function estateCat()
    {
        $this->is_login(3);

        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateCat/', $this->admin_estate_model->num_row_category(), 20);
        $this->session->set_userdata('page', 1);
        $this->data['estatecat'] = $this->admin_estate_model->categoryArray();
        $this->data['ptitle'] = lang('estate_page_cat_title');
        $this->tmadmin->tmView('estate_panel/category', $this->data);
    }

    public function estateAttributes()
    {
        $this->is_login(5);

        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateAttributes/', $this->admin_estate_model->num_row_type(), 20);

        $this->data['estateattrib'] = $this->admin_estate_model->estateAttribArray();
        $this->data['ptitle'] = 'Property Attributes';
        $this->tmadmin->tmView('estate_panel/attrib', $this->data);
    }

    public function estateType()
    {
        $this->is_login(3);

        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateType/', $this->admin_estate_model->num_row_type(), 20);
        $this->session->set_userdata('page', 1);
        $this->data['estatetype'] = $this->admin_estate_model->estateTypeArray();
        $this->data['ptitle'] = lang('estate_page_type_title');
        $this->tmadmin->tmView('estate_panel/type', $this->data);
    }

    public function estateConfirm()
    {
        $this->is_login(3);
        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateConfirm/', $this->admin_estate_model->num_row_user(array('publish' => 0)), 10);

        $whr = array(
            'publish !=' => '1'
        );
        $this->data['estates'] = $this->admin_estate_model->estates(NULL, $whr);
        $this->data['ptitle'] = lang('estate_page_confirm_title');
        $this->session->set_userdata('page', 1);
        $this->tmadmin->tmView('estate_panel/estate_confirm', $this->data);
    }

    public function estateSelectbox()
    {
        $this->is_login(3);

        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateSelectbox/', $this->admin_estate_model->num_row_selectbox(), 20);
        $this->session->set_userdata('page', 1);
        $this->data['selectbox'] = $this->admin_estate_model->selectboxGet();
        $this->data['ptitle'] = lang('estate_page_select_title');
        $this->tmadmin->tmView('estate_panel/selectbox', $this->data);
    }

    public function estateProvince($id)
    {
        $this->load->helper('country');
        echo stateList(NULL, urldecode($id));
    }

    public function estateSelectboxAdd($id = NULL)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 1);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('estate_page_selectupdate_title');
        } else {
            $this->data['ptitle'] = lang('estate_page_selectadd_title');
        }

        //validate form input
        $this->form_validation->set_rules('type', $this->lang->line('estate_add_selectbox_validation'), 'required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('estate_add_selectbox_name'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            if ($id != NULL) {
                $query = $this->admin_estate_model->selectUpdate($this->input->post('type'), $this->input->post('name'), $id);
            } else {
                $query = $this->admin_estate_model->selectRegister($this->input->post('type'), $this->input->post('name'));
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->selectboxGet($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['value'] : $this->form_validation->set_value('name'),
            );
            $this->data['type'] = array(
                'room' => 'Number of Rooms',
                'bathroom' => 'Number of Bathrooms',
                'heating' => 'Heating System',
                'squaremeter' => 'Square Meter',
                'buildstatus' => 'Building Condition',
                'floor' => 'Number of Floors'
            );
            $this->data['typeVal'] = ($id != NULL) ? $name[0]['type'] : $this->form_validation->set_value('type');

            $this->tmadmin->tmView('estate_panel/selectbox_add', $this->data);
        }
    }

    public function estateCatAdd($id = NULL)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 1);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('estate_page_catupdate_title');
        } else {
            $this->data['ptitle'] = lang('estate_page_catadd_title');
        }

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line('estate_category_add_name'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            if ($id != NULL) {
                $query = $this->admin_estate_model->categoryUpdate($this->input->post('name'), $id);
            } else {
                $query = $this->admin_estate_model->categoryRegister($this->input->post('name'));
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->categoryArray($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['catName'] : $this->form_validation->set_value('name'),
            );
            $this->tmadmin->tmView('estate_panel/category_add', $this->data);
        }
    }

    public function estateAttribAdd($id = NULL)
    {
        $this->is_login(5);

        if ($id != NULL) {
            $this->data['ptitle'] = 'Edit Property Attribute';
        } else {
            $this->data['ptitle'] = 'Add Property Attribute';
        }

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line('estate_type_name'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            if ($id != NULL) {
                $query = $this->admin_estate_model->estateAttribUpdate($this->input->post('name'), $id);
            } else {
                $query = $this->admin_estate_model->estateAttribRegister($this->input->post('name'));
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->estateAttribArray($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['attribName'] : $this->form_validation->set_value('name'),
            );
            $this->tmadmin->tmView('estate_panel/attrib_add', $this->data);
        }
    }

    public function estateTypeAdd($id = NULL)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 1);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('estate_page_typeupdate_title');
        } else {
            $this->data['ptitle'] = lang('estate_page_typeadd_title');
        }

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line('estate_type_name'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            if ($id != NULL) {
                $query = $this->admin_estate_model->estateTypeUpdate($this->input->post('name'), $id);
            } else {
                $query = $this->admin_estate_model->estateTypeRegister($this->input->post('name'));
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->estateTypeArray($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['estateName'] : $this->form_validation->set_value('name'),
            );
            $this->tmadmin->tmView('estate_panel/type_add', $this->data);
        }
    }

    public function estateProperty()
    {
        $this->is_login(3);

        $this->data['attrib'] = $this->admin_estate_model->estateAttribArray();

        $this->data['type'] = array();

        foreach ($this->data['attrib'] as $arrFields) {
            $this->data['attrib'][$arrFields->eaid] = $arrFields->attribName;
        }

        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateProperty/', $this->admin_estate_model->num_row_getName('propertyfield'), 20);
        $this->session->set_userdata('page', 1);
        $this->data['property'] = $this->admin_estate_model->propertyGet();
        $this->data['ptitle'] = lang('estate_page_prop_title');
        $this->tmadmin->tmView('estate_panel/property', $this->data);
    }

    public function estatePropertyAdd($id = NULL)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 1);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('estate_page_propeupdate_title');
        } else {
            $this->data['ptitle'] = lang('estate_page_propeadd_title');
        }

        //validate form input
        $this->form_validation->set_rules('type', $this->lang->line('estate_add_selectbox_validation'), 'required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('estate_add_selectbox_name'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            if ($id != NULL) {
                $query = $this->admin_estate_model->propertyUpdate($this->input->post('type'), $this->input->post('name'), $id);
            } else {
                $query = $this->admin_estate_model->propertyRegister($this->input->post('type'), $this->input->post('name'));
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->propertyGet($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['name'] : $this->form_validation->set_value('name'),
            );
            /*
            $this->data['type'] = array(
                '1' => 'Internal Hardware',
                '2' => 'External Hardware',
                '3' => 'Environmental Properties',
            );*/

            $this->data['attrib'] = $this->admin_estate_model->estateAttribArray();

            $this->data['type'] = array();

            foreach ($this->data['attrib'] as $arrFields) {
                $this->data['type'][$arrFields->eaid] = $arrFields->attribName;
            }

            $this->data['typeVal'] = ($id != NULL) ? $name[0]['typeID'] : $this->form_validation->set_value('type');

            $this->tmadmin->tmView('estate_panel/property_add', $this->data);
        }
    }

    public function estateActivate($id, $activate)
    {
        // do we have the right userlevel?
        $this->is_login(3);
        if ($activate) {
            $this->admin_estate_model->deactivate($id);
            $this->session->set_flashdata('message', lang('estate_message_deactive'));
        } else {
            $this->admin_estate_model->activate($id);
            $this->session->set_flashdata('message', lang('estate_message_active'));
        }

        redirect_back();
    }

    public function estateDeactivate($id, $activate)
    {
        // do we have the right userlevel?
        $this->is_login(3);
        if ($activate) {
            $this->admin_estate_model->deactivate($id);
            $this->session->set_flashdata('message', lang('estate_message_deactive'));
        } else {
            $this->admin_estate_model->activate($id);
            $this->session->set_flashdata('message', lang('estate_message_active'));
        }
        redirect_back();
    }

    public function estateShowcaseOn($id, $activate)
    {
        // do we have the right userlevel?
        $this->is_login(3);
        if ($activate) {
            $this->admin_estate_model->showcaseOff($id);
            $this->session->set_flashdata('message', lang('estate_message_showcase_deactive'));
        } else {
            $this->admin_estate_model->showcaseOn($id);
            $this->session->set_flashdata('message', lang('estate_message_showcase_activate'));
        }

        redirect_back();
    }

    public function estateShowcaseOff($id, $activate)
    {
        // do we have the right userlevel?
        $this->is_login(3);
        if ($activate) {
            $this->admin_estate_model->showcaseOff($id);
            $this->session->set_flashdata('message', lang('estate_message_showcase_deactive'));
        } else {
            $this->admin_estate_model->showcaseOn($id);
            $this->session->set_flashdata('message', lang('estate_message_showcase_activate'));
        }
        redirect_back();
    }

    public function estateDelete($id)
    {
        $this->is_login(3);
        $this->admin_estate_model->delete($id);
        $this->session->set_flashdata('message', lang('estate_message_delete'));
        redirect_back();
    }

    public function estateDeleteSelectbox($id)
    {
        $this->is_login(3);
        $this->admin_estate_model->deleteSelect($id);
        $this->session->set_flashdata('message', lang('estate_message_select_delete'));
        redirect_back();
    }

    public function estateDeleteProperty($id)
    {
        $this->is_login(3);
        $this->admin_estate_model->deleteProperty($id);
        $this->session->set_flashdata('message', lang('estate_message_prop_delete'));
        redirect_back();
    }

    public function estateDeleteCat($id)
    {
        $this->is_login(3);
        $this->admin_estate_model->deleteCategory($id);
        $this->session->set_flashdata('message', lang('estate_message_cat_delete'));
        redirect_back();
    }

    public function estateDeleteType($id)
    {
        $this->is_login(3);
        $this->admin_estate_model->deleteType($id);
        $this->session->set_flashdata('message', lang('estate_message_type_delete'));
        redirect_back();
    }

    //Blog Panel - A new blog add, delete, and approve
    public function blogAll()
    {
        $this->is_login(2);
        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/blogAll/', $this->admin_estate_model->num_row_getName('blogpage', array('type' => "0")), 10);

        $this->data['blogs'] = $this->admin_estate_model->blogPage(NULL, 1);
        $this->data['ptitle'] = lang('blog_page_title_all');
        $this->session->set_userdata('page', 2);
        $this->tmadmin->tmView('blog_panel/blog_all', $this->data);
    }

    public function blogAdd()
    {
        $this->is_login(2);
        $this->data['ptitle'] = lang('blog_page_title_add');

        //validate form input
        $this->form_validation->set_rules('title', $this->lang->line('estate_title'), 'xss_clean');
        $this->form_validation->set_rules('photo', $this->lang->line('estate_photo'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'desc' => $this->input->post('desc'),
                'tags' => $this->input->post('tags'),
                'publish' => $this->input->post('publish'),
                'photo' => $this->input->post('photo'),
                'type' => 0,
            );
            if ($data['title'] == "")
                $data['title'] = "Blog";
            if ($data['content'] == "")
                $data['content'] = '';
        }

        if ($this->form_validation->run() == true && $this->admin_estate_model->blogRegister($data)) {
            $this->session->set_flashdata('message', $this->admin_estate_model->errors());
            $this->session->set_flashdata('success', $this->admin_estate_model->messages());
            redirect_back();
        } else {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('title'),
            );
            $this->data['content'] = array(
                'name' => 'content',
                'id' => 'content',
                'type' => 'text',
                'value' => $this->form_validation->set_value('content'),
            );
            $this->data['desc'] = array(
                'name' => 'desc',
                'id' => 'desc',
                'type' => 'text',
                'value' => $this->form_validation->set_value('desc'),
            );
            $this->data['tags'] = array(
                'name' => 'tags',
                'id' => 'tags',
                'type' => 'text',
                'value' => $this->form_validation->set_value('tags'),
            );
            $this->data['publish'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => '1',
                'checked' => TRUE,
            );
            $this->data['publish2'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => '0',
            );
            $this->session->set_userdata('page', 2);
            $this->tmadmin->tmView('blog_panel/blog_add', $this->data);
        }
    }

    public function blogEdit($id)
    {
        $this->is_login(2);
        $this->data['ptitle'] = lang('blog_page_title_edit');
        //validate form input
        $this->session->set_userdata('page', 2);
        $this->form_validation->set_rules('title', $this->lang->line('estate_title'), 'xss_clean');
        $this->form_validation->set_rules('photo', $this->lang->line('estate_photo'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'desc' => $this->input->post('desc'),
                'tags' => $this->input->post('tags'),
                'publish' => $this->input->post('publish'),
                'photo' => $this->input->post('photo'),
                'type' => 0,
            );
            if ($data['title'] == "")
                $data['title'] = "Blog";
            if ($data['content'] == "")
                $data['content'] = '';
        }

        if ($this->form_validation->run() == true && $this->admin_estate_model->blogUpdate($data, $id)) {
            $this->session->set_flashdata('message', $this->admin_estate_model->errors());
            $this->session->set_flashdata('success', $this->admin_estate_model->messages());
            redirect_back();
        } else {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $old_data = $this->admin_estate_model->blogPage($id, 10);

            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'value' => $old_data[0]->title,
            );
            $this->data['content'] = array(
                'name' => 'content',
                'id' => 'content',
                'type' => 'text',
                'value' => $old_data[0]->content,
            );
            $this->data['desc'] = array(
                'name' => 'desc',
                'id' => 'desc',
                'type' => 'text',
                'value' => $old_data[0]->desc,
            );
            $this->data['tags'] = array(
                'name' => 'tags',
                'id' => 'tags',
                'type' => 'text',
                'value' => $old_data[0]->tags,
            );
            $this->data['photo'] = $old_data[0]->photo;
            $this->data['publish'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => '1',
                'checked' => ($old_data[0]->publish) ? TRUE : FALSE,
            );
            $this->data['publish2'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => "0",
                'checked' => (!$old_data[0]->publish) ? TRUE : FALSE,
            );

            $this->tmadmin->tmView('blog_panel/blog_edit', $this->data);
        }
    }

    public function blogConfirm()
    {
        $this->is_login(2);
        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/blogConfirm/', $this->admin_estate_model->num_row_getName('blogpage', array('type' => '0', 'publish' => '0')), 10);

        $this->data['blogs'] = $this->admin_estate_model->blogPage(null, 0);
        $this->session->set_userdata('page', 2);
        $this->data['ptitle'] = lang('blog_page_title_confirm');
        $this->tmadmin->tmView('blog_panel/blog_confirm', $this->data);
    }

    public function blogActivate($id, $activate)
    {
        // do we have the right userlevel?
        $this->is_login(2);
        if ($activate) {
            $this->admin_estate_model->blogpageDAC($id);
            $this->session->set_flashdata('message', lang('blog_message_deactive'));
        } else {
            $this->admin_estate_model->blogpageAC($id);
            $this->session->set_flashdata('message', lang('blog_message_active'));
        }

        redirect_back();
    }

    public function blogDeactivate($id, $activate)
    {
        // do we have the right userlevel?
        $this->is_login(2);
        if ($activate) {
            $this->admin_estate_model->blogpageDAC($id);
            $this->session->set_flashdata('message', lang('blog_message_deactive'));
        } else {
            $this->admin_estate_model->blogpageAC($id);
            $this->session->set_flashdata('message', lang('blog_message_active'));
        }
        redirect_back();
    }

    public function blogDelete($id)
    {
        $this->is_login(2);
        $this->admin_estate_model->deleteBlogPage($id);
        $this->session->set_flashdata('message', lang('blog_message_delete'));
        redirect_back();
    }

    //Web site page addition and deletion
    public function pageAll()
    {
        $this->is_login(4);
        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/pageAll/', $this->admin_estate_model->num_row_getName('blogpage', array('type' => "1")), 10);

        $this->data['pages'] = $this->admin_estate_model->blogPage(null, 1, array('type' => '1'));
        $this->data['ptitle'] = lang('page_page_title_all');
        $this->session->set_userdata('page', 3);
        $this->tmadmin->tmView('blog_panel/page_all', $this->data);
    }

    public function pageAdd()
    {
        $this->is_login(4);
        $this->data['ptitle'] = lang('page_page_title_add');
        //validate form input
        $this->form_validation->set_rules('title', $this->lang->line('estate_title'), 'xss_clean');
        $this->form_validation->set_rules('photo', $this->lang->line('estate_photo'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'desc' => $this->input->post('desc'),
                'tags' => $this->input->post('tags'),
                'publish' => $this->input->post('publish'),
                'photo' => NULL,
                'type' => 1,
            );
            if ($data['title'] == "")
                $data['title'] = "Pages";
            if ($data['content'] == "")
                $data['content'] = '';
        }

        if ($this->form_validation->run() == true && $this->admin_estate_model->blogRegister($data)) {
            $this->session->set_flashdata('message', $this->admin_estate_model->errors());
            $this->session->set_flashdata('success', $this->admin_estate_model->messages());
            redirect_back();
        } else {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('title'),
            );
            $this->data['content'] = array(
                'name' => 'content',
                'id' => 'content',
                'type' => 'text',
                'value' => $this->form_validation->set_value('content'),
            );
            $this->data['desc'] = array(
                'name' => 'desc',
                'id' => 'desc',
                'type' => 'text',
                'value' => $this->form_validation->set_value('desc'),
            );
            $this->data['tags'] = array(
                'name' => 'tags',
                'id' => 'tags',
                'type' => 'text',
                'value' => $this->form_validation->set_value('tags'),
            );
            $this->data['publish'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => '1',
                'checked' => TRUE,
            );
            $this->data['publish2'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => '0',
            );
            $this->session->set_userdata('page', 3);
            $this->tmadmin->tmView('blog_panel/page_add', $this->data);
        }
    }

    public function pageEdit($id)
    {
        $this->is_login(4);
        $this->session->set_userdata('page', 3);
        $this->data['ptitle'] = lang('page_page_title_edit');
        //validate form input
        $this->form_validation->set_rules('title', $this->lang->line('estate_title'), 'xss_clean');
        $this->form_validation->set_rules('photo', $this->lang->line('estate_photo'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'title' => $this->input->post('title'),
                'content' => $this->input->post('content'),
                'desc' => $this->input->post('desc'),
                'tags' => $this->input->post('tags'),
                'publish' => $this->input->post('publish'),
                'photo' => NULL,
                'type' => 1,
            );
            if ($data['title'] == "")
                $data['title'] = "Blog";
            if ($data['content'] == "")
                $data['content'] = '';
        }

        if ($this->form_validation->run() == true && $this->admin_estate_model->blogUpdate($data, $id)) {
            $this->session->set_flashdata('message', $this->admin_estate_model->errors());
            $this->session->set_flashdata('success', $this->admin_estate_model->messages());
            redirect_back();
        } else {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $old_data = $this->admin_estate_model->blogPage($id, 10);

            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'value' => $old_data[0]->title,
            );
            $this->data['content'] = array(
                'name' => 'content',
                'id' => 'content',
                'type' => 'text',
                'value' => $old_data[0]->content,
            );
            $this->data['desc'] = array(
                'name' => 'desc',
                'id' => 'desc',
                'type' => 'text',
                'value' => $old_data[0]->desc,
            );
            $this->data['tags'] = array(
                'name' => 'tags',
                'id' => 'tags',
                'type' => 'text',
                'value' => $old_data[0]->tags,
            );
            $this->data['publish'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => '1',
                'checked' => ($old_data[0]->publish) ? TRUE : FALSE,
            );
            $this->data['publish2'] = array(
                'name' => 'publish',
                'id' => 'publish',
                'value' => "0",
                'checked' => (!$old_data[0]->publish) ? TRUE : FALSE,
            );

            $this->tmadmin->tmView('blog_panel/page_edit', $this->data);
        }
    }

    public function pageConfirm()
    {
        $this->is_login(4);
        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/pageConfirm/', $this->admin_estate_model->num_row_getName('blogpage', array('type' => '1', 'publish' => '0')), 10);

        $this->data['pages'] = $this->admin_estate_model->blogPage(null, 0, array('type' => '1'));
        $this->data['ptitle'] = lang('page_page_title_confirm');
        $this->session->set_userdata('page', 3);
        $this->tmadmin->tmView('blog_panel/page_confirm', $this->data);
    }

    //Media files, viewing, editing, and adding a new
    public function mediaAll()
    {
        $this->session->set_userdata('page', 0);
        $this->is_login(3);

        $fileList = glob('uploads/{*.jpg,*.gif,*.png}', GLOB_BRACE);
        $this->data['files'] = array_slice($fileList, $this->uri->segment(3), 20);
        $this->data['pagination'] = $this->paginationCreate('/admin/mediaAll/', count($fileList), 20);
        $this->data['ptitle'] = lang('media_page_title');
        $this->tmadmin->tmView('other/media', $this->data);
    }

    public function mediaCustom()
    {
        $this->session->set_userdata('page', 0);
        $this->is_login(3);

        $fileList = glob('uploads/{*.jpg,*.gif,*.png}', GLOB_BRACE);

        foreach ($fileList as $key => $item) {
            $diskimage[$key + 1] = basename($item);
        }
        $slider = json_decode($this->admin_estate_model->getSetings('slider')->value);
        if (!empty($slider)) {
            foreach ($slider as $key => $item) {
                $images[] = $key;
            }
        }
        $blogimage = $this->admin_estate_model->blogPage(NULL, 10, array('photo IS NOT NULL' => NULL), NULL, 'photo');
        if (!empty($blogimage)) {
            foreach ($blogimage as $item) {
                $images[] = $item->photo;
            }
        }
        $estateimage = $this->admin_estate_model->estates(NULL, array('photoGallery <>' => "[null]"), NULL, 'photoGallery');
        if (!empty($estateimage)) {
            foreach ($estateimage as $item) {
                foreach (json_decode($item->photoGallery) as $photo) {
                    $images[] = $photo;
                }
            }
        }
        if (isset($images)) {
            foreach ($images as $photo) {
                $loc = array_search($photo, $diskimage);
                if ($loc != NULL) {
                    unset($diskimage[$loc]);
                }
            }
        }


        $this->data['files'] = array_slice($diskimage, $this->uri->segment(3), 20);
        $this->data['pagination'] = $this->paginationCreate('/admin/mediaCustom/', count($diskimage), 20);
        $this->data['ptitle'] = lang('media_page_title');
        $this->tmadmin->tmView('other/media_custom', $this->data);
    }

    // User Panel Settings - Add user, group and view profile
    public function userAll()
    {
        $this->is_login(4);

        $this->data['pagination'] = $this->paginationCreate('/admin/userAll/', $this->ion_auth->num_row_user(), 10);
        $this->ion_auth->paginationLimit(10, $this->uri->segment(3));

        $this->data['users'] = $this->ion_auth->users()->result();
        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }
        $this->data['ptitle'] = lang('user_page_all_title');
        $this->session->set_userdata('page', 5);
        $this->tmadmin->tmView('user_panel/user_all', $this->data);
    }

    public function userAdd()
    {
        $this->is_login(4);
        $this->data['ptitle'] = lang('user_page_add_title');
        $this->session->set_userdata('page', 5);
        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
        //$this->form_validation->set_rules('username', $this->lang->line('edit_user_validation_uname_label'), 'trim|required|min_length[5]|max_length[20]|xss_clean|alpha_numeric|is_unique[users.email]');
        //$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->ion_auth_model->getSet('min_password_length')->value . ']|max_length[' . $this->ion_auth_model->getSet('max_password_length')->value . ']|matches[password_confirm]');
        //$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'xss_clean');
        $this->form_validation->set_rules('phone1', $this->lang->line('create_user_validation_phone1_label'), 'xss_clean|min_length[3]|max_length[3]');
        $this->form_validation->set_rules('phone2', $this->lang->line('create_user_validation_phone2_label'), 'xss_clean|min_length[7]|max_length[7]');

        if ($this->form_validation->run() == true) {
            //$username = strtolower($this->input->post('username'));
            $email = $this->input->post('email');
            $username = $this->input->post('email');
            $password = 'Ema1lm&$';
            //$password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2')
            );
        }
        // set group to 4 as  admin/owner
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data, array('3'))) {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            $this->session->set_flashdata('success', $this->ion_auth->messages());
            redirect_back();
        } else {
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            /*$this->data['username'] = array(
                'name' => 'username',
                'id' => 'username',
                'type' => 'text',
                'value' => $this->form_validation->set_value('username'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );*/
            $this->data['company'] = array(
                'name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone1'] = array(
                'name' => 'phone1',
                'id' => 'phone1',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone1'),
            );
            $this->data['phone2'] = array(
                'name' => 'phone2',
                'id' => 'phone2',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone2'),
            );

            $this->tmadmin->tmView('user_panel/user_add', $this->data);
        }
    }

    public function userAllGroup()
    {
        $this->is_login(4);

        $this->data['pagination'] = $this->paginationCreate('/admin/userAllGroup/', $this->ion_auth->num_row_groups(), 10);
        $this->ion_auth->paginationLimit(10, $this->uri->segment(3));
        $this->session->set_userdata('page', 5);
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $this->data['ptitle'] = lang('user_page_grup_title');
        $this->tmadmin->tmView('user_panel/user_groupall', $this->data);
    }

    public function userGroup()
    {
        $this->is_login(4);
        $this->session->set_userdata('page', 5);
        $this->data['ptitle'] = lang('user_page_grupadd_title');
        //validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean|alpha_numeric|callback_userGroupNameCheck');
        $this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'), $this->input->post('level'));
            if ($new_group_id) {
                // check to see if we are creating the group
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                $this->session->set_flashdata('success', $this->ion_auth->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['group_name'] = array(
                'name' => 'group_name',
                'id' => 'group_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('group_name'),
            );
            $this->data['description'] = array(
                'name' => 'description',
                'id' => 'description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('description'),
            );
            $this->data['level'] = array(
                '5' => 'Developer',
                '4' => 'Administrator',
                '3' => 'Editör',
                '2' => 'Blogger',
                '1' => 'Kullanıcı',
            );

            $this->tmadmin->tmView('user_panel/group_add', $this->data);
        }
    }

    public function userGroupNameCheck($str)
    {
        if (strtolower($str) == 'members') {
            $this->form_validation->set_message('userGroupNameCheck', lang('group_creat_name'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function userProfile()
    {
        $this->is_login(2);
        $id = $this->ion_auth->get_user_id();
        $this->data['ptitle'] = lang('user_page_profile_title');
        $this->session->set_userdata('page', 5);
        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //process the phone number
        if (isset($user->phone) && !empty($user->phone)) {
            $user->phone = explode('-', $user->phone);
        }

        if (!isset($user->phone[0]))
            $user->phone[0] = NULL;
        if (!isset($user->phone[1]))
            $user->phone[1] = NULL;

        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('phone1', $this->lang->line('edit_user_validation_phone1_label'), 'xss_clean|min_length[2]|max_length[4]');
        $this->form_validation->set_rules('phone2', $this->lang->line('edit_user_validation_phone2_label'), 'xss_clean|min_length[7]|max_length[17]');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        $this->form_validation->set_rules('username', $this->lang->line('edit_user_validation_uname_label'), 'trim|required|min_length[3]|max_length[20]|xss_clean');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            $data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2'),
            );

            //update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->ion_auth_model->getSet('min_password_length')->value . ']|max_length[' . $this->ion_auth_model->getSet('max_password_length')->value . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                $data['password'] = $this->input->post('password');
            }

            if ($this->form_validation->run() === TRUE) {
                $this->ion_auth->update($user->id, $data);

                //check to see if we are creating the user
                //redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                $this->session->set_flashdata('success', $this->ion_auth->messages());
                redirect_back();
            }
        }

        //display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['success'] = $this->session->flashdata('success');


        //pass the user to the view
        $this->data['user'] = $user;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $this->data['phone1'] = array(
            'name' => 'phone1',
            'id' => 'phone1',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone1', $user->phone[0]),
        );
        $this->data['phone2'] = array(
            'name' => 'phone2',
            'id' => 'phone2',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone2', $user->phone[1]),
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password'
        );
        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username', $user->username)
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $user->email)
        );

        $this->tmadmin->tmView('user_panel/edit_user_profile', $this->data);
    }

    public function userEdit($id)
    {
        $this->is_login(4);
        $this->data['ptitle'] = lang('user_page_useredit_title');
        $user = $this->ion_auth->user($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //process the phone number
        if (isset($user->phone) && !empty($user->phone)) {
            $user->phone = explode('-', $user->phone);
        }

        if (!isset($user->phone[0]))
            $user->phone[0] = NULL;
        if (!isset($user->phone[1]))
            $user->phone[1] = NULL;

        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');
        $this->form_validation->set_rules('phone1', $this->lang->line('edit_user_validation_phone1_label'), 'xss_clean|min_length[3]|max_length[3]');
        $this->form_validation->set_rules('phone2', $this->lang->line('edit_user_validation_phone2_label'), 'xss_clean|min_length[7]|max_length[7]');
        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'xss_clean');
        $this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        //$this->form_validation->set_rules('username', $this->lang->line('edit_user_validation_uname_label'), 'trim|required|min_length[5]|max_length[20]|xss_clean');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            $data = array(
               // 'username' => $this->input->post('username'),
                'username' => $this->input->post('email'),
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2'),
            );

            //Update the groups user belongs to
            $groupData = $this->input->post('groups');

            if (isset($groupData) && !empty($groupData)) {

                $this->ion_auth->remove_from_group('', $id);

                foreach ($groupData as $grp) {
                    $this->ion_auth->add_to_group($grp, $id);
                }
            }

            //update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->ion_auth_model->getSet('min_password_length')->value . ']|max_length[' . $this->ion_auth_model->getSet('max_password_length')->value . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                $data['password'] = $this->input->post('password');
            }

            if ($this->form_validation->run() === TRUE) {
                $this->ion_auth->update($user->id, $data);

                //check to see if we are creating the user
                //redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                $this->session->set_flashdata('success', $this->ion_auth->messages());
                redirect_back();
            }
        }

        //display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['success'] = $this->session->flashdata('success');


        //pass the user to the view
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
        $this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $this->data['company'] = array(
            'name' => 'company',
            'id' => 'company',
            'type' => 'text',
            'value' => $this->form_validation->set_value('company', $user->company),
        );
        $this->data['phone1'] = array(
            'name' => 'phone1',
            'id' => 'phone1',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone1', $user->phone[0]),
        );
        $this->data['phone2'] = array(
            'name' => 'phone2',
            'id' => 'phone2',
            'type' => 'text',
            'value' => $this->form_validation->set_value('phone2', $user->phone[1]),
        );
       /* $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'type' => 'password'
        );
        $this->data['username'] = array(
            'name' => 'username',
            'id' => 'username',
            'type' => 'text',
            'value' => $this->form_validation->set_value('username', $user->username)
        );*/
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'value' => $this->form_validation->set_value('email', $user->email)
        );

        $this->tmadmin->tmView('user_panel/edit_user', $this->data);
    }

    public function userEditgroup($id)
    {
        $this->is_login(4);
        if (!$id || empty($id)) {
            redirect('admin', 'refresh');
        }
        $this->data['ptitle'] = lang('user_page_grupedit_title');
        $group = $this->ion_auth->group($id)->row();

        //validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean|alpha_numeric|callback_userGroupEditCheck');
        $this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === TRUE) {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['level'], $_POST['group_description']);

                if ($group_update) {
                    $this->session->set_flashdata('success', $this->lang->line('edit_group_saved'));
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect_back();
            }
        }

        //set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['success'] = $this->session->flashdata('success');

        //pass the user to the view
        $this->data['group'] = $group;

        $this->data['group_name'] = array(
            'name' => 'group_name',
            'id' => 'group_name',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_name', $group->name),
        );
        $this->data['group_description'] = array(
            'name' => 'group_description',
            'id' => 'group_description',
            'type' => 'text',
            'value' => $this->form_validation->set_value('group_description', $group->description),
        );
        $this->data['level'] = array(
            '5' => 'Developer',
            '4' => 'Administrator',
            '3' => 'Editör',
            '2' => 'Blogger',
            '1' => 'Kullanıcı',
        );

        $this->tmadmin->tmView('user_panel/edit_group', $this->data);
    }

    public function userGroupEditCheck($str)
    {
        if (strtolower($str) == 'members') {
            $this->form_validation->set_message('userGroupEditCheck', lang('edit_group_validation_members'));
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function userActivate($id, $activate)
    {
        // do we have the right userlevel?
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin(4)) {
            if ($activate) {
                $this->ion_auth->deactivate($id);
                $this->session->set_flashdata('message', lang('deactivate_message'));
            } else {
                $this->ion_auth->activate($id);
                $this->session->set_flashdata('message', lang('activate_message'));
            }
        }
        redirect_back();
    }

    public function userDeactivate($id, $activate)
    {
        // do we have the right userlevel?
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin(4)) {
            if ($activate) {
                $this->ion_auth->deactivate($id);
                $this->session->set_flashdata('message', lang('deactivate_message'));
            } else {
                $this->ion_auth->activate($id);
                $this->session->set_flashdata('message', lang('activate_message'));
            }
        }
        redirect_back();
    }

    public function userDelete($id)
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin(4)) {
            $this->session->set_flashdata('message', lang('edit_user_delete'));
            $this->ion_auth->delete_user($id);
        }
        redirect_back();
    }

    public function userGroupDelete($id)
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin(4)) {
            if ($id >= 1 and $id <= 5) {
                $this->session->set_flashdata('message', lang('all_group_notdelete'));
                redirect_back();
            }
            $this->session->set_flashdata('message', lang('all_group_delete'));
            $this->ion_auth->delete_group($id);
        }
        redirect_back();
    }

    // Web Site settings - contact information, e-mail settings, plug-ins and seo
    public function setGeneral()
    {
        $this->is_login(5);
        $this->data['ptitle'] = lang('set_genaral_title');

        //validate form input
        $this->form_validation->set_rules('site_title', $this->lang->line('set_genaral_site_title'), 'required|xss_clean');
        $this->form_validation->set_rules('slogan', $this->lang->line('set_genaral_slogan'), 'required|xss_clean');
        $this->form_validation->set_rules('estate_count', $this->lang->line('set_genaral_estate_count'), 'required|xss_clean|numeric');
        $this->form_validation->set_rules('blog_count', $this->lang->line('set_genaral_blog_count'), 'required|xss_clean|numeric');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'site_title' => $this->input->post('site_title'),
                'slogan' => $this->input->post('slogan'),
                'site_author' => $this->input->post('site_author'),
                'site_keyword' => $this->input->post('site_keyword'),
                'estate_count' => $this->input->post('estate_count'),
                'blog_count' => $this->input->post('blog_count'),
                'site_analytics' => $this->input->post('site_analytics')
            );
            $query = $this->admin_estate_model->updateGeneral($data);
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['site_title'] = array(
                'name' => 'site_title',
                'id' => 'site_title',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('site_title')->value,
            );
            $this->data['slogan'] = array(
                'name' => 'slogan',
                'id' => 'slogan',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('slogan')->value,
            );
            $this->data['site_keyword'] = array(
                'name' => 'site_keyword',
                'id' => 'site_keyword',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('site_keyword')->value,
            );
            $this->data['site_author'] = array(
                'name' => 'site_author',
                'id' => 'site_author',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('site_author')->value,
            );

            $this->data['estate_count'] = array(
                'name' => 'estate_count',
                'id' => 'estate_count',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('estate_count')->value,
            );
            $this->data['blog_count'] = array(
                'name' => 'blog_count',
                'id' => 'blog_count',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('blog_count')->value,
            );
            $this->data['site_analytics'] = array(
                'name' => 'site_analytics',
                'id' => 'site_analytics',
                'rows' => '5',
            );
            $this->data['site_analytics_value'] = $this->admin_estate_model->getSetings('site_analytics')->value;
            $this->session->set_userdata('page', 6);
            $this->tmadmin->tmView('settings/set_general', $this->data);
        }
    }

    public function setContact()
    {
        $this->is_login(5);
        $this->data['ptitle'] = lang('set_contact_title');
        $this->session->set_userdata('page', 6);
        //validate form input
        $this->form_validation->set_rules('site_eposta', $this->lang->line('set_contact_site_eposta'), 'valid_email|required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line('set_contact_phone'), 'xss_clean');
        $this->form_validation->set_rules('mobile_phone', $this->lang->line('set_contact_mobile_phone'), 'xss_clean');
        $this->form_validation->set_rules('adress', $this->lang->line('set_contact_adress'), 'xss_clean');
        $this->form_validation->set_rules('facebook', $this->lang->line('set_contact_facebook'), 'xss_clean');
        $this->form_validation->set_rules('twitter', $this->lang->line('set_contact_twitter'), 'xss_clean');
        $this->form_validation->set_rules('google', $this->lang->line('set_contact_google'), 'xss_clean');
        $this->form_validation->set_rules('linkedin', $this->lang->line('set_contact_linkedin'), 'xss_clean');
        $this->form_validation->set_rules('pinterest', $this->lang->line('set_contact_pinterest'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'site_eposta' => $this->input->post('site_eposta'),
                'phone' => $this->input->post('phone'),
                'mobile_phone' => $this->input->post('mobile_phone'),
                'adress' => $this->input->post('adress'),
                'facebook' => $this->input->post('facebook'),
                'twitter' => $this->input->post('twitter'),
                'google' => $this->input->post('google'),
                'linkedin' => $this->input->post('linkedin'),
                'pinterest' => $this->input->post('pinterest'),
            );
            $query = $this->admin_estate_model->updateGeneral($data);
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['site_eposta'] = array(
                'name' => 'site_eposta',
                'id' => 'site_eposta',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('site_eposta')->value,
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('phone')->value,
            );
            $this->data['mobile_phone'] = array(
                'name' => 'mobile_phone',
                'id' => 'mobile_phone',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('mobile_phone')->value,
            );
            $this->data['adress'] = array(
                'name' => 'adress',
                'id' => 'adress',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('adress')->value,
            );
            $this->data['facebook'] = array(
                'name' => 'facebook',
                'id' => 'facebook',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('facebook')->value,
            );
            $this->data['twitter'] = array(
                'name' => 'twitter',
                'id' => 'twitter',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('twitter')->value,
            );
            $this->data['google'] = array(
                'name' => 'google',
                'id' => 'google',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('google')->value,
            );
            $this->data['linkedin'] = array(
                'name' => 'linkedin',
                'id' => 'linkedin',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('linkedin')->value,
            );
            $this->data['pinterest'] = array(
                'name' => 'pinterest',
                'id' => 'pinterest',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('pinterest')->value,
            );

            $this->tmadmin->tmView('settings/set_contact', $this->data);
        }
    }

    public function setEmail()
    {
        $this->is_login(5);
        $this->data['ptitle'] = lang('set_email_title');
        $this->session->set_userdata('page', 6);
        //validate form input
        $this->form_validation->set_rules('from_email', $this->lang->line('set_email_from_email'), 'valid_email|required|xss_clean');
        $this->form_validation->set_rules('from_name', $this->lang->line('set_email_from_name'), 'xss_clean');
        $this->form_validation->set_rules('mail_type', $this->lang->line('set_email_mail_type'), 'xss_clean');
        $this->form_validation->set_rules('smtp_username', $this->lang->line('set_email_smtp_username'), 'xss_clean');
        $this->form_validation->set_rules('smtp_password', $this->lang->line('set_email_smtp_password'), 'xss_clean');
        $this->form_validation->set_rules('smtp_port', $this->lang->line('set_email_smtp_port'), 'xss_clean|numeric');
        $this->form_validation->set_rules('smtp_ssl', $this->lang->line('set_email_smtp_ssl'), 'xss_clean');
        $this->form_validation->set_rules('smtp_auth', $this->lang->line('set_email_smtp_auth'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'from_email' => $this->input->post('from_email'),
                'from_name' => $this->input->post('from_name'),
                'mail_type' => $this->input->post('mail_type'),
                'smtp_username' => $this->input->post('smtp_username'),
                'smtp_password' => $this->input->post('smtp_password'),
                'smtp_port' => $this->input->post('smtp_port'),
                'smtp_ssl' => $this->input->post('smtp_ssl'),
                'smtp_auth' => $this->input->post('smtp_auth'),
                'mail_charset' => $this->input->post('mail_charset'),
                'mail_encoding' => $this->input->post('mail_encoding'),
                'smtp_host' => $this->input->post('smtp_host'),
            );
            $query = $this->admin_estate_model->updateGeneral($data);
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['from_email'] = array(
                'name' => 'from_email',
                'id' => 'from_email',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('from_email')->value,
            );
            $this->data['from_name'] = array(
                'name' => 'from_name',
                'id' => 'from_name',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('from_name')->value,
            );
            $this->data['mail_charset'] = array(
                'name' => 'mail_charset',
                'id' => 'mail_charset',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('mail_charset')->value,
            );
            $this->data['mail_encoding'] = array(
                'name' => 'mail_encoding',
                'id' => 'mail_encoding',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('mail_encoding')->value,
            );
            $this->data['mail_type'] = array(
                'name' => 'mail_type',
                'id' => 'mail_type',
                'checked' => ($this->admin_estate_model->getSetings('mail_type')->value == 'smtp') ? TRUE : FALSE,
                'value' => 'smtp',
            );
            $this->data['mail_type2'] = array(
                'name' => 'mail_type',
                'id' => 'mail_type2',
                'checked' => ($this->admin_estate_model->getSetings('mail_type')->value == 'mail') ? TRUE : FALSE,
                'value' => 'mail',
            );
            $this->data['smtp_host'] = array(
                'name' => 'smtp_host',
                'id' => 'smtp_host',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('smtp_host')->value,
            );
            $this->data['smtp_username'] = array(
                'name' => 'smtp_username',
                'id' => 'smtp_username',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('smtp_username')->value,
            );
            $this->data['smtp_password'] = array(
                'name' => 'smtp_password',
                'id' => 'smtp_password',
                'type' => 'password',
                'value' => $this->admin_estate_model->getSetings('smtp_password')->value,
            );
            $this->data['smtp_port'] = array(
                'name' => 'smtp_port',
                'id' => 'smtp_port',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('smtp_port')->value,
            );
            $this->data['smtp_ssl'] = array(
                'name' => 'smtp_ssl',
                'id' => 'smtp_ssl',
                'checked' => ($this->admin_estate_model->getSetings('smtp_ssl')->value == 'ssl') ? TRUE : FALSE,
                'value' => 'ssl',
            );
            $this->data['smtp_ssl2'] = array(
                'name' => 'smtp_ssl',
                'id' => 'smtp_ssl2',
                'checked' => ($this->admin_estate_model->getSetings('smtp_ssl')->value == 'tls') ? TRUE : FALSE,
                'value' => 'tls',
            );
            $this->data['smtp_auth'] = array(
                'name' => 'smtp_auth',
                'id' => 'smtp_auth',
                'checked' => ($this->admin_estate_model->getSetings('smtp_auth')->value) ? TRUE : FALSE,
                'value' => 1,
            );

            $this->tmadmin->tmView('settings/set_email', $this->data);
        }
    }

    public function setImage()
    {
        $this->is_login(5);
        $this->data['ptitle'] = lang('set_image_title');
        $this->session->set_userdata('page', 6);
        //validate form input
        $this->form_validation->set_rules('image_height', $this->lang->line('set_image_height'), 'numeric|xss_clean');
        $this->form_validation->set_rules('image_width', $this->lang->line('set_image_width'), 'numeric|xss_clean');
        $this->form_validation->set_rules('image_quality', $this->lang->line('set_image_quality'), 'numeric|xss_clean');
        $this->form_validation->set_rules('image_maintain_ratio', $this->lang->line('set_image_maintain_ratio'), 'xss_clean');
        $this->form_validation->set_rules('image_wm_font_path', $this->lang->line('set_image_wm_font_path'), 'xss_clean');
        $this->form_validation->set_rules('image_wm_text', $this->lang->line('set_image_wm_text'), 'xss_clean');
        $this->form_validation->set_rules('image_wm_font_size', $this->lang->line('set_image_wm_font_size'), 'numeric|xss_clean');
        $this->form_validation->set_rules('image_wm_font_color', $this->lang->line('set_image_wm_font_color'), 'xss_clean');
        $this->form_validation->set_rules('image_wm_shadow_color', $this->lang->line('set_image_wm_shadow_color'), 'xss_clean');
        $this->form_validation->set_rules('image_wm_shadow_distance', $this->lang->line('set_image_wm_shadow_distance'), 'numeric|xss_clean');
        $this->form_validation->set_rules('image_wm_vrt_alignment', $this->lang->line('set_image_wm_vrt_alignment'), 'xss_clean');
        $this->form_validation->set_rules('image_wm_hor_alignment', $this->lang->line('set_image_wm_hor_alignment'), 'xss_clean');
        $this->form_validation->set_rules('image_wm_hor_offset', $this->lang->line('set_image_wm_hor_offset'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'image_height' => $this->input->post('image_height'),
                'image_width' => $this->input->post('image_width'),
                'image_quality' => $this->input->post('image_quality'),
                'image_maintain_ratio' => $this->input->post('image_maintain_ratio'),
                'image_wm_font_path' => $this->input->post('image_wm_font_path'),
                'image_wm_text' => $this->input->post('image_wm_text'),
                'image_wm_font_size' => $this->input->post('image_wm_font_size'),
                'image_wm_font_color' => $this->input->post('image_wm_font_color'),
                'image_wm_shadow_color' => $this->input->post('image_wm_shadow_color'),
                'image_wm_shadow_distance' => $this->input->post('image_wm_shadow_distance'),
                'image_wm_vrt_alignment' => $this->input->post('image_wm_vrt_alignment'),
                'image_wm_hor_alignment' => $this->input->post('image_wm_hor_alignment'),
                'image_watermarking' => $this->input->post('image_watermarking'),
                'image_wm_hor_offset' => $this->input->post('image_wm_hor_offset'),
            );
            $data = array('image_settings' => json_encode($data));
            $query = $this->admin_estate_model->updateGeneral($data);
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $dbData = json_decode($this->admin_estate_model->getSetings('image_settings')->value);
            foreach ($dbData as $key => $item) {
                $oldData[$key] = $item;
            }

            $this->data['image_height'] = array(
                'name' => 'image_height',
                'id' => 'image_height',
                'type' => 'text',
                'value' => $oldData['image_height'],
            );
            $this->data['image_width'] = array(
                'name' => 'image_width',
                'id' => 'image_width',
                'type' => 'text',
                'value' => $oldData['image_width'],
            );
            $this->data['image_quality'] = array(
                'name' => 'image_quality',
                'id' => 'image_quality',
                'type' => 'text',
                'value' => $oldData['image_quality'],
            );
            $this->data['image_maintain_ratio'] = array(
                'name' => 'image_maintain_ratio',
                'id' => 'image_maintain_ratio',
                'checked' => ($oldData['image_maintain_ratio']) ? TRUE : FALSE,
                'value' => 1,
            );

            $this->data['image_wm_font_path'] = array(
                'name' => 'image_wm_font_path',
                'id' => 'image_wm_font_path',
                'type' => 'text',
                'value' => $oldData['image_wm_font_path'],
            );
            $this->data['image_wm_text'] = array(
                'name' => 'image_wm_text',
                'id' => 'image_wm_text',
                'type' => 'text',
                'value' => $oldData['image_wm_text'],
            );
            $this->data['image_wm_font_size'] = array(
                'name' => 'image_wm_font_size',
                'id' => 'image_wm_font_size',
                'type' => 'text',
                'value' => $oldData['image_wm_font_size'],
            );
            $this->data['image_wm_font_color'] = array(
                'name' => 'image_wm_font_color',
                'id' => 'image_wm_font_color',
                'type' => 'text',
                'value' => $oldData['image_wm_font_color'],
            );
            $this->data['image_wm_shadow_color'] = array(
                'name' => 'image_wm_shadow_color',
                'id' => 'image_wm_shadow_color',
                'type' => 'text',
                'value' => $oldData['image_wm_shadow_color'],
            );
            $this->data['image_wm_shadow_distance'] = array(
                'name' => 'image_wm_shadow_distance',
                'id' => 'image_wm_shadow_distance',
                'type' => 'text',
                'value' => $oldData['image_wm_shadow_distance'],
            );
            $this->data['image_wm_hor_offset'] = array(
                'name' => 'image_wm_hor_offset',
                'id' => 'image_wm_hor_offset',
                'type' => 'text',
                'value' => $oldData['image_wm_hor_offset'],
            );
            $this->data['image_wm_vrt_alignment'] = array(
                'top' => 'Top',
                'middle' => 'Middle',
                'bottom' => 'Bottom',
            );
            $this->data['image_wm_vrt_alignment_value'] = $oldData['image_wm_vrt_alignment'];
            $this->data['image_wm_hor_alignment'] = array(
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            );
            $this->data['image_wm_hor_alignment_value'] = $oldData['image_wm_hor_alignment'];

            $this->data['image_watermarking'] = array(
                'name' => 'image_watermarking',
                'id' => 'image_watermarking',
                'checked' => ($oldData['image_watermarking']) ? TRUE : FALSE,
                'value' => 1,
            );

            $this->tmadmin->tmView('settings/set_image', $this->data);
        }
    }

    public function setView()
    {
        $this->is_login(5);
        $this->data['ptitle'] = lang('set_view_title');
        $this->session->set_userdata('page', 6);
        //validate form input
        $this->form_validation->set_rules('site_footer', $this->lang->line('set_view_footer'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'site_footer' => $this->input->post('site_footer'),
            );
            $query = $this->admin_estate_model->updateGeneral($data);
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['site_footer'] = array(
                'name' => 'site_footer',
                'id' => 'site_footer',
                'rows' => '5',
            );
            $this->data['site_footer_value'] = $this->admin_estate_model->getSetings('site_footer')->value;

            $this->tmadmin->tmView('settings/set_view', $this->data);
        }
    }

    public function setCore()
    {
        $this->is_login(5);
        $this->data['ptitle'] = lang('set_core_title');
        $this->session->set_userdata('page', 6);
        //validate form input
        $this->form_validation->set_rules('email_activation', $this->lang->line('set_core_email_activation'), 'xss_clean');
        $this->form_validation->set_rules('login_attempts', $this->lang->line('set_core_login_attempts'), 'xss_clean');
        $this->form_validation->set_rules('maximum_login_attempts', $this->lang->line('set_core_maximum_login_attempts'), 'numeric|xss_clean');
        $this->form_validation->set_rules('default_group', $this->lang->line('set_core_default_group'), 'xss_clean');
        $this->form_validation->set_rules('min_password_length', $this->lang->line('set_core_min_password_length'), 'xss_clean|numeric');
        $this->form_validation->set_rules('max_password_length', $this->lang->line('set_core_max_password_length'), 'numeric|xss_clean');
        $this->form_validation->set_rules('cache_timeout', $this->lang->line('set_core_cache_timeout'), 'numeric|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'email_activation' => $this->input->post('email_activation'),
                'track_login_attempts' => $this->input->post('login_attempts'),
                'maximum_login_attempts' => $this->input->post('maximum_login_attempts'),
                'default_group' => $this->input->post('default_group'),
                'min_password_length' => $this->input->post('min_password_length'),
                'max_password_length' => $this->input->post('max_password_length'),
                'cache_timeout' => $this->input->post('cache_timeout'),
            );
            $query = $this->admin_estate_model->updateGeneral($data);
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['email_activation'] = array(
                'name' => 'email_activation',
                'id' => 'email_activation',
                'value' => TRUE,
                'checked' => ($this->admin_estate_model->getSetings('email_activation')->value) ? TRUE : FALSE
            );
            $this->data['login_attempts'] = array(
                'name' => 'login_attempts',
                'id' => 'login_attempts',
                'value' => TRUE,
                'checked' => ($this->admin_estate_model->getSetings('track_login_attempts')->value) ? TRUE : FALSE
            );
            $this->data['maximum_login_attempts'] = array(
                'name' => 'maximum_login_attempts',
                'id' => 'maximum_login_attempts',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('maximum_login_attempts')->value,
            );
            $this->data['default_group'] = array(
                'members' => 'User',
                'blogger' => 'Blogger',
                'editor' => 'Editor',
                'administrator' => 'Administrator',
                'developer' => 'Developer',
            );
            $this->data['default_group_ac'] = $this->admin_estate_model->getSetings('default_group')->value;
            $this->data['min_password_length'] = array(
                'name' => 'min_password_length',
                'id' => 'min_password_length',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('min_password_length')->value,
            );
            $this->data['max_password_length'] = array(
                'name' => 'max_password_length',
                'id' => 'max_password_length',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('max_password_length')->value,
            );
            $this->data['cache_timeout'] = array(
                'name' => 'cache_timeout',
                'id' => 'cache_timeout',
                'type' => 'text',
                'value' => $this->admin_estate_model->getSetings('cache_timeout')->value,
            );


            $this->tmadmin->tmView('settings/set_core', $this->data);
        }
    }

    public function setExport()
    {
        $this->is_login(5);
        $this->data['ptitle'] = lang('set_export_title');
        $this->session->set_userdata('page', 6);
        //validate form input
        $this->form_validation->set_rules('set_export', $this->lang->line('export_driver_label'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'set_export' => $this->input->post('set_export'),
            );
            $query = $this->admin_estate_model->updateGeneral($data);
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $this->data['set_export'] = array(
                'name' => 'set_export',
                'id' => 'set_export',
            );
            $this->data['set_export_value'] = $this->admin_estate_model->getSetings('set_export')->value;

            $this->data['set_export_module'] = array(
                'name' => 'set_export_module',
                'id' => 'set_export_module',
            );
            $this->data['set_export_module_value'] = $this->admin_estate_model->getSetings('set_export_module')->value;
            $this->tmadmin->tmView('settings/set_export', $this->data);
        }
    }

    // ****** CUSTOMERS CONTROLLERS ******* //
    public function customerAll()
    {
        $this->is_login(4);

        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $this->data['success_message'] = $this->session->flashdata('success_message');
        $this->session->set_userdata('page', 9);
        $this->data['ptitle'] = lang('customer_page_all_title');
        $this->tmadmin->tmView('customer_panel/customer_all', $this->data);
    }

    public function customerAdd()
    {
        $this->is_login(4);

        $this->load->model('customers_model');

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'required|valid_email');
        $this->form_validation->set_rules('company', $this->lang->line("company"), 'required|xss_clean');
        $this->form_validation->set_rules('cf1', $this->lang->line("cf1"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("cf2"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("cf3"), 'xss_clean');
        $this->form_validation->set_rules('cf4', $this->lang->line("cf4"), 'xss_clean');
        $this->form_validation->set_rules('cf5', $this->lang->line("cf5"), 'xss_clean');
        $this->form_validation->set_rules('cf6', $this->lang->line("cf6"), 'xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'required|xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'required|xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'required|xss_clean');
        $this->form_validation->set_rules('country', $this->lang->line("country"), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required|xss_clean|min_length[9]|max_length[16]');

        if ($this->form_validation->run() == true) {
            $name = strtolower($this->input->post('name'));
            $email = $this->input->post('email');
            $company = $this->input->post('company');

            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone')
            );
        }

        if ($this->form_validation->run() == true && $this->customers_model->addCustomer($data)) { //check to see if we are creating the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("customer_added"));
            redirect("admin/customerAll", 'refresh');
        } else { //display the create customer form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array('name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['cui'] = array('name' => 'cui',
                'id' => 'cui',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cui', '-'),
            );
            $this->data['reg'] = array('name' => 'reg',
                'id' => 'reg',
                'type' => 'text',
                'value' => $this->form_validation->set_value('reg', '-'),
            );
            $this->data['cnp'] = array('name' => 'cnp',
                'id' => 'cnp',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cnp', '-'),
            );
            $this->data['serie'] = array('name' => 'serie',
                'id' => 'serie',
                'type' => 'text',
                'value' => $this->form_validation->set_value('serie', '-'),
            );
            $this->data['account_no'] = array('name' => 'account_no',
                'id' => 'account_no',
                'type' => 'text',
                'value' => $this->form_validation->set_value('account_no', '-'),
            );
            $this->data['bank'] = array('name' => 'bank',
                'id' => 'bank',
                'type' => 'text',
                'value' => $this->form_validation->set_value('bank', '-'),
            );
            $this->data['address'] = array('name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => $this->form_validation->set_value('address'),
            );
            $this->data['city'] = array('name' => 'city',
                'id' => 'city',
                'type' => 'text',
                'value' => $this->form_validation->set_value('city'),
            );
            $this->data['state'] = array('name' => 'state',
                'id' => 'state',
                'type' => 'text',
                'value' => $this->form_validation->set_value('state'),
            );
            $this->data['postal_code'] = array('name' => 'postal_code',
                'id' => 'postal_code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('postal_code'),
            );
            $this->data['country'] = array('name' => 'country',
                'id' => 'country',
                'type' => 'text',
                'value' => $this->form_validation->set_value('country'),
            );
            $this->data['phone'] = array('name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['cf1'] = array('name' => 'cf1',
                'id' => 'cf1',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cf1', '-'),
            );
            $this->data['cf2'] = array('name' => 'cf2',
                'id' => 'cf2',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cf2', '-'),
            );
            $this->data['cf3'] = array('name' => 'cf3',
                'id' => 'cf3',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cf3', '-'),
            );
            $this->data['cf4'] = array('name' => 'cf4',
                'id' => 'cf4',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cf4', '-'),
            );
            $this->data['cf5'] = array('name' => 'cf5',
                'id' => 'cf5',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cf5', '-'),
            );
            $this->data['cf6'] = array('name' => 'cf6',
                'id' => 'cf6',
                'type' => 'text',
                'value' => $this->form_validation->set_value('cf6', '-'),
            );
        }
        $this->session->set_userdata('page', 9);
        $this->data['ptitle'] = lang('customer_page_add_title');
        $this->tmadmin->tmView('customer_panel/customer_add', $this->data);
    }

    public function customerEdit($id = NULL)
    {

        $this->is_login(4);

        $id = (isset($_REQUEST['id']) && $id == NULL) ? $_REQUEST['id'] : $id;

        $this->load->model('customers_model');

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'required|valid_email');
        $this->form_validation->set_rules('company', $this->lang->line("company"), 'required|xss_clean');
        $this->form_validation->set_rules('cf1', $this->lang->line("cf1"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("cf2"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("cf3"), 'xss_clean');
        $this->form_validation->set_rules('cf4', $this->lang->line("cf4"), 'xss_clean');
        $this->form_validation->set_rules('cf5', $this->lang->line("cf5"), 'xss_clean');
        $this->form_validation->set_rules('cf6', $this->lang->line("cf6"), 'xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'required|xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'required|xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'required|xss_clean');
        $this->form_validation->set_rules('country', $this->lang->line("country"), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required|xss_clean|min_length[9]|max_length[16]');

        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone')
            );
        }

        if ($this->form_validation->run() == true && $this->customers_model->updateCustomer($id, $data)) {
            //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("customer_form_updated"));
            redirect("admin/customerAll", 'refresh');
        } else {
            //display the update form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['customer'] = $this->customers_model->getCustomerByID($id);

            $meta['page_title'] = $this->lang->line("customer_update");
            $this->data['id'] = $id;

            $this->data['ptitle'] = lang('customer_page_edit_title');
            $this->tmadmin->tmView('customer_panel/customer_edit', $this->data);
        }
    }

    public function customerDelete($id = NULL)
    {
        $this->is_login(4);

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->load->model('customers_model');

        if ($this->customers_model->deleteCustomer($id)) { //check to see if we are deleting the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("customer_form_deleted"));
            redirect("admin/customerAll", 'refresh');
        }

    }

    public function getdatatableajax()
    {
        $this->is_login(4);

        $this->load->model('customers_model');
        $this->load->library('datatables');

        $this->datatables
            ->select("id, name, company, phone, email, city, country")
            ->from("customers")

            ->add_column("Actions",
                "<center><div class='btn-group'><a class=\"tip btn btn-primary btn-xs\" title='" . $this->lang->line("customer_form_edit") . "' href='customerEdit?id=$1'><i class=\"fa fa-edit\"></i></a> <a class=\"tip btn btn-danger btn-xs\" title='" . $this->lang->line("customer_form_delete") . "' href='customerDelete?id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_customer') . "')\"><i class=\"fa fa-trash-o\"></i></a></div></center>", "id")
            ->unset_column('id');

        echo $this->datatables->generate();
    }


    // ***************************** SALES CONTROLLERS ***************************** //
    public function salesAllInvoices()
    {
        $this->is_login(4);

        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $this->data['success_message'] = $this->session->flashdata('success_message');

        $user = $this->ion_auth->user()->row();
        $this->data['from_name'] = $user->first_name . " " . $user->last_name;
        $this->data['from_email'] = $user->email;
        $this->session->set_userdata('page', 8);
        $this->data['ptitle'] = lang('sale_page_add_title');
        $this->tmadmin->tmView('sales_panel/sales_all_invoice', $this->data);
    }

    public function getsalesdatatableajax()
    {
        $this->is_login(4);

        $check = NULL;

        $email_url = site_url() . 'uploadify/testing/';

        $opt = "<center>
    			<div class='btn-group' style='margin:0;'>
    				<!-- <a class=\"tooltp add_payment btn btn-success btn-xs\" data-toggle=\"tooltip\" title='" . $this->lang->line("sale_add_payment") . "' href='#' id='$1' data-customer='$2'><i class=\"fa fa-briefcase\"></i></a> -->
    				<a class=\"tooltp btn btn-success btn-xs\" data-toggle=\"tooltip\" title='" . $this->lang->line("view_invoice") . "' href='#' onClick=\"MyWindow=window.open('saleViewInvoice?id=$1', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\"><i class=\"fa fa-file-text-o\"></i></a>
    				<a class=\"tooltp btn btn-primary btn-xs\" data-toggle=\"tooltip\" title='" . $this->lang->line("download_pdf") . "' href='salePdf?id=$1'><i class=\"fa fa-download\"></i></a>
					<!-- <a class=\"tooltp email_inv btn btn-success btn-xs\" data-toggle=\"tooltip\" title='" . $this->lang->line("email_invoice") . "' href='#' id='$1' data-customer='$2'><i class=\"fa fa-envelope\"></i></a> -->
					<a class=\"tooltp btn btn-warning btn-xs\" data-toggle=\"tooltip\" title='" . $this->lang->line("edit_invoice") . "' href='saleEdit?id=$1'><i class=\"fa fa-edit\"></i></a>
					<a class=\"tooltp btn btn-danger btn-xs\" data-toggle=\"tooltip\" title='" . $this->lang->line("delete_invoice") . "' href='saleDelete?id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_invoice') . "')\"><i class=\"fa fa-trash-o\"></i></a>
				</div>
				</center>";

        if ($this->input->get('customer_id')) {
            $customer_id = $this->input->get('customer_id');
        } else {
            $customer_id = NULL;
        }

        $this->load->model('sales_model');
        $this->load->library('datatables');
        $this->datatables
            ->select("sales.id as id, sales.date as date, reference_no, sales.user, customer_name, total+COALESCE(shipping, 0) as total, COALESCE(sum(payment.amount), 0) as amount, (total+COALESCE(shipping, 0))-COALESCE(sum(payment.amount), 0) as balance, status, sales.customer_id as cid", FALSE)
            ->from('sales')
            ->join('payment', 'payment.invoice_id=sales.id', 'left')
            ->group_by('sales.id');
        if ($customer_id) {
            $this->datatables->where('sales.customer_id', $customer_id);
        }
        if ($check) {
            $this->datatables->where('sales.user', LI_USER);
        }
        $this->datatables->edit_column('status', '$1-$2', 'status, id')
            ->add_column("Actions", $opt, "id, cid")

            ->unset_column('id')
            ->unset_column('cid');;


        echo $this->datatables->generate();
    }

    public function salesListQuotations()
    {
        $this->is_login(4);

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $this->data['success_message'] = $this->session->flashdata('success_message');

        $user = $this->ion_auth->user()->row();
        $this->data['from_name'] = $user->first_name . " " . $user->last_name;
        $this->data['from_email'] = $user->email;
        $this->db->where('user', LI_USER);
        $this->db->join('estate', 'estate.id = quotes.estate_id');
        $query = $this->db->get('quotes');
        $this->data['quotes'] = $query->result();
        $this->data['ptitle'] = lang('sale_quotes');
        $this->session->set_userdata('page', 8);
        $this->tmadmin->tmView('sales_panel/quotes', $this->data);
    }

    public function saleGetquotes()
    {
        $this->load->library('datatables');

        $this->datatables
            ->select("id, date, reference_no, user, customer_name, inv_total, total_tax, COALESCE(shipping, 0) as shipping, (total+COALESCE(shipping, 0)) as total, customer_id as cid", FALSE)
            ->from('quotes');
        if ($check) {
            $this->datatables->where('user', LI_USER);
        }
        $this->datatables->add_column("Actions",
            "<center><div class='btn-group'><a class=\"btn btn-primary btn-xs tooltp\" data-toggle=\"tooltip\" title='" . $this->lang->line("view_quote") . "' href='#' onClick=\"MyWindow=window.open('saleviewQuote?id=$1', 'MyWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;\"><i class=\"fa fa-file-text-o\"></i></a>
			<a class=\"btn btn-success btn-xs tooltp\" data-toggle=\"tooltip\" title='" . $this->lang->line("quote_to_invoice") . "' href='saleConvert?id=$1'><i class=\"fa fa-share\"></i></a>
			<a class=\"btn btn-primary btn-xs tooltp\" data-toggle=\"tooltip\" title='" . $this->lang->line("sale_download_pdf") . "' href='salePdfQuote?id=$1'><i class=\"fa fa-download\"></i></a>
			<!-- <a class=\"btn email_inv btn-success btn-xs tooltp\" data-toggle=\"tooltip\" title='" . $this->lang->line("sale_email_quote") . "' href='#' id='$1' data-customer='$2'><!--<a class=\"tip  btn btn-primary btn-xs\" title='" . $this->lang->line("sale_email_quote") . "' href='saleEmailQuote?id=$1'><i class=\"fa fa-envelope\"></i></a> -->
			<a class=\"btn btn-warning btn-xs tooltp\" data-toggle=\"tooltip\" title='" . $this->lang->line("sale_edit_quote") . "' href='saleEditQuote?id=$1'><i class=\"fa fa-edit\"></i></a>
			<a class=\"btn btn-danger btn-xs tooltp\" data-toggle=\"tooltip\" title='" . $this->lang->line("sale_delete_quote") . "' href='saleDeleteQuote?id=$1' onClick=\"return confirm('" . $this->lang->line('sale_alert_x_quote') . "')\"><i class=\"fa fa-trash-o\"></i></a></div></center>", "id, cid")

            ->unset_column('id')
            ->unset_column('cid');

        echo $this->datatables->generate();

    }

    public function saleGetCE()
    {

        //if($this->input->post('id')){ $id = $this->input->post('id'); } else { $id = NULL; break; }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
           // break;
        }

        $this->load->model('sales_model');

        $cus = $this->sales_model->getCustomerByID($id);

        echo json_encode(array('ce' => $cus->email));


    }

    public function saleSendEmail()
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        } else {
            $id = NULL;
            //break;
        }
        if ($this->input->post('to')) {
            $to = $this->input->post('to');
        } else {
            $to = NULL;
           // break;
        }
        if ($this->input->post('subject')) {
            $subject = $this->input->post('subject');
        } else {
            $subject = NULL;
        }
        if ($this->input->post('note')) {
            $message = $this->input->post('note');
        } else {
            $message = NULL;
        }


        $user = $this->ion_auth->user()->row();
        $from_name = $user->first_name . " " . $user->last_name;
        $from = $user->email;


        if ($this->email($id, $to, $from_name, $from, $subject, $message)) {
            echo $this->lang->line("sale_sent");
        } else {
            echo $this->lang->line("sale_x_sent");
        }

    }

    public function saleSendQuote()
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        } else {
            $id = NULL;
           // break;
        }
        if ($this->input->post('to')) {
            $to = $this->input->post('to');
        } else {
            $to = NULL;
         //   break;
        }
        if ($this->input->post('subject')) {
            $subject = $this->input->post('subject');
        } else {
            $subject = NULL;
        }
        if ($this->input->post('note')) {
            $message = $this->input->post('note');
        } else {
            $message = NULL;
        }


        $user = $this->ion_auth->user()->row();
        $from_name = $user->first_name . " " . $user->last_name;
        $from = $user->email;


        if ($this->emailQ($id, $to, $from_name, $from, $subject, $message)) {
            echo $this->lang->line("sale_sent");
        } else {
            echo $this->lang->line("sale_x_sent");
        }

    }

    /* -------------------------------------------------------------------------------------------------------------------------------- */
    //view inventory as html page

    public function saleViewInvoice()
    {
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        } else {
            $sale_id = NULL;
        }

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);

        $inv = $this->sales_model->getInvoiceBySaleID($sale_id);
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->sales_model->getCompanyDetails();
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);
        $this->data['payment'] = $this->sales_model->getPaymetnBySaleID($sale_id);
        $this->data['paid'] = $this->sales_model->getPaidAmount($sale_id);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;

        $this->data['ptitle'] = lang('invoice');
        $this->tmadmin->tmView('sales_panel/view_invoice', $this->data);

    }

    public function saleviewQuote()
    {
        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        } else {
            $quote_id = NULL;
        }

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $this->data['rows'] = $this->sales_model->getAllQuoteItems($quote_id);

        $inv = $this->sales_model->getQuoteByID($quote_id);
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->sales_model->getCompanyDetails();
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);

        $this->data['inv'] = $inv;
        $this->data['sid'] = $quote_id;

        $this->data['ptitle'] = lang('sale_invoice');
        $this->tmadmin->tmView('sales_panel/view_quote', $this->data);

    }
    /* -------------------------------------------------------------------------------------------------------------------------------- */

    /* -------------------------------------------------------------------------------------------------------------------------------- */
    //Add new sales

    public function salesAddInvoice()
    {
        $this->is_login(4);

        $this->load->model('sales_model');

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //validate form input
        $this->form_validation->set_rules('status', $this->lang->line("sale_status"), 'required|xss_clean');
        $this->form_validation->set_rules('reference_no', $this->lang->line("sale_reference_no"), 'required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line("sale_date"), 'required|xss_clean');
        $this->form_validation->set_rules('customer', $this->lang->line("sale_customer"), 'required|xss_clean');
        $this->form_validation->set_rules('quantity1', $this->lang->line("sale_quantity") . " 1", 'required|integer|xss_clean');
        $this->form_validation->set_rules('product1', $this->lang->line("sale_product") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('tax_rate1', $this->lang->line("sale_tax_rate") . ' 1', 'required|is_natural_no_zero|xss_clean');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("sale_unit_price") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("sale_note"), 'xss_clean');

        if ($this->input->post('customer') == 'new') {
            $this->form_validation->set_rules('name', $this->lang->line("sale_customer") . " " . $this->lang->line("sale_name"), 'required|xss_clean');
            $this->form_validation->set_rules('email', $this->lang->line("sale_customer") . " " . $this->lang->line("sale_email_address"), 'required|valid_email');
            $this->form_validation->set_rules('company', $this->lang->line("sale_customer") . " " . $this->lang->line("sale_company"), 'xss_clean');
            $this->form_validation->set_rules('address', $this->lang->line("sale_address"), 'xss_clean');
            $this->form_validation->set_rules('city', $this->lang->line("sale_city"), 'xss_clean');
            $this->form_validation->set_rules('state', $this->lang->line("sale_state"), 'xss_clean');
            $this->form_validation->set_rules('postal_code', $this->lang->line("sale_postal_code"), 'xss_clean');
            $this->form_validation->set_rules('country', $this->lang->line("sale_country"), 'xss_clean');
            $this->form_validation->set_rules('phone', $this->lang->line("sale_phone"), 'required|xss_clean|min_length[9]|max_length[16]');
        }
        /*
    		for($i=1; $i<=TOTAL_ROWS; $i++){
    	$this->form_validation->set_rules('quantity'.$i, 'Quantity', 'is_natural_no_zero');
    	}
    	*/
        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true) {
            $inv_date = trim($this->input->post('date'));
            if (JS_DATE == 'dd-mm-yy' || JS_DATE == 'dd/mm/yy' || JS_DATE == 'dd.mm.yy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } else {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            }
            $reference_no = $this->input->post('reference_no');
            $status = $this->input->post('status');
            $shipping = $this->input->post('shipping');

            if ($this->input->post('customer') == 'new') {

                $customer_name = $this->input->post('name');
                $customer_data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'company' => $this->input->post('company'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'postal_code' => $this->input->post('postal_code'),
                    'state' => $this->input->post('state'),
                    'country' => $this->input->post('country')
                );

            } else {
                $customer_id = $this->input->post('customer');
                $customer_details = $this->sales_model->getCustomerByID($customer_id);
                $customer_name = $customer_details->name;
            }
            $note = $this->input->post('note');

            $inv_total_no_tax = 0;

            for ($i = 1; $i <= TOTAL_ROWS; $i++) {
                if ($this->input->post($quantity . $i) && $this->input->post($product . $i) && $this->input->post($tax_rate . $i) && $this->input->post($unit_price . $i)) {


                    $tax_id = $this->input->post($tax_rate . $i);
                    $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                    $taxRate = $tax_details->rate;
                    $taxType = $tax_details->type;
                    $tax_rate_id[] = $tax_id;

                    $inv_quantity[] = $this->input->post($quantity . $i);
                    $inv_product_name[] = $this->input->post($product . $i);
                    $inv_unit_price[] = $this->input->post($unit_price . $i);
                    $inv_gross_total[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                    if ($taxType == 1 && $taxType != 0) {
                        $val_tax[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)) * $taxRate / 100);
                    } else {
                        $val_tax[] = $taxRate;
                    }

                    if ($taxType == 1) {
                        $tax[] = $taxRate . "%";
                    } else {
                        $tax[] = $taxRate;
                    }

                    $inv_total_no_tax += (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $inv_total_no_tax + $total_tax;


            $keys = array("product_name", "tax_rate_id", "tax", "quantity", "unit_price", "gross_total", "val_tax");

            $items = array();
            foreach (array_map(null, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value) {
                $items[] = array_combine($keys, $value);
            }
            if ($this->input->post('customer') == 'new') {
                $saleDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'status' => $status,
                    'shipping' => $shipping,
                );
            } else {
                $saleDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'status' => $status,
                    'shipping' => $shipping,
                );
                $customer_data = array();
            }
        }


        if ($this->form_validation->run() == true && $this->sales_model->addSale($saleDetails, $items, $customer_data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("sale_added"));
            redirect("admin/salesAllInvoices", 'refresh');

        } else {

            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['reference_no'] = array('name' => 'reference_no',
                'id' => 'reference_no',
                'type' => 'text',
                'value' => $this->form_validation->set_value('reference_no'),
            );
            $this->data['date'] = array('name' => 'date',
                'id' => 'date',
                'type' => 'text',
                'value' => $this->form_validation->set_value('date'),
            );

            $this->data['customer'] = array('name' => 'customer',
                'id' => 'customer',
                'type' => 'select',
                'value' => $this->form_validation->set_select('customer'),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('note'),
            );

            $this->session->set_userdata('page', 8);

            $this->data['customers'] = $this->sales_model->getAllCustomers();
            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();

            $this->data['ptitle'] = lang('sale_add_title');
            $this->tmadmin->tmView('sales_panel/add', $this->data);
        }
    }

    //Add new quote

    public function salesAddQuotation()
    {
        $this->is_login(4);

        $this->load->model('sales_model');

        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line("no_zero_required"));
        //validate form input
        $this->form_validation->set_rules('reference_no', $this->lang->line("sale_reference_no"), 'required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line("sale_date"), 'required|xss_clean');
        $this->form_validation->set_rules('customer', $this->lang->line("customer"), 'required|xss_clean');
        $this->form_validation->set_rules('quantity1', $this->lang->line("sale_quantity") . " 1", 'required|integer|xss_clean');
        $this->form_validation->set_rules('product1', $this->lang->line("sale_product") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('tax_rate1', $this->lang->line("sale_tax_rate") . ' 1', 'required|is_natural_no_zero|xss_clean');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("sale_unit_price") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');
        if ($this->input->post('customer') == 'new') {
            $this->form_validation->set_rules('name', $this->lang->line("customer") . " " . $this->lang->line("customer_form_name"), 'required|xss_clean');
            $this->form_validation->set_rules('email', $this->lang->line("customer") . " " . $this->lang->line("customer_form_email_address"), 'required|valid_email');
            $this->form_validation->set_rules('company', $this->lang->line("customer") . " " . $this->lang->line("customer_form_company"), 'xss_clean');
            $this->form_validation->set_rules('address', $this->lang->line("customer_form_address"), 'xss_clean');
            $this->form_validation->set_rules('city', $this->lang->line("customer_form_city"), 'xss_clean');
            $this->form_validation->set_rules('state', $this->lang->line("customer_form_state"), 'xss_clean');
            $this->form_validation->set_rules('postal_code', $this->lang->line("customer_form_postal_code"), 'xss_clean');
            $this->form_validation->set_rules('country', $this->lang->line("customer_form_country"), 'xss_clean');
            $this->form_validation->set_rules('phone', $this->lang->line("customer_form_phone"), 'required|xss_clean|min_length[9]|max_length[16]');
        }

        /*
    		for($i=1; $i<=TOTAL_ROWS; $i++){
    	$this->form_validation->set_rules('quantity'.$i, 'Quantity', 'is_natural_no_zero');
    	}
    	*/

        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true) {
            $inv_date = trim($this->input->post('date'));
            if (JS_DATE == 'dd-mm-yy' || JS_DATE == 'dd/mm/yy' || JS_DATE == 'dd.mm.yy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } else {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            }
            $reference_no = $this->input->post('reference_no');

            if ($this->input->post('customer') == 'new') {

                $customer_name = $this->input->post('name');
                $customer_data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'company' => $this->input->post('company'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'postal_code' => $this->input->post('postal_code'),
                    'state' => $this->input->post('state'),
                    'country' => $this->input->post('country')
                );

            } else {
                $customer_id = $this->input->post('customer');
                $customer_details = $this->sales_model->getCustomerByID($customer_id);
                $customer_name = $customer_details->name;
            }
            $note = $this->input->post('note');
            $shipping = $this->input->post('shipping');
            $estate_id = $this->input->post('estate_id');
            $down_payment = $this->input->post('down_payment');
            $monthly_payment = $this->input->post('monthly_payment');

            $inv_total_no_tax = 0;

            for ($i = 1; $i <= TOTAL_ROWS; $i++) {
                if ($this->input->post($quantity . $i) && $this->input->post($product . $i) && $this->input->post($tax_rate . $i) && $this->input->post($unit_price . $i)) {


                    $tax_id = $this->input->post($tax_rate . $i);
                    $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                    $taxRate = $tax_details->rate;
                    $taxType = $tax_details->type;
                    $tax_rate_id[] = $tax_id;

                    $inv_quantity[] = $this->input->post($quantity . $i);
                    $inv_product_name[] = $this->input->post($product . $i);
                    $inv_unit_price[] = $this->input->post($unit_price . $i);
                    $inv_gross_total[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                    if ($taxType == 1 && $taxType != 0) {
                        $val_tax[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)) * $taxRate / 100);
                    } else {
                        $val_tax[] = $taxRate;
                    }

                    if ($taxType == 1) {
                        $tax[] = $taxRate . "%";
                    } else {
                        $tax[] = $taxRate;
                    }

                    $inv_total_no_tax += (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $inv_total_no_tax + $total_tax;


            $keys = array("product_name", "tax_rate_id", "tax", "quantity", "unit_price", "gross_total", "val_tax");

            $items = array();
            foreach (array_map(null, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value) {
                $items[] = array_combine($keys, $value);
            }

            if ($this->input->post('customer') == 'new') {
                $quoteDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'shipping' => $shipping,
                    'estate_id' => $estate_id,
                    'down_payment' => $down_payment,
                    'monthly_payment' => $monthly_payment
                );
            } else {
                $quoteDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'shipping' => $shipping,
                    'estate_id' => $estate_id,
                    'down_payment' => $down_payment,
                    'monthly_payment' => $monthly_payment
                );
                $customer_data = array();
            }
        }


        if ($this->form_validation->run() == true && $this->sales_model->addQuote($quoteDetails, $items, $customer_data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("quote_added"));
            redirect("admin/salesListQuotations", 'refresh');

        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['reference_no'] = array('name' => 'reference_no',
                'id' => 'reference_no',
                'type' => 'text',
                'value' => $this->form_validation->set_value('reference_no'),
            );
            $this->data['date'] = array('name' => 'date',
                'id' => 'date',
                'type' => 'text',
                'value' => $this->form_validation->set_value('date'),
            );

            $this->data['customer'] = array('name' => 'customer',
                'id' => 'customer',
                'type' => 'select',
                'value' => $this->form_validation->set_select('customer'),
            );
            $this->data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'textarea',
                'value' => $this->form_validation->set_value('note'),
            );

            $this->data['customers'] = $this->sales_model->getAllCustomers();
            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();
            $this->data['estate'] = $this->sales_model->getAllEstates();
            $this->session->set_userdata('page', 8);
            $this->data['estate'] = $this->sales_model->getAllEstates();
            $this->data['ptitle'] = lang('sale_new_quote_title');
            $this->tmadmin->tmView('sales_panel/add_quote', $this->data);

        }
    }

    //Add new quote

    public function saleConvert()
    {
        $this->is_login(4);

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        if (RESTRICT_SALES) {
            if ($this->input->get('id')) {
                $id = $this->input->get('id');
            } else {
                $id = NULL;
            }
            if ($this->ion_auth->in_group('sales')) {
                $inv = $this->sales_model->getQuoteByID($id);
                if (USER_ID != $inv->user_id) {
                    $this->session->set_flashdata('message', $this->lang->line("access_denied"));
                    $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
                    redirect('admin/salesAllInvoices', 'refresh');
                }
            }
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

        $this->data['customers'] = $this->sales_model->getAllCustomers();
        $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();

        $this->data['inv'] = $this->sales_model->getQuoteByID($id);
        $this->data['inv_products'] = $this->sales_model->getAllQuoteItems($id);
        $meta['page_title'] = $this->lang->line("quote_to_invoice");
        $this->data['page_title'] = $this->lang->line("quote_to_invoice");

        $this->data['id'] = $id;

        $this->data['ptitle'] = lang('quote_to_invoice');
        $this->tmadmin->tmView('sales_panel/convert', $this->data);
    }

    /* -------------------------------------------------------------------------------------------------------------------------------- */
    //Edit sale

    public function saleEdit()
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }

        $this->load->model('sales_model');

        //validate form input
        $this->form_validation->set_rules('reference_no', $this->lang->line("sale_reference_no"), 'required|xss_clean');
        $this->form_validation->set_rules('status', $this->lang->line("sale_status"), 'required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line("sale_date"), 'required|xss_clean');
        $this->form_validation->set_rules('customer', $this->lang->line("sale_customer"), 'required|xss_clean');
        $this->form_validation->set_rules('quantity1', $this->lang->line("sale_quantity") . ' 1', 'required|integer|xss_clean');
        $this->form_validation->set_rules('product1', $this->lang->line("sale_products") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("sale_unit_price") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');

        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true) {
            $inv_date = trim($this->input->post('date'));
            if (JS_DATE == 'dd-mm-yy' || JS_DATE == 'dd/mm/yy' || JS_DATE == 'dd.mm.yy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } else {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            }
            $reference_no = $this->input->post('reference_no');
            $status = $this->input->post('status');

            if ($this->input->post('customer') == 'new') {

                $customer_name = $this->input->post('name');
                $customer_data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'company' => $this->input->post('company'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'postal_code' => $this->input->post('postal_code'),
                    'state' => $this->input->post('state'),
                    'country' => $this->input->post('country')
                );

            } else {
                $customer_id = $this->input->post('customer');
                $customer_details = $this->sales_model->getCustomerByID($customer_id);
                $customer_name = $customer_details->name;
            }
            $note = $this->input->post('note');
            $shipping = $this->input->post('shipping');

            $inv_total_no_tax = 0;

            for ($i = 1; $i <= TOTAL_ROWS; $i++) {
                if ($this->input->post($quantity . $i) && $this->input->post($product . $i) && $this->input->post($tax_rate . $i) && $this->input->post($unit_price . $i)) {


                    $tax_id = $this->input->post($tax_rate . $i);
                    $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                    $taxRate = $tax_details->rate;
                    $taxType = $tax_details->type;
                    $tax_rate_id[] = $tax_id;
                    $sid[] = $id;
                    $inv_quantity[] = $this->input->post($quantity . $i);
                    $inv_product_name[] = $this->input->post($product . $i);
                    $inv_unit_price[] = $this->input->post($unit_price . $i);
                    $inv_gross_total[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                    if ($taxType == 1 && $taxType != 0) {
                        $val_tax[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)) * $taxRate / 100);
                    } else {
                        $val_tax[] = $taxRate;
                    }

                    if ($taxType == 1) {
                        $tax[] = $taxRate . "%";
                    } else {
                        $tax[] = $taxRate;
                    }

                    $inv_total_no_tax += (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $inv_total_no_tax + $total_tax;


            $keys = array("sale_id", "product_name", "tax_rate_id", "tax", "quantity", "unit_price", "gross_total", "val_tax");

            $items = array();
            foreach (array_map(null, $sid, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value) {
                $items[] = array_combine($keys, $value);
            }
            if ($this->input->post('customer') == 'new') {
                $saleDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'status' => $status,
                    'shipping' => $shipping,
                );
            } else {
                $saleDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'status' => $status,
                    'shipping' => $shipping,
                );
                $customer_data = array();
            }
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateSale($id, $saleDetails, $items)) {
            $this->session->set_flashdata('success_message', $this->lang->line("sale_updated"));
            redirect("module=sales", 'refresh');

        } else { //display the create biller form
            //set the flash data error message if there is one

            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['customers'] = $this->sales_model->getAllCustomers();
            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();

            $this->data['inv'] = $this->sales_model->getInvoiceByID($id);
            $this->data['inv_products'] = $this->sales_model->getAllInvoiceItems($id);
            $this->data['id'] = $id;

            $this->data['ptitle'] = $this->lang->line("update_sale");
            $this->tmadmin->tmView('sales_panel/edit', $this->data);

        }
    }

    //Edit quote

    public function saleEditQuote()
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }

        $this->load->model('sales_model');

        //validate form input
        $this->form_validation->set_rules('reference_no', $this->lang->line("sale_reference_no"), 'required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line("sale_date"), 'required|xss_clean');
        $this->form_validation->set_rules('customer', $this->lang->line("sale_customer"), 'required|xss_clean');
        $this->form_validation->set_rules('quantity1', $this->lang->line("sale_quantity") . ' 1', 'required|integer|xss_clean');
        $this->form_validation->set_rules('product1', $this->lang->line("sale_products") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('unit_price1', $this->lang->line("sale_unit_price") . ' 1', 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("note"), 'xss_clean');

        $quantity = "quantity";
        $product = "product";
        $unit_price = "unit_price";
        $tax_rate = "tax_rate";

        if ($this->form_validation->run() == true) {
            $inv_date = trim($this->input->post('date'));
            if (JS_DATE == 'dd-mm-yy' || JS_DATE == 'dd/mm/yy' || JS_DATE == 'dd.mm.yy') {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 3, 2) . "-" . substr($inv_date, 0, 2);
            } else {
                $date = substr($inv_date, -4) . "-" . substr($inv_date, 0, 2) . "-" . substr($inv_date, 3, 2);
            }
            $reference_no = $this->input->post('reference_no');

            if ($this->input->post('customer') == 'new') {

                $customer_name = $this->input->post('name');
                $customer_data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'company' => $this->input->post('company'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'postal_code' => $this->input->post('postal_code'),
                    'state' => $this->input->post('state'),
                    'country' => $this->input->post('country')
                );

            } else {
                $customer_id = $this->input->post('customer');
                $customer_details = $this->sales_model->getCustomerByID($customer_id);
                $customer_name = $customer_details->name;
            }
            $note = $this->input->post('note');
            $shipping = $this->input->post('shipping');

            $inv_total_no_tax = 0;

            for ($i = 1; $i <= TOTAL_ROWS; $i++) {
                if ($this->input->post($quantity . $i) && $this->input->post($product . $i) && $this->input->post($tax_rate . $i) && $this->input->post($unit_price . $i)) {


                    $tax_id = $this->input->post($tax_rate . $i);
                    $tax_details = $this->sales_model->getTaxRateByID($tax_id);
                    $taxRate = $tax_details->rate;
                    $taxType = $tax_details->type;
                    $tax_rate_id[] = $tax_id;
                    $qid[] = $id;
                    $inv_quantity[] = $this->input->post($quantity . $i);
                    $inv_product_name[] = $this->input->post($product . $i);
                    $inv_unit_price[] = $this->input->post($unit_price . $i);
                    $inv_gross_total[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                    if ($taxType == 1 && $taxType != 0) {
                        $val_tax[] = (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)) * $taxRate / 100);
                    } else {
                        $val_tax[] = $taxRate;
                    }

                    if ($taxType == 1) {
                        $tax[] = $taxRate . "%";
                    } else {
                        $tax[] = $taxRate;
                    }

                    $inv_total_no_tax += (($this->input->post($quantity . $i)) * ($this->input->post($unit_price . $i)));

                }
            }

            $total_tax = array_sum($val_tax);
            $total = $inv_total_no_tax + $total_tax;


            $keys = array("quote_id", "product_name", "tax_rate_id", "tax", "quantity", "unit_price", "gross_total", "val_tax");

            $items = array();
            foreach (array_map(null, $qid, $inv_product_name, $tax_rate_id, $tax, $inv_quantity, $inv_unit_price, $inv_gross_total, $val_tax) as $key => $value) {
                $items[] = array_combine($keys, $value);
            }

            if ($this->input->post('customer') == 'new') {
                $quoteDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'shipping' => $shipping,
                );
            } else {
                $quoteDetails = array('reference_no' => $reference_no,
                    'date' => $date,
                    'user' => LI_USER,
                    'customer_id' => $customer_id,
                    'customer_name' => $customer_name,
                    'note' => $note,
                    'inv_total' => $inv_total_no_tax,
                    'total_tax' => $total_tax,
                    'total' => $total,
                    'shipping' => $shipping,
                );
                $customer_data = array();
            }
        }

        if ($this->form_validation->run() == true && $this->sales_model->updateQuote($id, $quoteDetails, $items)) {
            $this->session->set_flashdata('success_message', $this->lang->line("quote_updated"));
            redirect("admin/salesListQuotations", 'refresh');

        } else { //display the create biller form
            //set the flash data error message if there is one

            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['customers'] = $this->sales_model->getAllCustomers();
            $this->data['tax_rates'] = $this->sales_model->getAllTaxRates();


            $this->data['inv'] = $this->sales_model->getQuoteByID($id);
            $this->data['inv_products'] = $this->sales_model->getAllQuoteItems($id);
            $this->data['id'] = $id;

            $this->data['ptitle'] = $this->lang->line("update_quote");
            $this->tmadmin->tmView('sales_panel/edit_quote', $this->data);

        }
    }

    /*-------------------------------*/
    public function saleDelete($id = NULL)
    {
        $this->load->model('sales_model');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }

        if ($this->sales_model->deleteInvoice($id)) { //check to see if we are deleting the product
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("invoice_deleted"));
            redirect('admin/salesAllInvoices', 'refresh');
        }

    }

    public function saleDeleteQuote($id = NULL)
    {
        $this->load->model('sales_model');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }


        if ($this->sales_model->deleteQuote($id)) { //check to see if we are deleting the product
            //redirect them back to the admin page
            $this->session->set_flashdata('message', $this->lang->line("invoice_deleted"));
            redirect('admin/salesListQuotations', 'refresh');
        }

    }
    /* -------------------------------------------------------------------------------------------------------------------------------- */
    //generate pdf and force to download

    public function salePdf()
    {
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        } else {
            $sale_id = NULL;
        }

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);

        $inv = $this->sales_model->getInvoiceBySaleID($sale_id);
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->sales_model->getCompanyDetails();
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);
        $this->data['payment'] = $this->sales_model->getPaymetnBySaleID($sale_id);
        $this->data['paid'] = $this->sales_model->getPaidAmount($sale_id);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;

        $this->data['page_title'] = $this->lang->line("sale_invoice");

        $this->load->library('MPDF53/mpdf');

        $mpdf = new mPDF('win-1252', 'A4', '12', '', 10, 10, 10, 10, 9, 9, 'P');
        $mpdf->useOnlyCoreFonts = true; // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Invoice");
        $mpdf->SetAuthor("Tecdiary.net");
        $mpdf->SetCreator('Invoice Manager');
        $mpdf->SetWatermarkText("Invoice Manager");
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.025;
        $mpdf->SetDisplayMode('fullpage');

        //$html =  $this->load->view('view_invoice', $data, TRUE);
        $html = $this->load->view('admin/sales_panel/view_invoice', $this->data, TRUE);

        $name = $this->lang->line("sale_invoice") . " " . $this->lang->line("sale_no") . " " . $inv->id . ".pdf";

        $search = array("<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">");
        $replace = array("<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>");

        $html = str_replace($search, $replace, $html);

        $mpdf->WriteHTML($html);

        $mpdf->Output($name, 'D');

        exit;
    }

    public function salePdfQuote()
    {
        if ($this->input->get('id')) {
            $quote_id = $this->input->get('id');
        } else {
            $quote_id = NULL;
        }

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $this->data['rows'] = $this->sales_model->getAllQuoteItems($quote_id);

        $inv = $this->sales_model->getQuoteByID($quote_id);
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->sales_model->getCompanyDetails();
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);

        $this->data['inv'] = $inv;

        $this->data['page_title'] = $this->lang->line("quote");


        $this->load->library('MPDF53/mpdf');

        $mpdf = new mPDF('win-1252', 'A4', '12', '', 10, 10, 10, 10, 9, 9, 'P');
        $mpdf->useOnlyCoreFonts = true; // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Invoice");
        $mpdf->SetAuthor("Tecdiary.net");
        $mpdf->SetCreator('Invoice Manager');
        $mpdf->SetWatermarkText("Invoice Manager");
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.025;
        $mpdf->SetDisplayMode('fullpage');

        $html = $this->load->view('admin/sales_panel/view_quote', $this->data, TRUE);
        $name = $this->lang->line("quote") . " " . $this->lang->line("sale_no") . " " . $inv->id . ".pdf";

        $search = array("<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">");
        $replace = array("<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>");

        $html = str_replace($search, $replace, $html);

        $mpdf->WriteHTML($html);

        $mpdf->Output($name, 'D');

        exit;
    }
    /* -------------------------------------------------------------------------------------------------------------------------------- */
    //email inventory as html and send pdf as attachment

    public function saleEmail($sale_id = NULL, $to, $from_name, $from, $subject, $note)
    {
        if ($this->input->get('id')) {
            $sale_id = $this->input->get('id');
        }

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $this->data['rows'] = $this->sales_model->getAllInvoiceItems($sale_id);

        $inv = $this->sales_model->getInvoiceBySaleID($sale_id);
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->sales_model->getCompanyDetails();
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);
        $this->data['payment'] = $this->sales_model->getPaymetnBySaleID($sale_id);
        $this->data['paid'] = $this->sales_model->getPaidAmount($sale_id);
        $this->data['inv'] = $inv;
        $this->data['sid'] = $sale_id;


        $this->data['page_title'] = $this->lang->line("invoice");

        $this->load->library('MPDF53/mpdf');

        $mpdf = new mPDF('win-1252', 'A4', '12', '', 10, 10, 10, 10, 9, 9, 'P');
        $mpdf->useOnlyCoreFonts = true; // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Invoice");
        $mpdf->SetAuthor("Tecdiary.net");
        $mpdf->SetCreator('Invoice Manager');
        $mpdf->SetWatermarkText("Invoice Manager");
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.025;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->setBasePath(base_url());

        $html = $this->load->view('admin/sales_panel/view_invoice', $this->data, TRUE);
        $name = $this->lang->line("invoice") . " " . $this->lang->line("no") . " " . $inv->id . ".pdf";

        $search = array("<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">");
        $replace = array("<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>");

        $html = str_replace($search, $replace, $html);

        $mpdf->WriteHTML($html);

        //$mpdf->Output($name, 'F');
        $mpdf->Output($name, 'F', 'assets/uploads');

        $email_data = $this->load->view('admin/sales_panel/view_invoice', $data, TRUE);

        $search = array("<body>", "<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">", "class=\"table table-bordered table-hover table-striped\"");
        $replace = array("<body style='max-width:800px;'>", "<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>", "border='1' width='100%'");

        $email_data = str_replace($search, $replace, $email_data);

        if ($note) {
            $message = $note . "<br /><hr>" . $email_data;
        } else {
            $message = $email_data;
        }

        $this->load->library('email');

        $config['mailtype'] = 'html';
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);

        $this->email->from($from, $from_name);
        $this->email->to($to);

        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/uploads/' . $name);

        if ($this->email->send()) {
            // email sent
            unlink('assets/uploads/' . $name);
            //echo $this->email->print_debugger(); die();
            return true;
        } else {
            //email not sent
            unlink('assets/uploads/' . $name);
            //echo $this->email->print_debugger(); die();
            return false;
        }

    }

    public function saleEmailQ($id, $to, $from_name, $from, $subject, $note)
    {
        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
        $this->data['rows'] = $this->sales_model->getAllQuoteItems($id);

        $inv = $this->sales_model->getQuoteByID($id);
        $customer_id = $inv->customer_id;
        $this->data['biller'] = $this->sales_model->getCompanyDetails();
        $this->data['customer'] = $this->sales_model->getCustomerByID($customer_id);

        $this->data['inv'] = $inv;


        $this->data['page_title'] = $this->lang->line("quote");

        $this->load->library('MPDF53/mpdf');

        $mpdf = new mPDF('win-1252', 'A4', '12', '', 10, 10, 10, 10, 9, 9, 'P');
        $mpdf->useOnlyCoreFonts = true; // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Invoice");
        $mpdf->SetAuthor("Tecdiary.net");
        $mpdf->SetCreator('Invoice Manager');
        $mpdf->SetWatermarkText("Invoice Manager");
        $mpdf->showWatermarkText = false;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.025;
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->setBasePath(base_url());

        $html1 = $this->load->view('admin/sales_panel/view_quote', $this->data, TRUE);
        $name = $this->lang->line("quote") . " " . $this->lang->line("sale_no") . " " . $inv->id . ".pdf";

        $search = array("<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">");
        $replace = array("<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>");

        $html1 = str_replace($search, $replace, $html1);

        $mpdf->WriteHTML($html1);

        $mpdf->Output($name, 'F', 'assets/uploads');

        $html = $this->load->view('admin/sales_panel/view_quote', $data, TRUE);

        $search = array("<body>", "<div class=\"row-fluid\">", "<div class=\"span6\">", "<div class=\"span2\">", "<div class=\"span10\">", "class=\"table table-bordered table-hover table-striped\"");
        $replace = array("<body style='max-width:800px;'>", "<div style='width: 100%;'>", "<div style='width: 48%; float: left;'>", "<div style='width: 18%; float: left;'>", "<div style='width: 78%; float: left;'>", "border='1' width='100%'");

        $html = str_replace($search, $replace, $html);

        if ($note) {
            $message = $note . "<br /><hr>" . $html;
        } else {
            $message = $html;
        }

        $this->load->library('email');

        $config['mailtype'] = 'html';
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);

        $this->email->from($from, $from_name);
        $this->email->to($to);

        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->attach('assets/uploads/' . $name);


        if ($this->email->send()) {
            // email sent
            //$this->email->print_debugger(); die();

            unlink('assets/uploads/' . $name);
            return true;
        } else {
            //email not sent
            unlink('assets/uploads/' . $name);
            return false;
        }
    }


    public function saleEmailInvoice()
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        //validate form input
        $this->form_validation->set_rules('to', $this->lang->line("to") . " " . $this->lang->line("email"), 'required|valid_email|xss_clean');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim|xss_clean');

        if ($this->form_validation->run() == true) {
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            $message = $this->input->post('note');
            $user = $this->ion_auth->user()->row();
            $from_name = $user->first_name . " " . $user->last_name;
            $from = $user->email;
        }

        if ($this->form_validation->run() == true && $this->email($id, $to, $from_name, $from, $subject, $message)) { //check to see if we are creating the biller
            //redirect them back to the admin page

            $this->session->set_flashdata('success_message', $this->lang->line("sent"));
            redirect("admin/salesAllInvoices", 'refresh');

        } else { //display the create biller form
            //set the flash data error message if there is one

            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['to'] = array('name' => 'to',
                'id' => 'to',
                'type' => 'text',
                'value' => $this->form_validation->set_value('to'),
            );
            $data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject'),
            );
            $data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note'),
            );


            $user = $this->ion_auth->user()->row();
            $data['from_name'] = $user->first_name . " " . $user->last_name;
            $data['from_email'] = $user->email;

            //get customer by invoice
            $inv = $this->sales_model->getInvoiceByID($id);
            $customer_id = $inv->customer_id;
            //get customer details
            $data['cus'] = $this->sales_model->getCustomerByID($customer_id);
            $data['id'] = $id;
            $data['quote_id'] = NULL;
            $meta['page_title'] = $this->lang->line("email") . " " . $this->lang->line("invoice");
            $data['page_title'] = $this->lang->line("email") . " " . $this->lang->line("invoice");


            $this->load->view('commons/header', $meta);
            $this->load->view('email', $data);
            $this->load->view('commons/footer');

        }
    }

    public function saleEmailQuote()
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        } else {
            $id = NULL;
        }

        $this->load->model('sales_model');

        $this->locale = 'en_US';
        $this->load->library('mywords');
        $this->mywords->load('Numbers/Words');

        //validate form input
        $this->form_validation->set_rules('to', $this->lang->line("to") . " " . $this->lang->line("email"), 'required|valid_email|xss_clean');
        $this->form_validation->set_rules('subject', $this->lang->line("subject"), 'required|xss_clean');
        $this->form_validation->set_rules('note', $this->lang->line("message"), 'trim|xss_clean');

        if ($this->form_validation->run() == true) {
            $to = $this->input->post('to');
            $subject = $this->input->post('subject');
            $message = $this->input->post('note');
            $user = $this->ion_auth->user()->row();
            $from_name = $user->first_name . " " . $user->last_name;
            $from = $user->email;
        }

        if ($this->form_validation->run() == true && $this->emailQ($id, $to, $from_name, $from, $subject, $message)) { //check to see if we are creating the biller
            //redirect them back to the admin page

            $this->session->set_flashdata('success_message', $this->lang->line("sent"));
            redirect("module=sales&view=quotes", 'refresh');

        } else { //display the create biller form
            //set the flash data error message if there is one

            $data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $data['to'] = array('name' => 'to',
                'id' => 'to',
                'type' => 'text',
                'value' => $this->form_validation->set_value('to'),
            );
            $data['subject'] = array('name' => 'subject',
                'id' => 'subject',
                'type' => 'text',
                'value' => $this->form_validation->set_value('subject'),
            );
            $data['note'] = array('name' => 'note',
                'id' => 'note',
                'type' => 'text',
                'value' => $this->form_validation->set_value('note'),
            );


            $user = $this->ion_auth->user()->row();
            $data['from_name'] = $user->first_name . " " . $user->last_name;
            $data['from_email'] = $user->email;

            //get customer by invoice
            $inv = $this->sales_model->getQuoteByID($id);
            $customer_id = $inv->customer_id;
            //get customer details
            $data['cus'] = $this->sales_model->getCustomerByID($customer_id);
            $data['id'] = $id;
            $data['quote_id'] = NULL;
            $meta['page_title'] = $this->lang->line("email") . " " . $this->lang->line("quote");
            $data['page_title'] = $this->lang->line("email") . " " . $this->lang->line("quote");


            $this->load->view('commons/header', $meta);
            $this->load->view('email_quote', $data);
            $this->load->view('commons/footer');

        }
    }

    /*----------------------------------------------------------------------------------------------------------------------------------*/
    public function saleUpdateStatus()
    {
        $this->load->model('sales_model');

        if ($this->input->post('id')) {
            $id = $this->input->post('id');
        } else {
            $id = NULL;
           // break;
        }
        if ($this->input->post('status')) {
            $status = $this->input->post('status');
        } else {
            $status = NULL;
          //  break;
        }
        if ($id && $status) {
            if ($this->sales_model->updateStatus($id, $status)) {
                $this->session->set_flashdata('success_message', $this->lang->line("status_updated"));
                redirect("module=sales", 'refresh');
            }
        }

        return false;
    }

    public function saleAddPayment()
    {
        $this->load->model('sales_model');

        if ($this->input->post('invoice_id')) {
            $invoice_id = $this->input->post('invoice_id');
        } else {
            $invoice_id = NULL;
           // break;
        }
        if ($this->input->post('customer_id')) {
            $customer_id = $this->input->post('customer_id');
        } else {
            $customer_id = NULL;
          //  break;
        }
        if ($this->input->post('note')) {
            $note = $this->input->post('note');
        } else {
            $note = NULL;
        }
        if ($this->input->post('amount')) {
            $amount = $this->input->post('amount');
        } else {
            $amount = NULL;
         //   break;
        }
        if ($invoice_id && $customer_id && $amount) {
            if ($this->sales_model->addPaument($invoice_id, $customer_id, $amount, $note)) {
                $this->session->set_flashdata('success_message', $this->lang->line("amount_added"));
                redirect("module=sales", 'refresh');
            }
        }

        return false;
    }

    public function salePrDetails()
    {
        if ($this->input->get('name')) {
            $name = $this->input->get('name');
        }

        $this->load->model('sales_model');

        if ($item = $this->sales_model->getProductByName($name)) {

            $price = $item->price;
            $tax_rate = $item->tax_rate;

            $product = array('price' => $price, 'tax_rate' => $tax_rate);

        }

        echo json_encode($product);

    }
    /*** -------------------------- SALES ACTION OVER ---------------------***/

    /*** --------------------------- INVOICE SETTING -----------------------------***/

    public function invoiceGeneralSetting()
    {
        $this->is_login(4);

        $this->load->model('settings_model');
        $this->session->set_userdata('page', 10);
        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $this->data['success_message'] = $this->session->flashdata('success_message');

        //validate form input
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('currency_prefix', $this->lang->line('currency_code'), 'required|max_length[3]|xss_clean');
        $this->form_validation->set_rules('tax_rate', $this->lang->line('default_tax_rate'), 'required|is_natural_no_zero|xss_clean');
        $this->form_validation->set_rules('date_format', $this->lang->line('date_format'), 'required|xss_clean');


        if ($this->form_validation->run() == true) {

            $data = array(
                'currency_prefix' => $this->input->post('currency_prefix'),
                'default_tax_rate' => $this->input->post('tax_rate'),
                'dateformat' => $this->input->post('date_format'),
                'major' => $this->input->post('major'),
                'minor' => $this->input->post('minor'),
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateSetting($data)) {
            //check to see if we are updating
            //redirect them back to the setting page
            $this->session->set_flashdata('success_message', $this->lang->line('setting_updated'));
            redirect("admin/invoiceGeneralSetting", 'refresh');
        } else {

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['success_message'] = $this->session->flashdata('success_message');

            $this->data['settings'] = $this->settings_model->getSettings();
            $this->data['date_formats'] = $this->settings_model->getDateFormats();
            $this->data['tax_rates'] = $this->settings_model->getAllTaxRates();
        }

        $this->data['ptitle'] = lang('invoice_page_title');
        $this->tmadmin->tmView('invoice_panel/invoice_settings', $this->data);
    }

    public function invoiceCompanyDetails()
    {
        $this->is_login(4);

        $this->load->model('settings_model');

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'required|valid_email');
        $this->form_validation->set_rules('company', $this->lang->line("company"), 'required|xss_clean');
        $this->form_validation->set_rules('cf1', $this->lang->line("cf1"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("cf2"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("cf3"), 'xss_clean');
        $this->form_validation->set_rules('cf4', $this->lang->line("cf4"), 'xss_clean');
        $this->form_validation->set_rules('cf5', $this->lang->line("cf5"), 'xss_clean');
        $this->form_validation->set_rules('cf6', $this->lang->line("cf6"), 'xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'required|xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'required|xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'required|xss_clean');
        $this->form_validation->set_rules('country', $this->lang->line("country"), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required|xss_clean|min_length[9]|max_length[16]');

        if ($this->form_validation->run() == true) {

            if (DEMO) {
                $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
                redirect('module=home', 'refresh');
            }
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateCompany($data)) { //check to see if we are updateing the customer
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line("details_updated"));
            redirect("module=settings&view=company_details", 'refresh');
        } else { //display the update form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));
            $this->data['success_message'] = $this->session->flashdata('success_message');
            $this->session->set_userdata('page', 10);

            $this->data['details'] = $this->settings_model->getCompanyDetails();

            $this->data['ptitle'] = lang('invoice_company_details_title');
            $this->tmadmin->tmView('invoice_panel/invoice_company_details', $this->data);
        }
    }

    public function invoiceTaxRates()
    {
        $this->is_login(4);

        $this->load->model('settings_model');

        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $this->data['success_message'] = $this->session->flashdata('success_message');
        $this->data['tax_rates'] = $this->settings_model->getAllTaxRates();
        $this->session->set_userdata('page', 10);
        $this->data['ptitle'] = lang('invoice_tax_rates_title');
        $this->tmadmin->tmView('invoice_panel/tax_rates', $this->data);
    }

    public function invoiceAddTaxRate()
    {
        $this->is_login(4);
        $this->session->set_userdata('page', 10);
        $this->load->model('settings_model');

        //validate form input
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('name', $this->lang->line('estate_title'), 'required|xss_clean');
        $this->form_validation->set_rules('rate', $this->lang->line('sale_tax_rate'), 'required|xss_clean');
        $this->form_validation->set_rules('type', $this->lang->line('estate_add_selectbox_type'), 'required|is_natural_no_zero|xss_clean');


        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'rate' => $this->input->post('rate'),
                'type' => $this->input->post('type')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->addTaxRate($data)) { //check to see if we are updating
            //redirect them back to the setting page
            $this->session->set_flashdata('success_message', $this->lang->line('tax_rate_added'));
            redirect("admin/invoiceTaxRates", 'refresh');
        } else {

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['ptitle'] = lang('invoice_add_tax_rates_title');
            $this->tmadmin->tmView('invoice_panel/add_tax_rate', $this->data);
        }
    }

    public function invoiceEditTaxRate($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->is_login(4);

        $this->load->model('settings_model');

        //validate form input
        $this->form_validation->set_message('is_natural_no_zero', $this->lang->line('no_zero_required'));
        $this->form_validation->set_rules('name', $this->lang->line('estate_title'), 'required|xss_clean');
        $this->form_validation->set_rules('rate', $this->lang->line('sale_tax_rate'), 'required|xss_clean');
        $this->form_validation->set_rules('type', $this->lang->line('estate_add_selectbox_type'), 'required|is_natural_no_zero|xss_clean');


        if ($this->form_validation->run() == true) {

            $data = array('name' => $this->input->post('name'),
                'rate' => $this->input->post('rate'),
                'type' => $this->input->post('type')
            );
        }

        if ($this->form_validation->run() == true && $this->settings_model->updateTaxRate($id, $data)) { //check to see if we are updating
            //redirect them back to the setting page
            $this->session->set_flashdata('success_message', $this->lang->line('tax_rate_updated'));
            redirect("admin/invoiceTaxRates", 'refresh');
        } else {

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['tax_rate'] = $this->settings_model->getTaxRateByID($id);
            $this->data['id'] = $id;

            $this->data['ptitle'] = lang('update_tax_rate');
            $this->tmadmin->tmView('invoice_panel/edit_tax_rate', $this->data);
        }
    }

    function invoiceDeleteTaxRate($id = NULL)
    {
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $this->is_login(4);

        $this->load->model('settings_model');

        if ($this->settings_model->deleteTaxRate($id)) {
            $this->session->set_flashdata('success_message', $this->lang->line("tax_rate_deleted"));
            redirect("admin/invoiceTaxRates", 'refresh');
        }

    }

    function delete_invoice_type($id = NULL)
    {
        if (DEMO) {
            $this->session->set_flashdata('message', $this->lang->line("disabled_in_demo"));
            redirect('module=home', 'refresh');
        }
        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->settings_model->deleteInvoiceType($id)) {
            $this->session->set_flashdata('success_message', $this->lang->line("invoice_type_deleted"));
            redirect('module=settings&view=invoice_types', 'refresh');
        }

    }


    function invoiceChangeLogo()
    {
        $this->is_login(4);
        $this->session->set_userdata('page', 10);
        $this->load->model('settings_model');

        //validate form input
        $this->form_validation->set_rules('logo', 'Logo Image', 'xss_clean');

        if ($this->form_validation->run() == true) {
            if ($_FILES['logo']['size'] > 0) {

                $this->load->library('upload_photo');

                //Set the config
                $config['upload_path'] = 'assets/admin/img/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '300';
                $config['max_width'] = '300';
                $config['max_height'] = '80';
                $config['overwrite'] = FALSE;

                //Initialize
                $this->upload_photo->initialize($config);

                if (!$this->upload_photo->do_upload('logo')) {
                    //echo the errors
                    $error = $this->upload_photo->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("admin/invoiceChangeLogo", 'refresh');
                }

                //If the upload success
                $photo = $this->upload_photo->file_name;

            } else {
                $this->session->set_flashdata('message', $this->lang->line('not_uploaded'));
                redirect("admin/invoiceChangeLogo", 'refresh');
            }


        }

        if ($this->form_validation->run() == true && $this->settings_model->updateInvoiceLogo($photo)) {

            //check to see if we are updateing the product
            //redirect them back to the admin page
            $this->session->set_flashdata('success_message', $this->lang->line('logo_changed'));
            redirect("admin/invoiceChangeLogo", 'refresh');
        } else {
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
            $this->data['success_message'] = $this->session->flashdata('success_message');

            $this->data['ptitle'] = lang('change_invoice_logo');
            $this->tmadmin->tmView('invoice_panel/invoice_logo', $this->data);
        }
    }

    /** SUPPLIERS MODULES **/
    public function listSuppliers()
    {
        $this->is_login(4);

        $this->load->library('form_validation');
        $this->load->model('suppliers_model');

        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
        $this->data['success_message'] = $this->session->flashdata('success_message');
        $this->session->set_userdata('page', 13);
        $this->data['ptitle'] = lang('suppliers');
        $this->tmadmin->tmView('supplier_panel/content', $this->data);
    }

    function supplier_getdatatableajax()
    {
        $this->is_login(4);

        $this->load->model('suppliers_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->datatables
            ->select("id, name, company, phone, email, city, country")
            ->from("suppliers")

            ->add_column("Actions",
                "<center><div class='btn-group'><a class=\"tip btn btn-primary btn-xs\" title='" . $this->lang->line("edit_supplier") . "' href='editSupplier?id=$1'><i class=\"fa fa-edit\"></i></a> <a class=\"tip btn btn-danger btn-xs\" title='" . $this->lang->line("delete_supplier") . "' href='deleteSupplier?id=$1' onClick=\"return confirm('" . $this->lang->line('alert_x_supplier') . "')\"><i class=\"fa fa-trash-o\"></i></a></div></center>", "id")
            ->unset_column('id');

        echo $this->datatables->generate();

    }

    function newSupplier()
    {
        $this->is_login(4);

        $this->load->library('form_validation');
        $this->load->model('suppliers_model');

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'required|valid_email');
        $this->form_validation->set_rules('company', $this->lang->line("company"), 'required|xss_clean');
        $this->form_validation->set_rules('cf1', $this->lang->line("scf1"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("scf2"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("scf3"), 'xss_clean');
        $this->form_validation->set_rules('cf4', $this->lang->line("scf4"), 'xss_clean');
        $this->form_validation->set_rules('cf5', $this->lang->line("scf5"), 'xss_clean');
        $this->form_validation->set_rules('cf6', $this->lang->line("scf6"), 'xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'required|xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'required|xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'required|xss_clean');
        $this->form_validation->set_rules('country', $this->lang->line("country"), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required|xss_clean|min_length[9]|max_length[16]');


        if ($this->form_validation->run() == true) {
            $name = strtolower($this->input->post('name'));
            $email = $this->input->post('email');
            $company = $this->input->post('company');

            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone')

            );
        }

        if ($this->form_validation->run() == true && $this->suppliers_model->addSupplier($name, $email, $company, $data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("supplier_added"));
            redirect("module=suppliers", 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['name'] = array('name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('name'),
            );
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array('name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );

            $this->data['address'] = array('name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => $this->form_validation->set_value('address'),
            );
            $this->data['city'] = array('name' => 'city',
                'id' => 'city',
                'type' => 'text',
                'value' => $this->form_validation->set_value('city'),
            );
            $this->data['state'] = array('name' => 'state',
                'id' => 'state',
                'type' => 'text',
                'value' => $this->form_validation->set_value('state'),
            );
            $this->data['postal_code'] = array('name' => 'postal_code',
                'id' => 'postal_code',
                'type' => 'text',
                'value' => $this->form_validation->set_value('postal_code'),
            );
            $this->data['country'] = array('name' => 'country',
                'id' => 'country',
                'type' => 'text',
                'value' => $this->form_validation->set_value('country'),
            );
            $this->data['phone'] = array('name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone'),
            );

            $this->session->set_userdata('page', 13);
            $this->data['ptitle'] = lang('new_supplier');
            $this->tmadmin->tmView('supplier_panel/add', $this->data);
        }
    }

    function editSupplier($id = NULL)
    {
        $this->is_login(4);

        $this->load->library('form_validation');
        $this->load->model('suppliers_model');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line("name"), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line("email_address"), 'required|valid_email');
        $this->form_validation->set_rules('company', $this->lang->line("company"), 'required|xss_clean');
        $this->form_validation->set_rules('cf1', $this->lang->line("scf1"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("scf2"), 'xss_clean');
        $this->form_validation->set_rules('cf2', $this->lang->line("scf3"), 'xss_clean');
        $this->form_validation->set_rules('cf4', $this->lang->line("scf4"), 'xss_clean');
        $this->form_validation->set_rules('cf5', $this->lang->line("scf5"), 'xss_clean');
        $this->form_validation->set_rules('cf6', $this->lang->line("scf6"), 'xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line("address"), 'required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line("city"), 'required|xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line("state"), 'required|xss_clean');
        $this->form_validation->set_rules('postal_code', $this->lang->line("postal_code"), 'required|xss_clean');
        $this->form_validation->set_rules('country', $this->lang->line("country"), 'required|xss_clean');
        $this->form_validation->set_rules('phone', $this->lang->line("phone"), 'required|xss_clean|min_length[9]|max_length[16]');


        if ($this->form_validation->run() == true) {
            $data = array('name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'company' => $this->input->post('company'),
                'cf1' => $this->input->post('cf1'),
                'cf2' => $this->input->post('cf2'),
                'cf3' => $this->input->post('cf3'),
                'cf4' => $this->input->post('cf4'),
                'cf5' => $this->input->post('cf5'),
                'cf6' => $this->input->post('cf6'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'state' => $this->input->post('state'),
                'postal_code' => $this->input->post('postal_code'),
                'country' => $this->input->post('country'),
                'phone' => $this->input->post('phone')

            );
        }

        if ($this->form_validation->run() == true && $this->suppliers_model->updateSupplier($id, $data)) {
            $this->session->set_flashdata('success_message', $this->lang->line("supplier_updated"));
            redirect("admin/listSuppliers", 'refresh');

        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['supplier'] = $this->suppliers_model->getSupplierByID($id);

            $meta['page_title'] = $this->lang->line("update_supplier");
            $this->data['id'] = $id;

            $this->data['ptitle'] = lang('update_supplier');
            $this->tmadmin->tmView('supplier_panel/edit', $this->data);

        }
    }

    function deleteSupplier($id = NULL)
    {
        $this->is_login(4);

        $this->load->model('suppliers_model');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        if ($this->suppliers_model->deleteSupplier($id)) {
            $this->session->set_flashdata('success_message', $this->lang->line("supplier_deleted"));
            redirect("admin/listSuppliers", 'refresh');
        }

    }

    function addSuppliersCsv()
    {

        $this->is_login(4);
        $this->session->set_userdata('page', 13);
        $this->load->library('form_validation');
        $this->load->model('suppliers_model');

        $this->form_validation->set_rules('userfile', $this->lang->line("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (isset($_FILES["userfile"])) /*if($_FILES['userfile']['size'] > 0)*/ {

                $this->load->library('upload');

                $config['upload_path'] = 'assets/admin/uploads/csv/';
                $config['allowed_types'] = 'csv';
                $config['max_size'] = '200';
                $config['overwrite'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {

                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('message', $error);
                    redirect("admin/addSuppliersCsv", 'refresh');
                }

                $csv = $this->upload->file_name;

                $arrResult = array();
                $handle = fopen("assets/admin/uploads/csv/" . $csv, "r");
                if ($handle) {
                    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $arrResult[] = $row;
                    }
                    fclose($handle);
                }
                $titles = array_shift($arrResult);

                $keys = array('name', 'email', 'phone', 'company', 'address', 'city', 'state', 'postal_code', 'country');

                $final = array();

                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }
                $rw = 2;
                foreach ($final as $csv) {
                    if ($this->suppliers_model->getSupplierByEmail($csv['email'])) {
                        $this->session->set_flashdata('message', $this->lang->line("check_supplier_email") . " (" . $csv['email'] . "). " . $this->lang->line("supplier_already_exist") . " " . $this->lang->line("line_no") . " " . $rw);
                        redirect("admin/addSuppliersCsv", 'refresh');
                    }
                    $rw++;
                }
            }

            $final = $this->mres($final);
            //$data['final'] = $final;
        }

        if ($this->form_validation->run() == true && $this->suppliers_model->add_suppliers($final)) {
            $this->session->set_flashdata('success_message', $this->lang->line("suppliers_added"));
            redirect('admin/listSuppliers', 'refresh');
        } else {

            $this->data['message'] = (validation_errors() ? validation_errors() : $this->session->flashdata('message'));

            $this->data['userfile'] = array('name' => 'userfile',
                'id' => 'userfile',
                'type' => 'text',
                'value' => $this->form_validation->set_value('userfile')
            );

            $this->data['ptitle'] = lang('add_suppliers_by_csv');
            $this->tmadmin->tmView('supplier_panel/add_by_csv', $this->data);
        }

    }

    function mres($q)
    {
        if (is_array($q))
            foreach ($q as $k => $v)
                $q[$k] = $this->mres($v); //recursive
        elseif (is_string($q))
            $q = mysql_real_escape_string($q);
        return $q;
    }

    // More Function
    public function viewStatics()
    {
        $this->is_login(2);
        $this->data['ptitle'] = lang('statics_page_head');

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

        $this->tmadmin->tmView('other/statics', $this->data);
    }

    public function viewSlider()
    {
        $this->is_login(3);
        $this->data['ptitle'] = lang('slider_page_title');

        //validate form input
        $this->form_validation->set_rules('title', $this->lang->line('estate_title'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $title = $this->input->post('title');
            $photo = $this->input->post('photo');
            foreach ($title as $key => $value) {
                $data[$photo[$key]] = $value;
            }

            $data = array('slider' => json_encode($data));
        }

        if ($this->form_validation->run() == true && $this->admin_estate_model->updateGeneral($data)) {
            $this->session->set_flashdata('message', $this->admin_estate_model->errors());
            $this->session->set_flashdata('success', $this->admin_estate_model->messages());
            redirect_back();
        } else {

            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            $getData = json_decode($this->admin_estate_model->getSetings('slider')->value);
            $this->data['photoData'] = $getData;

            $this->data['title'] = array(
                'name' => 'title[]',
                'id' => 'title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('title'),
            );
            $this->data['photo'] = array(
                'name' => 'photo[]',
                'id' => 'photo',
            );

            $this->tmadmin->tmView('other/slider', $this->data);
        }
    }

    public function viewSearch()
    {
        $this->is_login(3);

        if ($this->input->get('ss')) {
            $this->session->set_userdata('src', $this->input->get('ss'));
            $likeStr = $this->input->get('ss');
        } else {
            $likeStr = $this->session->userdata('src');
        }

        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/viewSearch/', $this->admin_estate_model->estateSearch($likeStr, NULL, TRUE), 10);

        $this->data['estates'] = $this->admin_estate_model->estateSearch($likeStr);
        $this->data['ptitle'] = lang('search_title');
        $this->tmadmin->tmView('other/search', $this->data);
    }

    public function clearCache()
    {
        $this->load->library('clr_output');
        $this->clr_output->clear_all_cache();
        redirect_back();
    }

    /*
    * Message Controller Methods
    *
    * @developer   Nurul Amin Muhit
    * @package     Real State CMS Pro
    *
    */

    public function messages()
    {
        $this->is_login(2);

        /* Load Requires */
        $this->load->helper('text');

        /* Retrieve userID From Session */
        $user = $this->session->userdata('user_id');

        /* Pagination */
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);

        $per_page = 10;
        $base_where = array();

        $this->admin_estate_model->paginationLimit($per_page, $this->uri->segment(4));

        /* Retrieve Messages From Database */
        $this->data['messages'] = $this->_get_page($this->uri->segment(3));
        $this->data['msg_details'] = $this->_get_page($this->uri->segment(3));

        /* Page Driven */
        if ($this->uri->segment(3) == 'inbox') {
            $base_where['user_to'] = $user;
            $total_rows = $this->admin_estate_model->msg_count_by($base_where);
            $this->data['pagination'] = $this->paginationCreate('/admin/messages/inbox/', $total_rows, $per_page);

            $this->data['ptitle'] = lang('message_inbox_title');
            $loaded = 'inbox';
        } elseif ($this->uri->segment(3) == 'comments') {
            $this->data['ptitle'] = lang('message_comments_title');
            $loaded = 'comments';
        } elseif ($this->uri->segment(3) == 'new_message') {
            $this->data['ptitle'] = lang('message_new_message_title');
            $loaded = 'new_message';
        } elseif ($this->uri->segment(3) == 'message') {
            $this->data['ptitle'] = lang('message_message_title');
            $loaded = 'message';
        } elseif ($this->uri->segment(3) == 'view_sent') {
            $this->data['ptitle'] = lang('message_view_sent_title');
            $loaded = 'view_sent';
        } else {
            $base_where['user_from'] = $user;
            $total_rows = $this->admin_estate_model->msg_count_by($base_where);
            $this->data['pagination'] = $this->paginationCreate('/admin/messages/sent/', $total_rows, $per_page);

            $this->data['ptitle'] = lang('message_sent_title');
            $loaded = 'sent';
        }
        $this->session->set_userdata('page', 16);
        $this->tmadmin->tmView('messages/' . $loaded, $this->data);
    }

    public function messagesInsert()
    {

        $this->load->library('upload');

        $upload_folder = FCPATH . '/uploads';

        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, DIR_WRITE_MODE, true);
        }

        $this->upload_config = array(
            'upload_path' => $upload_folder,
            'allowed_types' => '*',
            'max_size' => 10240,
            'remove_space' => true,
            'encrypt_name' => true,
        );

        $this->upload->initialize($this->upload_config);

        if (!$this->upload->do_upload() AND isset($_POST['userfile'])) {
            $this->session->set_flashdata('message', $this->lang->line('message_failed_upload'));
            redirect('admin/messages/new_message');
        } else {
            $data = $this->upload->data();
            $user = $this->db->get_where('users', array('username' => $_POST['user_to']))->row()->id;
            if (!empty($user)) {
                $file_id = $this->admin_estate_model->insert_file($data['file_name'], $user, $_POST['subject'], $_POST['message']);
                $this->session->set_flashdata('message', $this->lang->line('message_sent_successfully'));
            } else {
                $this->session->set_flashdata('message', $this->lang->line('message_user_not_found'));
            }
            redirect('admin/messages/new_message');
        }
    }

    public function messagesReply()
    {
        $this->is_login(2);

        if ($this->input->post()) {
            $data = array(
                'user_to' => $this->input->post('user_to'),
                'user_from' => $this->session->userdata('user_id'),
                'subject' => $this->input->post('subject'),
                'message' => $this->input->post('message')
            );
            $this->db->insert('messages', $data);
            $this->session->set_flashdata('message', lang('message_sent_successfully'));
            redirect('admin/messages/inbox');
        } else {
            $data['page'] = lang('messages');

            $this->tmadmin->tmView('messages/reply', $this->data);
        }
    }

    public function messagesDelete($id = Null)
    {
        $file_id = $this->admin_estate_model->get_attached($id);
        if ($file_id) {
            $this->db->where('attach_id', $file_id);
            $query = $this->db->get('attachments');
            foreach ($query->result() as $row) {
                $name = $row->file_name;
                unlink(FCPATH . '/uploads/' . $name);
            }
            $this->db->where('attach_id', $file_id);
            $this->db->delete('attachments');
        }
        $this->db->where('msg_id', $id);
        $this->db->set('deleted', '1');
        $this->db->update('messages');
        $this->session->set_flashdata('message', lang('message_deleted_successfully'));
        redirect('admin/messages/inbox');
    }

    public function messagesDownload($file_id)
    {
        /* Load Requires */
        $this->load->helper('download');

        if ($this->admin_estate_model->get_file($file_id)) {
            $file = $this->admin_estate_model->get_file($file_id);
            $data = file_get_contents(FCPATH . '/uploads/' . $file->file_name); // Read the file's contents
            force_download($file->file_name, $data);
        } else {
            $this->session->set_flashdata('message', $this->lang->line('message_failure'));
            redirect('messages/inbox');
        }
    }

    /**
     *
     * Members Controller Methods
     *
     * @developer   Nurul Amin Muhit
     * @email:      muhit18@gmail.com
     * @package     Real Estate CMS Pro
     */

    function members()
    {
        $this->is_login(2);
        $this->load->model('mdl_members');

        $this->data['members'] = $this->mdl_members->get_members();
        $this->session->set_userdata('page', 17);
        $this->data['ptitle'] = lang('members');
        $this->tmadmin->tmView('members/members', $this->data);
    }

    function membersLoginActivities()
    {
        $this->is_login(2);
        $this->session->set_userdata('page', 17);
        $this->data['ptitle'] = lang('members');
        $this->data['login_activities'] = $this->admin_estate_model->login_activities();

        $this->tmadmin->tmView('members/login_activities', $this->data);
    }

    function memberships()
    {
        $this->is_login(2);

        $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/memberships/', $this->admin_estate_model->membership_count_by(), 10);
        $this->session->set_userdata('page', 17);
        $this->data['memberships'] = $this->admin_estate_model->memberships();
        $this->data['ptitle'] = lang('memberships') . ' ' . lang('dashboard');

        $this->tmadmin->tmView('members/memberships', $this->data);
    }

    public function membershipsAdd()
    {
        //$this->is_login(2);

        if ($this->input->post()) {
            $data = array(
                'membership' => $this->input->post('membership'),
                'amount' => $this->input->post('amount'),
                'valid_days' => $this->input->post('days'),
                'description' => $this->input->post('description')
            );
            $this->db->insert('memberships', $data);

            $this->_log_activity(lang('added_new_membership'));
            $this->session->set_flashdata('message', lang('record_successfully_created'));
            redirect('admin/memberships');
        } else {
            $this->data['page'] = 17;
            $this->data['ptitle'] = lang('membership') . ' ' . lang('add');

            $this->tmadmin->tmView('members/memberships_add', $this->data);
        }
    }

    public function membershipsEdit()
    {
        $this->is_login(2);

        if ($this->input->post()) {
            $id = $this->input->post('m_id');
            $data = array(
                'membership' => $this->input->post('membership'),
                'amount' => $this->input->post('amount'),
                'valid_days' => $this->input->post('days'),
                'description' => $this->input->post('description')
            );
            $this->db->where('m_id', $id);
            $this->db->update('memberships', $data);
            $this->_log_activity(lang('membership_edited'));
            $this->session->set_flashdata('message', lang('record_successfully_updated'));
            redirect('admin/membershipsEdit/' . $id);
        } else {
            $this->data['membership_profile'] = $this->admin_estate_model->membership_profile($this->uri->segment(3));
            $this->data['ptitle'] = lang('membership') . ' ' . lang('edit');

            $this->tmadmin->tmView('members/memberships_edit', $this->data);
        }
    }

    public function membershipsDelete($id)
    {
        $this->is_login(2);

        $this->db->where('m_id', $id);
        $this->db->delete('memberships');
        $this->_log_activity($this->lang->line('membership_deleted'));
        $this->session->set_flashdata('message', lang('record_successfully_deleted'));
        redirect('admin/memberships');
    }
    /*Start Import*/
    public function import()
    {
        $this->is_login(2);
        $this->session->set_userdata('page', 23);
        $driver_list = $this->admin_estate_model->getSetings('set_export')->value;
        $driver_array = explode(',', $driver_list);
        $module_list = $this->admin_estate_model->getSetings('set_export_module')->value;
        $module_array = explode(',', $module_list);
        $this->data['driver_list'] = $driver_array;
        $this->data['module_list'] = $module_array;
        $this->tmadmin->tmView('export/import', $this->data);
    }
    /*End Import*/
    /*Start XML Export*/
    public function edit_selection(){
        $id = $this->input->post('id');
        $grid_data = $this->admin_estate_model->exportView($id);
        die(json_encode($grid_data));
    }
    public function update_status()
    {
        $id = $this->input->post('id');
        $grid_data = $this->admin_estate_model->exportView($id);
        #\application\helpers\Generic::_setTrace($grid_data);
        if (!empty($grid_data)) {
            if ($grid_data->status == 1) {
                $this->db->update('exports', array('status' => 0), array('id' => $id));
                die(json_encode(array('success' => true, 'msg'=> 'Successfully Deactivate')));
            } elseif ($grid_data->status == 0) {
                $this->db->update('exports', array('status' => 1), array('id' => $id));
                die(json_encode(array('success' => true, 'msg'=> 'Successfully Activate')));
            }
        } else {
            die(json_encode(array('success' => false)));
        }
    }
    public function update_driver_status()
    {
        $id = $this->input->post('id');
        $driver_data = $this->admin_estate_model->driverData($id);
        if (!empty($driver_data)) {
            if ($driver_data->status == 1) {
                $this->db->update('export_drivers', array('status' => 0), array('id' => $id));
                die(json_encode(array('success' => true, 'msg'=> 'Successfully Deactivate', 'tab'=>'driver')));
            } elseif ($driver_data->status == 0) {
                $this->db->update('export_drivers', array('status' => 1), array('id' => $id));
                die(json_encode(array('success' => true, 'msg'=> 'Successfully Activate', 'tab'=>'driver')));
            }
        } else {
            die(json_encode(array('success' => false)));
        }
    }
    public function delete_export()
    {
        $id = $this->input->post('id');
        if($this->db->delete('exports', array('id' => $id))){
            die(json_encode(array('success' => true, 'msg' => 'Successfully Deleted')));
        }
    }
    public function add_export()
    {
        $name = $this->input->post('name');
        $driver = $this->input->post('driver');
        $module = $this->input->post('module');
        if (!empty($name) && !empty($driver) && !empty($module)) {
            $data['name'] = $name;
            $data['driver'] = $driver;
            $data['module'] = $module;
            $data['created'] = date('Y-m-d H:i:s');
            $data['modified'] = date('Y-m-d H:i:s');
            if ($this->db->insert('exports', $data)) {
                die(json_encode(array('success' => true,'msg' => 'Successfully Added')));
            }
        } else
            die(json_encode(array('success' => false, 'msg' => 'Please Fill Out the Form Successfully')));
    }
    public function edit_export()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $driver = $this->input->post('driver');
        $module = $this->input->post('module');
        if (!empty($id)) {
            $data['name'] = $name;
            $data['driver'] = $driver;
            $data['module'] = $module;
            $data['created'] = date('Y-m-d H:i:s');
            $data['modified'] = date('Y-m-d H:i:s');
            if ($this->db->update('exports', $data,array('id' =>$id))) {
                die(json_encode(array('success' => true,'msg' => 'Successfully Updated')));
            }
        }
    }
    public function export()
    {
        $this->is_login(2);
        $this->session->set_userdata('page', 23);
//        $grid_data = $this->admin_estate_model->exportView();
//        $driver_list = $this->admin_estate_model->getDrivers(1);                 //paramitter status
//        $module_list = $this->admin_estate_model->getSetings('set_export_module')->value;
//        $module_array = explode(',', $module_list);
//        $this->data['grid_data'] = $grid_data;
//        $this->data['driver_list'] = $driver_list;
//        $this->data['module_list'] = $module_array;
        $this->tmadmin->tmView('export/index', array());
    }

   public  function exportproperty(){
       $file_name = null;
       $file_type = null;
       if(isset($_REQUEST['feed'])){
           $file_name = $_REQUEST['feed'];

       }
       if(isset($_REQUEST['file_type'])){
           $file_type = $_REQUEST['file_type'];
       }

       if($file_type && $file_name){
           self::DownloadFile($file_name,$file_type);
           exit;
       }

   }



   /* public  function  SendupdatedDataForPreview($data='')
        {
                 $url = 'http://staging.rentalhomes.com/site/importProperty';

            print($data);
                  $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_VERBOSE, 1);
                  curl_setopt($ch, CURLOPT_HEADER, 0);
                  curl_setopt($ch, CURLINFO_HEADER_OUT, false);
                  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                  curl_setopt($ch, CURLOPT_POSTFIELDS, array('pm_data'=>$data));
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //to suppress the curl output
                    curl_setopt($ch, CURLOPT_USERPWD, 'staging:lotdadmin');
                  $response = curl_exec($ch);

            return $response;
                  die(json_encode($response));


        }*/

    public  function PreviewProperty($id){

        $this->is_login(3);
       // $id=(isset($_REQUEST['id']))?$_REQUEST['id']:0;
        if($id > 0){

            $property=$this->admin_estate_model->getPropertyData($id);
            if(isset($property[0]['id'])){
                $property_id=$property[0]['id'];
                $property=$property[0];
                $amenity_data = $this->admin_estate_model->getAmenityData($property_id);
                $aminities=[];
                foreach ($amenity_data as $new_aminity) {
                    $aminities[] = $new_aminity->name;
                }
                $merge_data = array_merge($property, array('aminities' => $aminities));
                $merge_data['unavailability']=$this->admin_estate_model->unavailability($property_id);
                $merge_data['optional_rates']=$this->admin_estate_model->priceRatesEdit($property_id);

                $processed_data['property'] = $this->ProcessStructureForFK($merge_data);

                // for import purpose
                $solr_cls= new \application\helpers\solr_import();
                $formatted_data=$solr_cls->PrepareSolrDataFormat($processed_data);


                echo "                 
                <h4>Thanks for your request. Our team will review your submission within 24 hours and approve if everything is ok.    </h4>
                <hr/>
               <h5> You will get a notification once the updates are available at lefttravel websites.</h5>
             
                <hr/>

                 <hr/>

                 Below is the submitted data.
                  <hr/>
                ";
                \application\helpers\Generic::_setTrace(json_encode($formatted_data));


                // now save
                $res=$solr_cls->importData($formatted_data);


                if($res===true){
                    header("Location: http://staging.rentalhomes.com/individual/PM-$property_id");
                    exit;
                }
                //$res=solr_import::SendupdatedDataForPreview(json_encode($processed_data));

                \application\helpers\Generic::_setTrace($res);

                unset($solr_cls);
            }else{
                die("no property result by this  id");
            }


        }else{
            die("no property id");
        }

    }

    public function GetAllPropertyData()
    {
        $this->is_login(3);
        $merge_data = array();
        $xml_array = array();
        $root_node='';
        $property_data = json_decode(json_encode($this->admin_estate_model->getPropertyData()), true);
        foreach ($property_data as $key => $property) {
            $amenity_data = $this->admin_estate_model->getAmenityData($property['id']);
            $aminities=[];
            foreach ($amenity_data as $new_aminity) {
                $aminities[] = $new_aminity->name;
            }
            $merge_data = array_merge($property, array('aminities' => $aminities));
            $merge_data['unavailability']=$this->admin_estate_model->unavailability($property['id']);
            $merge_data['optional_rates']=$this->admin_estate_model->priceRatesEdit($property['id']);
           # \application\helpers\Generic::_setTrace($merge_data);


            // now only for fk
        if(1){
                $processed_data = $this->ProcessStructureForFK($merge_data);
                //\application\helpers\Generic::_setTrace($processed_data);
                $xml_array['property'][] = $processed_data;
                $root_node='property_data';

            }
            elseif(isset($_GET['feed']) &&  strstr(strtolower($_REQUEST['feed']),'vast')){
                $processed_data = self::ProcessStructureForVast($merge_data);
                $xml_array['listing'][] = $processed_data;
                $root_node='listings';

            }elseif(isset($_GET['feed']) && strstr(strtolower($_REQUEST['feed']),'away')){
                $processed_data = self::ProcessStructureForHw($merge_data);
                $xml_array['unit'][] = $processed_data;
                $root_node='units';

            }


            elseif(isset($_GET['feed']) && strstr(strtolower($_REQUEST['feed']),'otalo')){

                $processed_data = self::ProcessStructureForOTALO($merge_data);
               // \application\helpers\Generic::_setTrace($processed_data);
                $xml_array['property'][] = $processed_data;
                $root_node='properties';

            }
            elseif(isset($_GET['feed']) && strstr(strtolower($_REQUEST['feed']),'room')){

                $processed_data = self::ProcessStructureForRM($merge_data);
                // \application\helpers\Generic::_setTrace($processed_data);
                $xml_array['room'][] = $processed_data;
                $root_node='rooms';

            }


        }


       /* self::outputXML($xml_array,'TESTD');*/

        self::outputXML($xml_array,$root_node,@$_GET['feed']);

        die('success');

    }

      /*===========================End XML Export==============================================================*/



function outputXML($data,$root_node,$file_name){
   // $data=array('dfd'=>'rerer','dfdf'=>array('oop'=>'php','kry'=>'kop'));
    $this->load->library('Array2XML');

    $xml_path=dirname(BASEPATH)."/assets/admin/exports/";
    #\application\helpers\Generic::_setTrace($data);


//print dirname(BASEPATH)."/assets/admin/exports/";
//print $file_name;
    //$xml = Array2XML::createXML('listings', $data);

    chmod($xml_path, 0777);
    $vrbo_file_path=$xml_path.$file_name;

   // print $vrbo_file_path;

    $xml = Array2XML::createXML($root_node, $data);
    $xml->save($vrbo_file_path.'.xml');

}
function  outputCSV($data, $file_name) {
    $xml_path=dirname(BASEPATH)."/assets/admin/exports/";
    $vrbo_file_path=$xml_path.$file_name;

    $output = fopen($vrbo_file_path.".csv",'w') or die("Can't open ".$vrbo_file_path);
    $key=array_keys($data[0]);
    //die(print_r($key));
    fputcsv($output, $key);
    foreach($data as $product) {
       // print_r($product);
        $product['not_available_dates']=json_encode($product['not_available_dates']);
        $product['rate_range']=json_encode($product['rate_range']);
        fputcsv($output, $product);
    }
    fclose($output) or die("Can't close ".$vrbo_file_path);
}

    public static function ProcessStructureForFeed($row)
    {
        $processed_data = array('vast' => self::ProcessStructureForVast($row));
        return $processed_data;
    }
    public static function ProcessStructureForHw($row)
    {
        $row['lat']='';
        $row['lng']='';
        if(isset($row['gps'])){
            $lat_arr=explode(',',$row['gps']);
            if(isset($lat_arr[0])){
                $row['lat']=$lat_arr[0];
            }
            if(isset($lat_arr[1])){
                $row['lng']=$lat_arr[1];
            }
        }

        $array = array(
            '@attributes' => array(
                'advertiser' => '',
            ),
            'source' => array(
                'id' => $row['id'],
            ),
            'unit_details' => array(
                'kind' => $row['estateName'],
                'changeover_day' => 'flexible',
                'min_los' => $row['default_min_los'],
                'floor_area' => $row['id'],
                'title' => $row['title'],
                'headline' => $row['title'],
                'description' => array(
                    'paragraph' => $row['content'],
                    'max_occupancy' => $row['room'],
                    'bedrooms' => array(
                        '@attributes' => array(
                            'number' => $row['room'],
                        )
                    ),
                    'bathrooms' => array(
                        '@attributes' => array(
                            'number' => $row['bathroom'],
                        )
                    ),
                    'images' => array(
                        '@attributes' => array(
                            'last_updated' => date('Y-m-d').'T'.date('H:i:s')//'2012-05-07T19:34:46.743Z',
                        ),
                        'image' => self::get_HW_Images($row['photoGallery']),

                    ),
                    'latitude' => $row['lat'],
                    'longitude' => $row['lng'],
                    'country' => $row['country'],
                    'state' => $row['province'],
                    'city' => $row['city'],
                    'street' => $row['address'],
                    'postcode' => $row['postal_code'],
                    /*'locale' => array(
                        'description' => array(
                            'paragraph' => "Just a ten-minute walk from the shore of beautiful Lake Travisâ€”one of the most desired locations in the region for outdoor recreation, including fishing, boating, swimming, and picnicking."),
                    ),*/
                    'unit_attributes' => array(
                        'attribute' => $row['aminities'],
                    ),
                ),
            ),
                'availability' => array(
                    '@attributes' => array(
                        'available' => false,
                    ),
                    'range' => self::GetHW_Unavailability($row['unavailability']) ,
                ),
                'rates' => array(
                    'description' => array(
                        'paragraph' => "",
                    ),
                    'currency' => 'USD',
                    'rate' => self::GetHW_Rates($row['optional_rates'])//array(
                ,
                ),

        );

return $array;

    }
    public static function ProcessStructureForRM($row)
    {

        $row['lat']='';
        $row['lng']='';
        if(isset($row['gps'])){
            $lat_arr=explode(',',$row['gps']);
            if(isset($lat_arr[0])){
                $row['lat']=$lat_arr[0];
            }
            if(isset($lat_arr[1])){
                $row['lng']=$lat_arr[1];
            }
        }


        $array = Array
        (
            'type' => $row['estateName'],
            'title' =>  $row['title'],
            'address' =>  $row['address'],
            'apartment_number' => '',
            'postal_code' =>  $row['postal_code'],
            'lat' =>  $row['lat'],
            'lng' =>  $row['lng'],
            'city' =>  $row['city'],
            'neighborhood' => $row['title'],
            'description' =>$row['content'],
            'number_of_bedrooms' => $row['room'],
            'number_of_bathrooms' =>$row['bathroom'],
            'floor' => $row['floor'],
            'number_of_double_beds' => '',
            'number_of_single_beds' => '',
            'number_of_sofa_beds' => '',
            'surface' => $row['squaremeter'],
            'surface_unit' => 'metric',
            'max_guests' => $row['room'],
            'minimum_stay' =>$row['default_min_los'],
            'smoking_allowed' => Array
            (
                '@attributes' => Array
                (
                    'type' => 'boolean',
                    'nil' => true
                )

            ),

            'pets_allowed' => Array
            (
                '@attributes' => Array
                (
                    'type' => 'boolean',
                    'nil' => true
                )

            ),

            'children_welcome' => true,
            'check_in_instructions' => 'These are sample check - in instructions',
            'check_in_time' => '',
            'check_out_time' => '',
            'nightly_rate' => $row['default_nightly'],
            'weekly_rate' => $row['default_weekly'],
            'monthly_rate' => '',
            'security_deposit_amount' => '',
            'security_deposit_currency_code' => Array
            (
                '0' => 1,
                '1' => 'cash',
            ),

            'amenities' => implode(',',$row['aminities']),//'bed_linen_and_towels, kitchen, internet, tv, laundry',
            'tax_rate' => '',
            'rate_base_max_guests' => '',
            'extra_guest_surcharge' => '',
            'default_to_available' => '1',
            'cancellation_policy' => 'Cancellation to be made within 24 hours',
            'terms_and_conditions' => 'These are my terms and conditions',
            'services_cleaning_rate' => '',
            'services_cleaning' => true,
            'services_cleaning_required' => Array
            (
                '@attributes' => Array
                (
                    'type' => 'boolean',
                    'nil' => true
                )

            ),

            'services_airport_pickup' => Array
            (
                '@attributes' => Array
                (
                    'type' => 'boolean',
                    'nil' => true,
                )

            ),

            'services_car_rental' => Array
            (
                '@attributes' => Array
                (
                    'type' => 'boolean',
                    'nil' => true
                )

            ),

            'disabled' => false
        );

return $array;

    }
    public  function ProcessStructureForFK($row)
    {
        //\application\helpers\Generic::_setTrace($row);
        $row['lat']='';
        $row['lng']='';
        if(isset($row['gps'])){
            $lat_arr=explode(',',$row['gps']);
            if(isset($lat_arr[0])){
                $row['lat']=$lat_arr[0];
            }
            if(isset($lat_arr[1])){
                $row['lng']=$lat_arr[1];
            }
        }


        $array =array(
            'property_details' => array(
                //'handicap_adapted' => array(),
                'currency' => 'USD',
                'owner_id' => $row['email'],
                'telephone' => $row['telephone'],
                'property_id' => 'PM-'.$row['id'],
                'unit_size_units' => 'meter',
                'property_type' => $row['estateName'],
                //'children' => 'No',
                //'check_in' => array(),
                'bathroom_count' => $row['bathroom'],
                'occupancy' =>  $row['sleep'],
                'unit_size' => $row['squaremeter'],
                //'elder_elevator' => array(),
                //'children_over_five' => array(),
               // 'handicap' => 'Ask',
                'bedroom_count' => $row['room'],
                'check_out' => array(),
                'property_name' => ucwords($row['title']),
                //'elder' => 'Ask',

                'url' =>  $row['gsm'],
                //'pet' => 'No',
            ),
            'property_descriptions' => $row['content'],
            'property_addresses' => array(
                'city' => $row['city'],
                'location_name' => $row['address'],
                'zip' => $row['postal_code'],
                'address1' =>$row['address'],

                'precision' => 'coord',
                'longitude' => $row['lng'],
                'show_exact_address' => 1,
                'state' => $row['province'],
               // 'new_location_id' => '',
                'country' =>$row['country'],
                'latitude' => $row['lat'],
            ),
//            'property_fees' => array(),
//            'property_default_rates' => array(),
//            'review_sum' => array(),
//            'property_themes' => array(),
//            'review_photos' => array(),
//            'review_count' => array(),
//            'property_special' => array(),
//            'property_attributes' => array(
//            ),
//            'property_bathrooms' => array(),
//            'property_nearby_details' => array(),
//            'property_bedrooms' => array(),
//            'property_calendar' => array(),
//            'property_seating' => array(),
            'property_rate_summary' => array(
                'day_max_rate' =>$row['default_nightly'],
               // 'user_week_min_rate' => $row['default_weekly'],
                //'user_month_max_rate' => array(),
                'week_min_rate' => $row['default_weekly'],
                'week_max_rate' =>$row['default_weekly'],
               // 'user_week_max_rate' => $row['default_weekly'],
               // 'user_month_min_rate' => array(),
               // 'month_max_rate' => array(),
               // 'user_day_min_rate' => $row['default_nightly'],
               // 'month_min_rate' => array(),
               // 'user_day_max_rate' =>$row['default_nightly'],
                'day_min_rate' => $row['default_nightly'],
                'minimum_length' =>$row['default_min_los'],
            ),
            'property_amenities' => array(
                'property_amenity' =>($row['aminities'])

            ),

            'property_photos' =>[
                'property_photo'=>self::get_FK_Images($row['photoGallery'])
                ],

            'booked_dates'=>[
                 'booked_date' => self::GetFK_Unavailability($row['unavailability'])
                ],
            'property_rates' =>self::GetFKRates($row['optional_rates']),
        );


        return $array;

    }
    public static function ProcessStructureForOTALO($row)
    {
       # \application\helpers\Generic::_setTrace($row);

        $row['lat']='';
        $row['lng']='';
        if(isset($row['gps'])){
            $lat_arr=explode(',',$row['gps']);
            if(isset($lat_arr[0])){
                $row['lat']=$lat_arr[0];
            }
            if(isset($lat_arr[1])){
                $row['lng']=$lat_arr[1];
            }
        }

        $array=Array
        (
            '@attributes' => Array
            (
                'id' => $row['id'],
                'source' => 'weneedavacation',
                'bedrooms' => $row['room'],
                'sleeps' => $row['room'],
                'baths' => $row['bathroom'],
                'smoking' => false,
                'pets' => false,
                'waterfront' => false,
                'url' => 'http://www.sdpvacationrentals.com/vacation_rentals/' . $row['id'],
            ),

            'title' => $row['title'],
            'contact' => Array
            (
                '@attributes' => Array
                (
                    'owner' => 'sdp vacation rentals',
                ),

            ),

            'description' => $row['content'],
            'location' => Array
            (
                '@attributes' => Array
                (
                    'region' => $row['province'],
                    'state' => $row['province'],
                    'city' => $row['city'],
                    'country' =>$row['country'],
                    'latitude' => $row['lat'],
                    'longitude' => $row['lng'],
                ),

            ),

            'amenities' => Array
            (
                'amenity' => $row['aminities'],

            ),

            'rates' => Array
            (
                '@attributes' => Array
                (
                    'currency' => 'USD',
                ),

                'rate' => self::GetOTALO_Rates($row['optional_rates']),
            ),

            'images' => Array
            (
                'image' =>self::get_OTALO_Images($row['photoGallery']),

            ),

            'availability' => Array
            (
                'month' => self::GetOTALOAvailability($row['unavailability'])


            ),

            'reviews' => Array
            (
                '@attributes' => Array
                (
                    'ratings' => '',
                    'averageRating' => ''
                ),

            ),

        );


       // \application\helpers\Generic::_setTrace($array);

        return $array;

    }



    static function GetOTALOAvailability($row1){

        $row= json_decode($row1);
        $list=array();
        $date_list=array();

        if(is_array($row) && count($row)>0){
            foreach($row as $day){
                $yr=date('Y',strtotime($day));
                $mo=date('m',strtotime($day));
                $d=date('d',strtotime($day));
                $list[$yr.'-'.$mo][]=$d;
            }

            foreach($list as $k=>$val){
                $date=array(
                    '@attributes' => Array
                    (
                        'unknown' => implode(',',$val),
                        'available' => '',
                        'date' => $k,
                        'reserved' => '',
                    ),
                );
                $date_list[]=$date;
            }

        }
         return $date_list;
    }

    static  function GetFKAmenity($row){
        $amenities=array();
        if(is_array($row) && count($row)>0){
            foreach($row as $k=>$val){
                $res1= array(
                    //'description' => array(),
                   // 'parent_amenity' => '',
                    'site_amenity' => $val,
                    'order' => $k,
                );
                $amenities[]=$res1;
            }
        }
        return $amenities;

    }

    static  function  GetFKRates($row){
        $rate=array();
        $data=array();
        # return $rate;
        if( isset($row[0])){

            foreach($row as $k=>$val)
            {


                    $data['end_date'] = $val->end_date;

                                            $data['start_date'] =$val->start_date;
                                            $data['label'] = $val->title;
                                           $data['week_max_rate'] =$val->weekly_price;


                                            $data['minimum_length'] = $val->min_los;
                                            $data['turn_day'] = array();
                                            $data['is_changeover_day_defined'] =array();

                                            $data['week_min_rate'] =$val->nightly_price;
                                            $data['min_stay_unit'] = 'night';


                                            $data['min_stay_count'] = $val->min_los;



                $rate[]=$data;
            }
            $rate=array('property_rate'=>$rate);
        }

        return $rate;


    }

    static function GetHW_Rates($row){
        $rate=array();
       # return $rate;
        if( isset($row[0])){

            foreach($row as $k=>$val)
            {
                $data['starts_at']=$val->start_date;
                $data['ends_at']=$val->end_date;
                $data['title']=$val->title;
                $data['min_los']=$val->min_los;
                $data['night']=$val->nightly_price;
                $data['week']=$val->weekly_price;
                $rate[]=$data;
            }
        }

        return $rate;
    }
    static function GetOTALO_Rates($row){
        $rate=array();
       # return $rate;
        if( isset($row[0])){

            foreach($row as $k=>$val)
            {
              $data=Array
                (
                    '@attributes' => Array
                    (
                        'weekly' => $val->weekly_price,
                        'end_date' => $val->start_date,
                        'start_date' =>$val->start_date,
                    ),
                );
                $rate[]=$data;
            }
        }

        return $rate;
    }

    static  function  GetFK_Unavailability($row){

        $unavail=[];
        # return $unavail;
        if(isset($row) && $row){
            $data=json_decode($row,true);
            if(is_array($data) && count($data)>0){
                foreach($data as $val){
                    //$date['booked_date']=$val;

                    $unavail[]=$val;
                }
            }
        }
        return $unavail;
    }

    static  function  GetHW_Unavailability($row){

        $unavail=array();
       # return $unavail;
        if(isset($row) && $row){
            $data=json_decode($row,true);
            if(is_array($data) && count($data)>0){
                foreach($data as $val){
                    $date['starts_at']=$val;
                    $date['ends_at']=$val;
                    $unavail[]=array('@attributes'=>$date);
                }
            }
        }
        return $unavail;
    }
     static  function  get_HW_Images($row){
        $image=array();
        # return $image;
        if(isset($row) && $row){
            $arr=explode(',',$row);
            if(is_array($arr) && count($arr)>0){
                foreach($arr[0] as $k=>$val){
                    $row1['title']='';
                    $row1['uri']=$val;
                    $image[]=$row1;
                }
            }


        }
        return $image;
    }
    static  function  get_FK_Images($row){
        $image=array();
         //return $image;
        if(isset($row) && $row){
            $arr=json_decode($row,true);

            return $arr;
            if(is_array($arr) && count($arr)>0){
                foreach($arr[0] as $k=>$val){
                    $row1= array(
                        //'description' => array(),
                        'photo_file_name' => $val,
                       // 'base_url' => 'http://www.sdpvacationrentals.com/uploaded/',
                        //'height' => 450,
                        //'width' => 600,
                        //'largest_image_prefix' => '640x480',
                       // 'order' => array(),
                    );
                    $image[]=$row1;
                }
            }


        }
        return $image;
    }
    static  function  get_OTALO_Images($row){
        $image=array();
        # return $image;
        if(isset($row) && $row){
            $arr=json_decode($row,true);
           // die(print_r($arr));
            if(is_array($arr) && count($arr)>0){
                foreach($arr[0] as $k=>$val){

                    $row1= Array
                    (
                        '@attributes' => Array
                        (
                            'src' =>'http://www.sdpvacationrentals.com/uploaded/'.$val,
                        ),

                    );
                    $image[]=$row1;
                }
            }


        }
        return $image;
    }
    public static function GetTotalNumber($str)
    {
        $arr = explode('+', $str);
        $count = 0;
        foreach ($arr as $k => $val) {
            $count = $count + trim($val);
        }
        return $count;
    }

    public static function GetAllImages($images = '')
    {

        $arr = json_decode($images, true);

        $img = array();
        if(is_array($arr) && count($arr)>0){

            foreach ($arr as $val) {
                $img[] = 'http://www.sdpvacationrentals.com/uploaded/' . $val;
            }
            return implode(',', $img);

        }
    }

    public static function ProcessStructureForVast($row)
    {
        #\application\helpers\Generic::_setTrace($row);
        $data = array();
        $data['vacation_rentals_category'] = (isset($row['estateName'])) ? $row['estateName'] : '';
        $data['record_id'] = (isset($row['id'])) ? $row['id'] : '';
        $data['title'] = (isset($row['title'])) ? $row['title'] : '';
        $data['url'] = (isset($row['id'])) ? 'http://www.sdpvacationrentals.com/vacation_rentals/' . $row['id'] : '';
        $data['address'] = (isset($row['address'])) ? $row['address'] : '';
        $data['city'] = (isset($row['city'])) ? $row['city'] : '';
        $data['state'] = (isset($row['province'])) ? $row['province'] : '';
        $data['zip'] = (isset($row['postal_code'])) ? $row['postal_code'] : '';
        $data['country'] = (isset($row['country'])) ? $row['country'] : '';
        $data['listing_time'] = date('Y-m-d-H:i:s');
        $data['expire_time'] = '';
        $data['description'] = (isset($row['content'])) ? htmlentities($row['content']) : '';
        $data['bedrooms'] = (isset($row['room'])) ? self:: GetTotalNumber($row['room']) : '';
        $data['bathrooms'] = (isset($row['bathroom'])) ? self:: GetTotalNumber($row['bathroom']) : '';
        $data['sleeps'] = (isset($row['room'])) ? self:: GetTotalNumber($row['room']) : '';
        $data['amenities'] = (isset($row['amenities'])) ? implode(', ', $row['amenities']) : '';
        $data['activities'] = (isset($row['activities'])) ? implode(', ', $row['activities']) : '';
        $data['image_url'] = (isset($row['photoGallery'])) ? self :: GetAllImages($row['photoGallery']) : '';
        $data['rate_period'] = (isset($row['rate_period'])) ? ($row['rate_period']) : 'daily';
        $data['low_rate'] = (isset($row['default_nightly'])) ? ($row['default_nightly']) : '0';
        $data['mid_rate'] = (isset($row['default_nightly'])) ? ($row['default_nightly']) : '0';
        $data['high_rate'] = (isset($row['default_weekly'])) ? ($row['default_weekly']) : '0';
        $data['currency'] = (isset($row['currency'])) ? ($row['currency']) : 'USD';
        $data['not_available_dates'] = (isset($row['not_available_dates'])) ? ($row['not_available_dates']) : '';
        if(isset($row['unavailability'])){
            $dates=json_decode($row['unavailability'],true);
            //die(print_r($dates));
            if(is_array($dates) && !empty($dates)){
                unset($data['not_available_dates']);
                foreach($dates as $date){
                   $date_arr['not_available_date'][]=$date;
                }
            }
            $data['not_available_dates']=$date_arr;
        }
        if(!empty($row['optional_rates'])){
            foreach($row['optional_rates'] as $k=>$val){

                $rates_data = array(
                    'start_date'=>$val->start_date,
                    'end_date'=>$val->end_date,
                    'min_stay'=>$val->min_los,
                    'stay_unit'=>'day',
                    'unit_rate'=>$val->nightly_price
                );
                $rate_range[]=$rates_data;

            }
            $data['rate_range']=$rate_range;
        }

        return $data;
    }

    /*End Export XML*/
    public function exportCsv()
    {

        $this->load->dbutil();

        $table = 'estate';
        $file_name = 'estate.csv';
        $output_file = 'assets/admin/exports/' . $file_name;

        $delimiter = ",";
        $newline = "\r\n";

        $query = $this->db->query("SELECT * FROM $table");

        $csv_content = $this->dbutil->csv_from_result($query, $delimiter, $newline);

        file_put_contents($output_file, $csv_content);

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo $csv_content;
    }

    function _log_activity($message)
    {
        $this->is_login(2);

        $this->db->set('user', $this->session->userdata('user_id'));
        $this->db->set('message', $message);
        $this->db->insert('timeline');
        return true;
    }

    function _get_page($status)
    {

        switch ($status) {
            case 'inbox':
                return $this->admin_estate_model->get_inbox($this->session->userdata('user_id'));
                break;
            case 'comments':
                return $this->admin_estate_model->get_comments($this->uri->segment(4));
                break;

            case 'sent':
                return $this->admin_estate_model->get_sent($this->session->userdata('user_id'));
                break;

            case 'message':
                return $this->admin_estate_model->msg_details($this->uri->segment(4));
                break;

            case 'view_sent':
                return $this->admin_estate_model->sent_details($this->uri->segment(4));
                break;

            default:

                break;
        }
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')
        ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function paginationCreate($baseUrl, $totalRow, $perPage)
    {
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
    }

    /**
     * @author melvin angelo e. jabonillo
     *   setting up the attributes name of the properties
     */
    function estateAttribute()
    {
        $this->session->set_userdata('page', 1);
        $this->data['attribute_name'] = $this->admin_estate_model->getAllAttribute();
        $this->tmadmin->tmView('attribute_name/index', $this->data);
    }

    /**
     * @author melvin angelo e. jabonillo
     *   adding attribute name
     */
    function estateAttributeManage()
    {
        $this->session->set_userdata('page', 1);
        $this->data['success'] = '';
        $this->data['message'] = '';
        $this->data['values'] = '';
        if ($_POST) {
            $this->form_validation->set_rules('name', $this->lang->line('sidebar_estate_attr_name'), 'required|xss_clean|is_unique[attribute_name.name]');
            $this->form_validation->set_rules('position', $this->lang->line('sidebar_estate_attr_position'), 'required|xss_clean|is_unique[attribute_name.position]|is_natural_no_zero');
            if ($this->form_validation->run() == TRUE) {
                $query = $this->admin_estate_model->addAttribute($_POST);
                $this->data['success'] = $this->lang->line('estate_attr_success');
                redirect('admin/estateAttribute');
            } else {
                $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            }
        }
        $this->tmadmin->tmView('attribute_name/add', $this->data);
    }

    /**
     * @author melvin angelo e. jabonillo
     *   adding attribute name
     */
    function estateAttributeEdit($id = null)
    {
        $this->data['values'] = $this->admin_estate_model->getAttributeInfo($id);
        $this->data['success'] = '';
        $this->data['message'] = '';
        if ($_POST) {
            $id = $_POST['id'];
            $checkAttribute = $this->admin_estate_model->checkAttribute($id, $_POST);
            if (empty($checkAttribute)) {
                $query = $this->admin_estate_model->updateAttribute($id, $_POST);
                $this->data['success'] = $this->lang->line('estate_attr_edit_success');
                redirect('admin/estateAttribute');
            } else {
                $this->data['message'] = 'Unique Name and Position';
                redirect('admin/estateAttribute');
            }
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));

        }

        $this->tmadmin->tmView('attribute_name/edit', $this->data);
    }

    /* Create Neighborhood */

    public function viewTag()
    {
//$this->is_login(3);
        // $this->admin_estate_model->paginationLimit(10, $this->uri->segment(3));
        // $this->data['pagination'] = $this->paginationCreate('/admin/viewtag/', $this->admin_estate_model->num_row_user(), 10);

        $this->data['list'] = $this->admin_estate_model->tags();
        // $this->data['ptitle'] = lang('estate_page_all_title');
        //  $this->tmadmin->tmView('neighbourhood_panel/viewtag', $this->data);


        $this->tmadmin->tmView('neighbourhood_panel/viewtag', $this->data);

    }


    function edittag($id)
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('edit')) {
            $this->form_validation->set_rules('tag', 'Tag', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'tag' => $this->input->post('tag'),
                    'city' => $this->input->post('city'),
                    'place' => $this->input->post('place'),
                    'is_shown' => $this->input->post('is_shown'),
                );
                $this->admin_estate_model->updatetag($data, $id);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood Tag updated Successfully');
                // $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Neighborhood Tag Added Successfully'));
                redirect('admin/viewtag');


            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'edittag';
        $data['list'] = $this->admin_estate_model->onetag($id);
        $this->tmadmin->tmView('neighbourhood_panel/edittag', $data);
        //$this->tmadmin->tmView('neighbourhood_panel/viewtag', $this->data);
    }

    function deletetag($id)
    {
        $delete = $this->admin_estate_model->deletetag($id);
        if ($delete) {
            redirect('admin/viewtag');
        }
    }

    function addtag()
    {

        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('tag', 'Tag', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'tag' => $this->input->post('tag'),
                    'city' => $this->input->post('city'),
                    'place' => $this->input->post('place'),
                    'is_shown' => $this->input->post('is_shown'),
                );
                $this->admin_estate_model->addtag($data);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood Tag Added Successfully');
                //$this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Neighborhood Tag Added Successfully'));
                redirect('admin/viewtag');

            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'addtag';

        $data['content'] = $this->tmadmin->tmView('neighbourhood_panel/addtag', $data, true);
        //$this->load->view('admin/viewtag',$data);
    }


    //-----------------photographers-------------------------------	
    function addphotographer()
    {

        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'required|trim|xss_clean');
            $this->form_validation->set_rules('photo_grapher_name', 'Photographer Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('photo_grapher_web', 'Website Url', 'required|trim|xss_clean');
            $this->form_validation->set_rules('photo_grapher_desc', 'Photographer Description', 'required|trim|xss_clean');
            $this->form_validation->set_rules('is_featured', 'is_featured', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'city' => $this->input->post('city'),
                    'place' => $this->input->post('place'),
                    'photographer_name' => $this->input->post('photo_grapher_name'),
                    'photographer_desc' => $this->input->post('photo_grapher_desc'),
                    'website_url' => $this->input->post('photo_grapher_web'),
                    'image' => $_FILES['photo_grapher_image']['name'],
                    'status' => 'Active',
                );
                $this->admin_estate_model->addphotographer($data);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood photographer Added Successfully');
                // $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Neighborhood photographer Added Successfully'));
                redirect('admin/viewphotographer');

            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'addphotographer';
        $data['content'] = $this->tmadmin->tmView('neighbourhood_panel/addphotographer', $data, true);
        //$this->load->view('admin/dashbord',$data);
    }


    function editphotographer($id)
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('edit')) {
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'required|trim|xss_clean');
            $this->form_validation->set_rules('photo_grapher_name', 'Photographer Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('photo_grapher_web', 'Website Url', 'required|trim|xss_clean');
            $this->form_validation->set_rules('photo_grapher_desc', 'Photographer Description', 'required|trim|xss_clean');
            $this->form_validation->set_rules('is_featured', 'is_featured', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'city' => $this->input->post('city'),
                    'place' => $this->input->post('place'),
                    'photographer_name' => $this->input->post('photo_grapher_name'),
                    'photographer_desc' => $this->input->post('photo_grapher_desc'),
                    'website_url' => $this->input->post('photo_grapher_web'),
                    'status' => 'Active',
                );
                $this->admin_estate_model->updatephotographer($data, $id);

                //Notification message
                //  $this->session->set_flashdata('flash_message', $this->common_model->admin_flash_message('success','Neighborhood photographer Added Successfully'));
                $this->session->set_flashdata('message', 'Neighborhood photographer Added Successfully');
                redirect('admin/viewphotographer');

            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'editphotographer';
        $data['list'] = $this->admin_estate_model->onephotographer($id);
        $data['content'] = $this->tmadmin->tmView('neighbourhood_panel/editphotographer', $data, true);
        //$this->load->view('admin/dashbord',$data);
    }

    function deletephotographer($id)
    {
        $delete = $this->admin_estate_model->deletephotographer($id);
        if ($delete) {
            redirect('admin/viewphotographer');
        }
    }

    function viewphotographer()
    {
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'viewphotographer';
        $data['list'] = $this->admin_estate_model->viewphotographer();
        //$data['content']=$this->load->view('admin/neighbor/viewphotographer',$data,true);
        $this->tmadmin->tmView('neighbourhood_panel/viewphotographer', $data);
    }

    //-----------------posts-------------------------------	
    function addpost()
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'required|trim|xss_clean');
            $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'title' => $this->input->post('title'),
                    'city' => $this->input->post('city'),
                    'place' => $this->input->post('place'),
                    'description' => $this->input->post('description'),
                    'is_featured' => $this->input->post('is_featured'),
                );
                $this->admin_estate_model->addpost($data);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood post Added Successfully');
                redirect('admin/viewpost');


            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'addpost';
        $this->tmadmin->tmView('neighbourhood_panel/addpost', $data, true);
    }

    function viewpost()
    {
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'viewpost';
        $data['list'] = $this->admin_estate_model->viewpost();
        $this->tmadmin->tmView('neighbourhood_panel/viewpost', $data);

    }

    function editpost($id)
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('edit')) {
            $this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'required|trim|xss_clean');
            $this->form_validation->set_rules('title', 'post Name', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'title' => $this->input->post('title'),
                    'city' => $this->input->post('city'),
                    'place' => $this->input->post('place'),
                    'description' => $this->input->post('description'),
                    'is_featured' => $this->input->post('is_featured'),
                );
                $this->admin_estate_model->updatepost($data, $id);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood post Updated Successfully');
                redirect('admin/viewpost');

            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'editpost';

        $data['list'] = $this->admin_estate_model->onepost($id);

    }

    function deletepost($id)
    {
        $delete = $this->admin_estate_model->deletepost($id);
        if ($delete) {
            redirect('admin/viewpost');
        }
    }

    //-----------------------------category--------------------------


    function viewcategory()
    {
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'viewcategory';
        $data['list'] = $this->admin_estate_model->viewcategory();
        $this->tmadmin->tmView('neighbourhood_panel/viewcategory', $data);

    }

    function addcategory()
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('category', 'category', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'category_name' => $this->input->post('category'),
                );
                $this->admin_estate_model->addcategory($data);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood category Updated Successfully');
                redirect('admin/viewcategory');

            }
        }

        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'addcategory';
        $data['content'] = $this->tmadmin->tmView('neighbourhood_panel/addcategory', $data);
    }

    function editcategory($id)
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('edit')) {
            $this->form_validation->set_rules('category', 'category', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'category_name' => $this->input->post('category'),
                    'created' => date('Y-m-d H:i:s')
                );
                $this->admin_estate_model->updatecategory($data, $id);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood category Updated Successfully');
                redirect('admin/viewcategory');

            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'editcategory';
        $data['list'] = $this->admin_estate_model->onecategory($id);
        $data['content'] = $this->tmadmin->tmView('neighbourhood_panel/editcategory', $data);

    }

    function deletecategory($id)
    {
        $delete = $this->admin_estate_model->deletecategory($id);
        if ($delete) {
            redirect('admin/viewcategory');
        }
    }

    function viewcity()
    {
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'viewcity';
        $data['list'] = $this->admin_estate_model->viewcity();
        $data['content'] = $this->tmadmin->tmView('neighbourhood_panel/viewcity', $data, true);

    }

    function addcity()
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city_desc', 'Description', 'required|trim|xss_clean');
            $this->form_validation->set_rules('known', 'Known', 'required|trim|xss_clean');
            $this->form_validation->set_rules('around', 'Around', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'city' => $this->input->post('city'),
                    'city_desc' => $this->input->post('city_desc'),
                    'known' => $this->input->post('known'),
                    'around' => $this->input->post('around'),
                    'is_home' => $this->input->post('is_home'),
                );
                $this->admin_estate_model->addcity($data);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood city Added Successfully');
                redirect('admin/viewcity');

            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'addcity';

        $this->tmadmin->tmView('neighbourhood_panel/addcity', $data, true);

    }

    function editcity($id)
    {
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
        if ($this->input->post('edit')) {
            $this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city_desc', 'Description', 'required|trim|xss_clean');
            $this->form_validation->set_rules('known', 'Known', 'required|trim|xss_clean');
            $this->form_validation->set_rules('around', 'Around', 'required|trim|xss_clean');
            if ($this->form_validation->run()) {

                $data = array(
                    'city' => $this->input->post('city'),
                    'city_desc' => $this->input->post('city_desc'),
                    'known' => $this->input->post('known'),
                    'around' => $this->input->post('around'),
                    'is_home' => $this->input->post('is_home'),
                );
                $this->admin_estate_model->updatecity($data, $id);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood city Updated Successfully');
                redirect('admin/viewcity');

            }
        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'editcity';
        $data['list'] = $this->admin_estate_model->onecity($id);
        $this->tmadmin->tmView('neighbourhood_panel/editcity', $data, true);

    }

    function deletecity($id)
    {
        $delete = $this->admin_estate_model->deletecity($id);
        if ($delete) {
            redirect('admin/viewcity');
        }
    }

    public function viewplace()
    {
        //Get Groups
        $this->load->model('admin_estate_model');
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'viewplace';
        $data['place'] = $this->admin_estate_model->getplace();

        $data['area'] = $this->admin_estate_model->getplace1();

        //Load View		
        $data['content'] = $this->tmadmin->tmView('neighbourhood_panel/viewplace', $data, true);


    }

    public function editplace()
    {
        //Get id of the category	
        $id = is_numeric($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

        if ($this->input->post('edit')) {
            //Set rules
            $this->form_validation->set_rules('area', 'area', 'required|trim|xss_clean');


            if ($this->form_validation->run()) {
                //prepare update data
                $updateData = array();
                $updateData['area'] = $this->input->post('area');
                // $area		       = $this->input->post('area');
                $updateKey = array('id' => $this->uri->segment(3));

                $this->load->model('admin_estate_model');
                $this->admin_estate_model->updatePage($updateKey, $updateData);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood place Updated Successfully');
                redirect('admin/viewplace');


            }
        } //If - Form Submission End

        //Set Condition To Fetch The Faq Category
        $condition = array('neighbor_area.id' => $id);

        //Get Groups
        $this->load->model('admin_estate_model');
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighborhoods';
        $data['page'] = 'editplace';
        $data['places'] = $this->admin_estate_model->getplace1($condition);

        $this->tmadmin->tmView('neighbourhood_panel/editplace', $data, true);


    }

    function addplace()
    {
        //Intialize values for library and helpers	
        $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

        if ($this->input->post('save')) {

            $this->form_validation->set_rules('country', 'country', 'required|trim|xss_clean');
            $this->form_validation->set_rules('state', 'state', 'required|trim|xss_clean');
            $this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
            $this->form_validation->set_rules('area', 'area', 'required|trim|xss_clean');

            if ($this->form_validation->run()) {


                $country = $this->input->post('country');
                $state = $this->input->post('state');
                $city = trim($this->input->post('city'));
                $area = trim($this->input->post('area'));

                $this->load->model('admin_estate_model');

                $trains = $this->db->query("SELECT * FROM `neighbor_city` WHERE `city` = '" . $city . "'");

                if ($trains->num_rows() == 0) {

                    $this->admin_estate_model->addplace($country, $state, $city);
                }

                $train = $this->db->query("SELECT * FROM `neighbor_city` WHERE `city` = '" . $city . "'");
                $results = $train->result_array();
                foreach ($results as $arrival) {
                    $city_id = $arrival['id'];

                }

                $this->admin_estate_model->addplace1($city_id, $area);

                //Notification message
                $this->session->set_flashdata('message', 'Neighborhood place Added Successfully');
                redirect('admin/viewplace');

            }

        }
        $data['msg'] = $this->session->flashdata('msg');
        $data['dir'] = $this->dir;
        $data['page_title'] = 'Neighbourhoods';
        $data['page'] = 'addplace';
        //Load View			
        $this->tmadmin->tmView('neighbourhood_panel/addplace', $data, true);
    }

    public function deleteplace()
    {
        $id = $this->uri->segment(3, 0);

        if ($id == 0) {

            $this->load->model('admin_estate_model');
            $getplace = $this->admin_estate_model->getplace();
            $pagelist = $this->input->post('pagelist');
            if (!empty($pagelist)) {
                foreach ($pagelist as $res) {
                    $condition = array('id' => $res);
                    $this->admin_estate_model->deleteplace(NULL, $condition);
                }
            } else {
                $this->session->set_flashdata('message', 'Please select any neighborhood place');
                redirect('neighbourhoods/viewplace');
            }
        } else {
            $condition = array('id' => $id);
            $this->load->model('admin_estate_model');
            $this->admin_estate_model->deleteplace(NULL, $condition);
        }
        //Notification message
        $this->session->set_flashdata('message', 'Neighborhood place deleted Successfully');
        redirect('admin/viewplace');
    }

    /* Property module */
    public function estateLandlord()
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateLanlord/', $this->admin_estate_model->num_row_category(), 20);

        $this->data['estatelanlord'] = $this->admin_estate_model->lanlordArray();
        $this->data['ptitle'] = lang('estate_page_lanlord_title');
        $this->tmadmin->tmView('estate_panel/lanlord', $this->data);
    }

    public function estateLandlordAdd($id = NULL)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('estate_page_lanlordupdate_title');
        } else {
            $this->data['ptitle'] = lang('estate_page_lanlordadd_title');
        }

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line('estate_lanlord_add_name'), 'required|xss_clean');
        $this->form_validation->set_rules('phone_number', $this->lang->line('estate_lanlord_add_phone_number'), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('estate_lanlord_add_email'), 'required|xss_clean');
        $this->form_validation->set_rules('fax', $this->lang->line('estate_lanlord_add_fax'), 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            if ($id != NULL) {
                $query = $this->admin_estate_model->lanlordUpdate($this->input->post('name'), $this->input->post('phone_number'), $this->input->post('email'), $this->input->post('fax'), $id);
            } else {
                $query = $this->admin_estate_model->lanlordRegister($this->input->post('name'), $this->input->post('phone_number'), $this->input->post('email'), $this->input->post('fax'));
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->lanlordArray($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['name'] : $this->form_validation->set_value('name'),
            );

            $this->data['phone_number'] = array(
                'name' => 'phone_number',
                'id' => 'phone_number',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['phone_number'] : $this->form_validation->set_value('phone_number'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['email'] : $this->form_validation->set_value('email'),
            );
            $this->data['fax'] = array(
                'name' => 'fax',
                'id' => 'fax',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['fax'] : $this->form_validation->set_value('fax'),
            );


            $this->tmadmin->tmView('estate_panel/lanlord_add', $this->data);
        }
    }

    public function estateDeleteLandlord($id)
    {
        $this->session->set_userdata('page', 24);
        $this->is_login(3);
        $this->admin_estate_model->deleteLandlord($id);
        $this->session->set_flashdata('message', lang('estate_message_lanlord_delete'));
        redirect_back();
    }

    public function estateApartment()
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateApartment/', $this->admin_estate_model->num_row_category(), 20);
        $this->data['estatelanlord'] = $this->admin_estate_model->lanlordArray();
        $this->data['estateapartment'] = $this->admin_estate_model->apartmentArray();
        $this->data['ptitle'] = lang('estate_page_apartment_title');
        $this->tmadmin->tmView('estate_panel/apartment', $this->data);
    }

    public function estateApartmentAdd($id = NULL)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('estate_page_apartmentupdate_title');
        } else {
            $this->data['ptitle'] = lang('estate_page_apartmentadd_title');
        }
        $this->data['estatelanlord'] = $this->admin_estate_model->lanlordArray();

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line('estate_apartment_add_name'), 'required|xss_clean');
        $this->form_validation->set_rules('address', $this->lang->line('estate_apartment_add_address'), 'required|xss_clean');
        $this->form_validation->set_rules('state', $this->lang->line('estate_apartment_add_state'), 'required|xss_clean');
        $this->form_validation->set_rules('city', $this->lang->line('estate_apartment_add_city'), 'required|xss_clean');
        $this->form_validation->set_rules('zipcode', $this->lang->line('estate_apartment_add_zipcode'), 'required|xss_clean');
        $this->form_validation->set_rules('landlord', $this->lang->line('estate_apartment_add_landlord'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            if ($id != NULL) {
                $query = $this->admin_estate_model->apartmentUpdate(
                    $this->input->post('name'),
                    $this->input->post('address'),
                    $this->input->post('state'),
                    $this->input->post('city'),
                    $this->input->post('zipcode'),
                    $this->input->post('landlord'),
                    $this->input->post('rent'),
                    $id
                );
            } else {
                $query = $this->admin_estate_model->apartmentRegister(
                    $this->input->post('name'),
                    $this->input->post('address'),
                    $this->input->post('state'),
                    $this->input->post('city'),
                    $this->input->post('zipcode'),
                    $this->input->post('landlord'),
                    $this->input->post('rent')
                );
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->apartmentArray($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['name'] : $this->form_validation->set_value('name'),
            );

            $this->data['address'] = array(
                'name' => 'address',
                'id' => 'address',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['address'] : $this->form_validation->set_value('address'),
            );
            $this->data['state'] = array(
                'name' => 'state',
                'id' => 'state',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['state'] : $this->form_validation->set_value('state'),
            );
            $this->data['city'] = array(
                'name' => 'city',
                'id' => 'city',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['city'] : $this->form_validation->set_value('city'),
            );
            $this->data['zipcode'] = array(
                'name' => 'zipcode',
                'id' => 'zipcode',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['zip_code'] : $this->form_validation->set_value('zip_code'),
            );
            $this->data['rent'] = array(
                'name' => 'rent',
                'id' => 'rent',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['total_rent'] : $this->form_validation->set_value('total_rent'),
            );
            $this->data['landlord'] = array(
                'name' => 'landlord',
                'id' => 'landlord',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['landlord_id'] : $this->form_validation->set_value('landlord_id'),
            );

            $this->tmadmin->tmView('estate_panel/apartment_add', $this->data);
        }
    }

    public function estateDeleteApartment($id)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        $this->admin_estate_model->deleteApartment($id);
        $this->session->set_flashdata('message', lang('estate_message_apartment_delete'));
        redirect_back();
    }

    public function estateResident()
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estateResident/', $this->admin_estate_model->num_row_category(), 20);
        $this->data['estateresident'] = $this->admin_estate_model->residentArray();
        $this->data['ptitle'] = lang('estate_page_resident_title');
        $this->tmadmin->tmView('estate_panel/resident', $this->data);
    }

    public function estateDeleteResident($id)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        $this->admin_estate_model->deleteResident($id);
        $this->session->set_flashdata('message', lang('estate_message_resident_delete'));
        redirect_back();
    }


    public function estateResidentAdd($id = NULL)
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('estate_page_resident_update_title');
        } else {
            $this->data['ptitle'] = lang('estate_page_residentt_add_title');
        }
        $this->data['estateapartment'] = $this->admin_estate_model->apartmentArray();

        //validate form input
        $this->form_validation->set_rules('name', $this->lang->line('estate_resident_add_name'), 'required|xss_clean');
        $this->form_validation->set_rules('phonenumber', $this->lang->line('estate_resident_add_phonenumber'), 'required|xss_clean');
        $this->form_validation->set_rules('email', $this->lang->line('estate_resident_add_email'), 'required|xss_clean');
        $this->form_validation->set_rules('rent', $this->lang->line('estate_resident_add_rent'), 'xss_clean');
        $this->form_validation->set_rules('apartment', $this->lang->line('estate_resident_add_apartment'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {

            $moveInTime = $this->input->post('moveintime') . " " . $this->input->post('movein_hour') . ":" . $this->input->post('movein_min') . ":00";
            $moveOutTime = $this->input->post('moveouttime') . " " . $this->input->post('moveout_hour') . ":" . $this->input->post('moveout_min') . ":00";

            if ($id != NULL) {
                $query = $this->admin_estate_model->residentUpdate(
                    $this->input->post('name'),
                    $this->input->post('phonenumber'),
                    $this->input->post('email'),
                    $moveInTime,
                    $moveOutTime,
                    $this->input->post('apartment'),
                    $this->input->post('rent'),
                    $id
                );
            } else {
                $query = $this->admin_estate_model->residentRegister(
                    $this->input->post('name'),
                    $this->input->post('phonenumber'),
                    $this->input->post('email'),
                    $moveInTime,
                    $moveOutTime,
                    $this->input->post('apartment'),
                    $this->input->post('rent')
                );
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->residentArray($id);
            }

            $this->data['name'] = array(
                'name' => 'name',
                'id' => 'name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['name'] : $this->form_validation->set_value('name'),
            );

            $this->data['phonenumber'] = array(
                'name' => 'phonenumber',
                'id' => 'phonenumber',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['phone_number'] : $this->form_validation->set_value('phone_number'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['email'] : $this->form_validation->set_value('email'),
            );

            $extInTime = explode(" ", $name[0]['move_in_time']);
            $extInHours = explode(":", $extInTime[1]);
            $this->data['moveintime'] = array(
                'name' => 'moveintime',
                'id' => 'moveintime',
                'type' => 'text',
                'value' => ($id != NULL) ? $extInTime[0] : $this->form_validation->set_value('move_in_time'),
            );

            $this->data['movein_hour'] = array(
                'name' => 'movein_hour',
                'id' => 'movein_hour',
                'type' => 'text',
                'value' => ($id != NULL) ? $extInHours[0] : $this->form_validation->set_value('movein_hour'),
            );

            $this->data['movein_min'] = array(
                'name' => 'movein_min',
                'id' => 'movein_min',
                'type' => 'text',
                'value' => ($id != NULL) ? $extInHours[1] : $this->form_validation->set_value('movein_min'),
            );

            $extOutTime = explode(" ", $name[0]['move_out_time']);
            $extOutHours = explode(":", $extOutTime[1]);

            $this->data['moveouttime'] = array(
                'name' => 'moveouttime',
                'id' => 'moveouttime',
                'type' => 'text',
                'value' => ($id != NULL) ? $extOutTime[0] : $this->form_validation->set_value('move_out_time'),
            );

            $this->data['moveout_hour'] = array(
                'name' => 'moveout_hour',
                'id' => 'moveout_hour',
                'type' => 'text',
                'value' => ($id != NULL) ? $extOutHours[0] : $this->form_validation->set_value('moveout_hour'),
            );

            $this->data['moveout_min'] = array(
                'name' => 'moveout_min',
                'id' => 'moveout_min',
                'type' => 'text',
                'value' => ($id != NULL) ? $extOutHours[1] : $this->form_validation->set_value('moveout_min'),
            );


            $this->data['rent'] = array(
                'name' => 'rent',
                'id' => 'rent',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['rent'] : $this->form_validation->set_value('rent'),
            );
            $this->data['apartment'] = array(
                'name' => 'apartment',
                'id' => 'apartment',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['apartment_id'] : $this->form_validation->set_value('apartment_id'),
            );

            $this->tmadmin->tmView('estate_panel/resident_add', $this->data);
        }
    }


    public function estatePastResident()
    {
        $this->is_login(3);
        $this->session->set_userdata('page', 24);
        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/estatePastResident/', $this->admin_estate_model->num_row_category(), 20);
        $this->data['estateresident'] = $this->admin_estate_model->pastResidentArray();
        $this->data['ptitle'] = lang('estate_page_resident_pasttitle');
        $this->tmadmin->tmView('estate_panel/resident_past', $this->data);
    }


    /* Human resource module funcitons below */


    public function hrmStaffList()
    {
        $this->session->set_userdata('page', 25);
        $this->is_login(3);

        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/hrmStaffList/', $this->admin_hrm_model->num_row_category(), 20);
        $this->data['hrmuser'] = $this->admin_hrm_model->hrmStaffListArray();
        $this->data['ptitle'] = lang('hrm_page_staff_userlist');
        $this->tmadmin->tmView('hrm_panel/userlist', $this->data);
    }

    public function hrmJobList()
    {
        $this->session->set_userdata('page', 25);
        $this->is_login(3);

        $this->admin_estate_model->paginationLimit(20, $this->uri->segment(3));
        $this->data['pagination'] = $this->paginationCreate('/admin/hrmJobList/', $this->admin_hrm_model->num_row_category(), 20);
        $this->data['hrmjob'] = $this->admin_hrm_model->hrmJobListArray();
        $this->data['ptitle'] = lang('hrm_page_job_joblist');
        $this->tmadmin->tmView('hrm_panel/joblist', $this->data);
    }


    public function hrmNewStaff()
    {
        $this->session->set_userdata('page', 25);
        $this->is_login(3);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('hrm_page_staff_update_title');
        } else {
            $this->data['ptitle'] = lang('hrm_page_staff_add_title');
        }
        $this->data['grouplist'] = $this->admin_hrm_model->groupListArray();
        $this->data['userlist'] = $this->admin_hrm_model->userListArray();

        //validate form input
        $this->form_validation->set_rules('username', $this->lang->line('hrm_username_staff'), 'required|xss_clean');
        //$this->form_validation->set_rules('password', $this->lang->line('estate_resident_add_phonenumber'), 'required|xss_clean');
        $this->form_validation->set_rules('group_id', $this->lang->line('estate_resident_add_email'), 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {


            $username = $this->input->post('username');
            $password = md5($this->input->post('password'));
            $managerid = $this->input->post('managerid');
            $groupid = $this->input->post('group_id');
            $firstname = $this->input->post('first_name');
            $lastname = $this->input->post('last_name');
            $email = $this->input->post('email');
            $phonenumber = $this->input->post('phonenumber');
            $active = $this->input->post('active');
            $notify = $this->input->post('notify');

            /* Job details  */
            $jobname = $this->input->post('jobname');
            $hsalary = $this->input->post('hsalary');
            $weeklyhours = $this->input->post('whrs');
            $startdate = $this->input->post('sdate');
            $enddate = $this->input->post('edate');
            $photo = $_FILES['userphoto']['name'];

            /*
            Array
            (
                [userphoto] => Array
                    (
                        [name] => beautiful-wallpaper-collection-4.jpg
                        [type] => image/jpeg
                        [tmp_name] => C:\wamp\tmp\php21A5.tmp
                        [error] => 0
                        [size] => 313229
                    )

            )
        */

            if ($id != NULL) {
                $query = $this->admin_estate_model->residentUpdate(
                    $this->input->post('name'),
                    $this->input->post('phonenumber'),
                    $this->input->post('email'),
                    $moveInTime,
                    $moveOutTime,
                    $this->input->post('apartment'),
                    $this->input->post('rent'),
                    $id
                );
            } else {
                $query = $this->admin_hrm_model->staffRegister(
                    $username,
                    $password,
                    $groupid,
                    $managerid,
                    $firstname,
                    $lastname,
                    $email,
                    $phonenumber,
                    $active,
                    $photo,
                    $jobname,
                    $hsalary,
                    $weeklyhours,
                    $startdate,
                    $enddate
                );
                // INSERT JOB       

                // if ($queryUser == 1) {
                //              $userid = $this->db->insert_id();
                //


                /*$query = $this->admin_hrm_model->jobRegister( $userid,
                                                                $jobname,
                                                                $hsalary,
                                                                $weeklyhours,
                                                                $startdate,
                                                                $enddate
                                                            );                                                  
                                                                    
           // } 
           */
            }
            if ($query) {
                $this->session->set_flashdata('message', $this->admin_estate_model->errors());
                $this->session->set_flashdata('success', $this->admin_estate_model->messages());
                redirect_back();
            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');

            if ($id != NULL) {
                $name = $this->admin_estate_model->residentArray($id);
            }

            $this->data['username'] = array(
                'name' => 'username',
                'id' => 'username',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['username'] : $this->form_validation->set_value('name'),
            );


            $this->data['phonenumber'] = array(
                'name' => 'phonenumber',
                'id' => 'phonenumber',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['phone_number'] : $this->form_validation->set_value('phone_number'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['email'] : $this->form_validation->set_value('email'),
            );

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['first_name'] : $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]['last_name'] : $this->form_validation->set_value('last_name'),
            );


            $this->tmadmin->tmView('hrm_panel/staff_add', $this->data);
        }
    }


    public function hrmNewJob($id = NULL)
    {
        $this->session->set_userdata('page', 25);
        $this->is_login(3);
        if ($id != NULL) {
            $this->data['ptitle'] = lang('hrm_page_staff_update_title');
        } else {
            $this->data['ptitle'] = lang('hrm_page_staff_add_title');
        }

        //$this->data['grouplist'] = $this->admin_hrm_model->groupListArray();
        $this->data['userlist'] = $this->admin_hrm_model->userListArray();
        //validate form input
        $this->form_validation->set_rules('user_id', $this->lang->line('hrm_username_staff'), 'required|xss_clean');
        $this->form_validation->set_rules('hsalary', $this->lang->line('estate_hrm_job_hourly_salary'), 'required|xss_clean');
        $this->form_validation->set_rules('whrs', $this->lang->line('estate_hrm_job_expected_hours'), 'required|xss_clean');
        $this->form_validation->set_rules('sdate', $this->lang->line('estate_hrm_job_start_date'), 'required|xss_clean');
        $this->form_validation->set_rules('edate', $this->lang->line('estate_hrm_job_end_date'), 'required|xss_clean');
        //} 

        if ($this->form_validation->run() == TRUE) {

            /* Job details  */
            $username = $this->input->post('user_id');
            $jobname = $this->input->post('jobname');
            $hsalary = $this->input->post('hsalary');
            $weeklyhours = $this->input->post('whrs');
            $startdate = $this->input->post('sdate');
            $enddate = $this->input->post('edate');
            //  $id = $this->input->post('jobid');
            if ($id != NULL) {
                $query = $this->admin_hrm_model->jobUpdate(
                    $username,
                    $jobname,
                    $hsalary,
                    $weeklyhours,
                    $startdate,
                    $enddate,
                    $id
                );
            } else {
                $query = $this->admin_hrm_model->jobRegister(
                    $username,
                    $jobname,
                    $hsalary,
                    $weeklyhours,
                    $startdate,
                    $enddate
                );

            }

            if ($query) {
                redirect('admin/hrmJobList');
                $this->session->set_flashdata('message', $this->admin_hrm_model->errors());
                $this->session->set_flashdata('success', $this->admin_hrm_model->messages());

            }
        } else {
            //display the create group form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->admin_estate_model->errors() ? $this->admin_estate_model->errors() : $this->session->flashdata('message')));
            $this->data['success'] = $this->session->flashdata('success');
            if ($id != NULL) {
                $name = $this->admin_hrm_model->jobArray($id);

            }

            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]->user_id : $this->form_validation->set_value('user_id'),
            );


            $this->data['jobname'] = array(
                'name' => 'jobname',
                'id' => 'jobname',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]->name : $this->form_validation->set_value('name'),
            );
            $this->data['hsalary'] = array(
                'name' => 'hsalary',
                'id' => 'hsalary',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]->hourly_salary : $this->form_validation->set_value('hourly_salary'),
            );

            $this->data['whrs'] = array(
                'name' => 'whrs',
                'id' => 'whrs',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]->expected_hours : $this->form_validation->set_value('expected_hours'),
            );
            $this->data['sdate'] = array(
                'name' => 'sdate',
                'id' => 'sdate',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]->start_date : $this->form_validation->set_value('start_date'),
            );

            $this->data['edate'] = array(
                'name' => 'edate',
                'id' => 'edate',
                'type' => 'text',
                'value' => ($id != NULL) ? $name[0]->end_date : $this->form_validation->set_value('end_date'),
            );

            $this->tmadmin->tmView('hrm_panel/job_add', $this->data);
        }
    }

    public static function DownloadFile($name,$type){

        $file_name=$name.'.xml';
        //$file_path='assets/admin/exports/'.$file_name;
        $file_path=dirname(BASEPATH)."/assets/admin/exports/".$file_name;

        if(!file_exists($file_path)){
            die('sorry no file');
        }
        header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($file_path)) . ' GMT');
        header('Accept-Ranges: bytes');  // Allow support for download resume
        header('Content-Length: ' . filesize($file_path));  // File size
        header('Content-Encoding: none');
        header('Content-Type: application/'.trim($type,'.'));
        header('Cache-Control: max-age=0');
        header('Content-Disposition: attachment; filename=' . $file_name);
        readfile($file_path);

    }
}
