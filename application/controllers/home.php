<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Name:  Real Estate CMS Pro
 *
 * Author: Ramazan APAYDIN
 *         apaydinweb@gmail.com
 *
 * Website: http://ramazanapaydin.com
 *
 * Created:  04.15.2013
 */
class home extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->helper('language');
        $this->load->library('form_validation');
        $this->load->model('home_model');
    }

    public function index() {

        $this->home_model->paginationLimit(10, 0);
        $this->data['showcase'] = $this->home_model->getEstates(NULL, array('publish' => '1', 'showcase' => '1'), TRUE);
        $this->home_model->paginationLimit(2, 0);
        $this->data['estates'] = $this->home_model->getEstates(NULL, array('publish' => '1'), TRUE);

        $this->data['sRoom'] = $this->home_model->getSelect('room');
        $this->data['sBathroom'] = $this->home_model->getSelect('bathroom');
        $this->data['sHeating'] = $this->home_model->getSelect('heating');
        $this->data['sSm'] = $this->home_model->getSelect('squaremeter');
        $this->data['sBs'] = $this->home_model->getSelect('buildstatus');
        $this->data['sFloor'] = $this->home_model->getSelect('floor');
        $this->data['sCat'] = $this->home_model->getCatType('category', 'catName');
        $this->data['sType'] = $this->home_model->getCatType('estatetype', 'estateName');
        $this->data['sPricemin'] = $this->home_model->getPrice('ASC');
        $this->data['sPricemax'] = $this->home_model->getPrice('DESC');

        $this->setCache();
        $this->tmhome->tmView('index', $this->data, FALSE);
    }

    public function setCache() {
        if ($this->session->userdata('level') >= 2) {
            $_SESSION['cached'] = 1;
        } else {
            $this->output->cache($this->home_model->getSet('cache_timeout')->value);
        }
    }

    public function cat($id = 1) {
        $catName = $this->home_model->getCategory(array('cid' => $id));
        $this->data['ptitle'] = $catName[$id];

        if (!$catName[$id]) {
            show_404();
        }

        $uricount = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $paginationCount = $this->home_model->getSet('estate_count')->value;

        $this->home_model->paginationLimit($paginationCount, $uricount);
        $this->data['pagination'] = $this->paginationCreate("/cat/$id/", $this->home_model->num_row_user(array('publish' => '1', 'catID' => $id)), $paginationCount);

        $this->data['estates'] = $this->home_model->getEstates(NULL, array('publish' => '1', 'catID' => $id), TRUE);

        $this->setCache();
        $this->tmhome->tmView('category', $this->data, TRUE);
    }

    public function type($id = 1) {
        $catName = $this->home_model->getType(array('eid' => $id));
        $this->data['ptitle'] = $catName[$id];

        if (!$catName[$id]) {
            show_404();
        }

        $uricount = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $paginationCount = $this->home_model->getSet('estate_count')->value;

        $this->home_model->paginationLimit($paginationCount, $uricount);
        $this->data['pagination'] = $this->paginationCreate("/type/$id/", $this->home_model->num_row_user(array('publish' => '1', 'estateTypeID' => $id)), $paginationCount);

        $this->data['estates'] = $this->home_model->getEstates(NULL, array('publish' => '1', 'estateTypeID' => $id), TRUE);

        $this->setCache();
        $this->tmhome->tmView('estatetype', $this->data, TRUE);
    }

    public function search($pg = NULL) {
        $this->data['ptitle'] = lang('search');

        $uricount = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        if (isset($_GET['searchstr']) && empty($_GET['searchstr'])) {
            $_GET['searchstr'] = " ";
        }

        if (!$this->input->get('searchstr')) {
            $query = $this->session->flashdata('query');
            if (is_array($query)) {
                foreach ($query as $key => $item) {
                    if ($key == "pcount")
                        continue;
                    $_GET[$key] = $item;
                }
            }
        }

        $data = array(
            'searchstr' => $this->input->get('searchstr'),
            'room' => (!$this->input->get('room')) ? NULL : $this->input->get('room'),
            'bathroom' => (!$this->input->get('bathroom')) ? NULL : $this->input->get('bathroom'),
            'heating' => (!$this->input->get('heating')) ? NULL : $this->input->get('heating'),
            'squaremeter' => (!$this->input->get('squaremeter')) ? NULL : $this->input->get('squaremeter'),
            'buildstatus' => (!$this->input->get('buildstatus')) ? NULL : $_GET['buildstatus'],
            'floor' => (!$this->input->get('floor')) ? NULL : $this->input->get('floor'),
            'category' => $this->input->get('category'),
            'type' => $this->input->get('type'),
            'pricemin' => $this->input->get('pricemin'),
            'pricemax' => $this->input->get('pricemax'),
        );
        $this->session->set_flashdata('query', $data);

        $paginationCount = $this->home_model->getSet('estate_count')->value;
        $this->home_model->paginationLimit($paginationCount, $uricount);

        $getData = $this->home_model->getSearch($data);
        $this->data['estates'] = $getData['query'];
        $this->data['searchstr'] = $data['searchstr'];

        $this->data['pagination'] = $this->paginationCreate("/search/", $getData['count'][0]['FOUND_ROWS()'], $paginationCount, 2);
        $this->tmhome->tmView('search', $this->data, TRUE);
    }

    public function blog() {
        $this->data['ptitle'] = lang('blog_page_title');

        $uricount = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $paginationCount = $this->home_model->getSet('blog_count')->value;

        $this->home_model->paginationLimit($paginationCount, $uricount);
        $this->data['pagination'] = $this->paginationCreate("/blog/", $this->home_model->num_row_blogpage(array('publish' => '1', 'type' => "0")), $paginationCount, 3);

        $this->data['blogs'] = $this->home_model->getBlogpage(NULL, array('publish' => '1', 'type' => "0"), TRUE);
        $this->data['contact'] = $this->home_model->getContact();

        $this->setCache();
        $this->tmhome->tmView('blog', $this->data, FALSE);
    }

    public function single($id = NULL) {
        if ($id == NULL) {
            show_404();
        }

        $this->data['estate'] = $this->home_model->getEstates($id);
        if (!$this->data['estate']) {
            show_404();
        }

        $this->data['estate'] = $this->data['estate'][0];
        $this->data['ptitle'] = $this->data['estate']->title;
        $this->data['getuser'] = $this->home_model->getMyestate($this->data['estate']->addedUserID);
        $this->data['getuser'] = $this->data['getuser'][0];
        $this->data['properties'] = $this->home_model->getProperties($id);

        $this->setCache();
        $this->tmhome->tmView('single', $this->data, TRUE);
    }

    public function pages($id = NULL) {
        if ($id == NULL) {
            show_404();
        }

        $this->data['page'] = $this->home_model->getBlogpage($id);
        if (!$this->data['page']) {
            show_404();
        }
        $this->data['page'] = $this->data['page'][0];
        $this->data['ptitle'] = $this->data['page']->title;
        $this->data['pdescription'] = $this->data['page']->desc;
        $this->data['contact'] = $this->home_model->getContact();

        $this->setCache();
        $this->tmhome->tmView('pages', $this->data, FALSE);
    }

    public function singleb($id = NULL) {
        if ($id == NULL) {
            show_404();
        }

        $this->data['blog'] = $this->home_model->getBlogpage($id);
        if (!$this->data['blog']) {
            show_404();
        }
        $this->data['blog'] = $this->data['blog'][0];
        $this->data['ptitle'] = $this->data['blog']->title;
        $this->data['pdescription'] = $this->data['blog']->desc;
        $this->data['contact'] = $this->home_model->getContact();

        $this->setCache();
        $this->tmhome->tmView('single_blog', $this->data, FALSE);
    }

    public function contact() {

        $this->form_validation->set_rules('firstname', $this->lang->line('contact_first_name'), 'xss_clean|required');
        $this->form_validation->set_rules('lastname', $this->lang->line('contact_last_name'), 'xss_clean|required');
        $this->form_validation->set_rules('email', $this->lang->line('contact_email_name'), 'xss_clean|valid_email|required');
        $this->form_validation->set_rules('subject', $this->lang->line('contact_subject_name'), 'xss_clean');
        $this->form_validation->set_rules('message', $this->lang->line('contact_message_name'), 'xss_clean');

        if ($this->form_validation->run() == true) {
            $data = array(
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'email' => $this->input->post('email'),
                'subject' => $this->input->post('subject'),
                'message' => $this->input->post('message'),
            );

            $maildata = $this->home_model->mailsetting();
            $email_config = array(
                'protocol' => $maildata['mail_type'],
                'smtp_host' => $maildata['smtp_host'],
                'smtp_user' => $maildata['smtp_username'],
                'smtp_pass' => $maildata['smtp_password'],
                'smtp_crypto' => $maildata['smtp_ssl'],
                'smtp_port' => $maildata['smtp_port'],
                'charset' => $maildata['mail_charset'],
                'mailtype' => $maildata['mail_encoding'],
                '_smtp_auth' => ($maildata['smtp_auth'] == 1) ? TRUE : FALSE,
            );
            $this->email->initialize($email_config);
            $message = "
            <table>
                <tr>
                    <td>Name:</td>
                    <td>" . $data['firstname'] . "</td>
                </tr>
                <tr>
                    <td>Surname:</td>
                    <td>" . $data['lastname'] . "</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>" . $data['email'] . "</td>
                </tr>
                <tr>
                    <td>Subject:</td>
                    <td>" . $data['subject'] . "</td>
                </tr>
                <tr>
                    <td>Message:</td>
                    <td>" . $data['message'] . "</td>
                </tr>
            </table>    
            ";
            $this->email->clear();
            $this->email->from($maildata['from_email'], $maildata['from_name']);
            $this->email->to($this->home_model->getSet('site_eposta')->value);
            $this->email->subject($data['subject']);
            $this->email->message($message);
            if (@$this->email->send()) {
                redirect_back();
            } else {
                redirect_back();
            }
        }
    }

    function paginationCreate($baseUrl, $totalRow, $perPage, $urisegment = 3) {
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
            'prev_link' => '<i class="icon-double-angle-left"></i> ' . lang('previous'),
            'prev_tag_open' => '<li class="prev">',
            'prev_tag_close' => '</li>',
            'next_link' => lang('pnext') . ' <i class="icon-double-angle-right"></i>',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a href="#">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'uri_segment' => $urisegment
        );

        $this->pagination->initialize($pageConfig);
        return $this->pagination->create_links();
    }

}

?>