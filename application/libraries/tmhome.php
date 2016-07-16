<?php

if(!defined('BASEPATH'))
  exit('No direct script access allowed');

class tmhome {

    private $ci;

    function __construct() {
        $this->ci = & get_instance();
        $this->ci->load->model('home_model');
    }

    function tmView($filename, $data = NULL, $sidebar = TRUE) {
        (!isset($data['ptitle'])) ? $headData['ptitle'] = $this->ci->home_model->getSet('site_title')->value : $headData['ptitle'] = $data['ptitle'] . " - " . $this->ci->ion_auth->getSet('site_title')->value;
        (!isset($data['pdescription'])) ? $headData['pdescription'] = $this->ci->home_model->getSet('slogan')->value : $headData['pdescription'] = $data['pdescription'];
        (!isset($data['keyword'])) ? $headData['keyword'] = $this->ci->home_model->getSet('site_keyword')->value : $headData['keyword'] = $data['keyword'];
        (!isset($data['author'])) ? $headData['author'] = $this->ci->home_model->getSet('site_author')->value : $headData['author'] = $data['author'];

        $sosyal = $this->ci->home_model->getContact();

        //Head Data
        $headData['pdata'] = $this->ci->home_model->getPages();
        $headData['pcategory'] = $this->ci->home_model->getCategory();
        $headData['ptype'] = $this->ci->home_model->getType();
        $headData['pusername'] = $this->ci->home_model->getUser();
        $headData['psosyal'] = $sosyal;

        //Footer And Slider Data
        $footerData['fdata'] = $sosyal;
        $sliderData['fanalytics'] = $this->ci->home_model->getSet('site_analytics')->value;
        $sliderData['sdata'] = json_decode($this->ci->home_model->getSet('slider')->value);

        if ($sidebar) {
            //Sidebar Data
            $sidebarData['sContact'] = $sosyal;
            $sidebarData['sRoom'] = $this->ci->home_model->getSelect('room');
            $sidebarData['sBathroom'] = $this->ci->home_model->getSelect('bathroom');
            $sidebarData['sHeating'] = $this->ci->home_model->getSelect('heating');
            $sidebarData['sSm'] = $this->ci->home_model->getSelect('squaremeter');
            $sidebarData['sBs'] = $this->ci->home_model->getSelect('buildstatus');
            $sidebarData['sFloor'] = $this->ci->home_model->getSelect('floor');
            $sidebarData['sCat'] = $this->ci->home_model->getCatType('category', 'catName');
            $sidebarData['sType'] = $this->ci->home_model->getCatType('estatetype', 'estateName');
            $sidebarData['sPricemin'] = $this->ci->home_model->getPrice('ASC');
            $sidebarData['sPricemax'] = $this->ci->home_model->getPrice('DESC');
            $data['sidebar'] = $this->ci->load->view('front/sidebar', $sidebarData, TRUE);
        }

        $data['header'] = $this->ci->load->view('front/header', $headData, TRUE);
        $data['slider'] = $this->ci->load->view('front/slider', $sliderData, TRUE);
        $data['footer'] = $this->ci->load->view('front/footer', $footerData, TRUE);

        $this->ci->load->view('front/' . $filename, $data);
    }

}