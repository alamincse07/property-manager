<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class tmadmin {

    private $ci;

    function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->library('ion_auth');
    }

    function tmView($filename, $data = Null) {
        $user = $this->ci->ion_auth->user()->row();
        $headData['username'] = $user->first_name;
                
        if (!isset($data['ptitle']))
            $headData['ptitle'] = $this->ci->ion_auth->getSet('site_title')->value;
        else
            $headData['ptitle'] = $data['ptitle'] . " - " .  $this->ci->ion_auth->getSet('site_title')->value;
        
        if (!isset($data['description']))
            $headData['desc'] = $this->ci->ion_auth->getSet('slogan')->value;
        else
            $headData['desc'] = $data['description'];
        
        if (!isset($data['keyword']))
            $headData['keyword'] = $this->ci->ion_auth->getSet('site_keyword')->value;
        else
            $headData['keyword'] = $data['keyword'];
        
        if (!isset($data['author']))
            $headData['author'] = $this->ci->ion_auth->getSet('site_author')->value;
        else
            $headData['author'] = $data['author'];
        
        $headData['main_title'] = $this->ci->ion_auth->getSet('site_title')->value;

        $data['header'] = $this->ci->load->view('admin/header', $headData, TRUE);
        $data['footer'] = $this->ci->load->view('admin/footer', '', TRUE);
        $data['sidebar'] = $this->ci->load->view('admin/sidebar', '', TRUE);
        $data['hugemenu'] = $this->ci->load->view('admin/hugemenu', '', TRUE);

        $this->ci->load->view('admin/' . $filename, $data);
    }

}

?>