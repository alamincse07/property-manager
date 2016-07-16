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

class uploadify extends CI_Controller {

    public $view_data = array();
    private $upload_config;

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->model('admin_estate_model');
    }

    public function index() {
        $this->load->helper(array('url', 'form'));
        //$this->tmadmin->tmView('uploadify',$this->view_data);
    }

    public function do_upload() {
        $this->load->library('upload');

        $image_upload_folder = FCPATH . '/uploads';

        if (!file_exists($image_upload_folder)) {
            mkdir($image_upload_folder, DIR_WRITE_MODE, true);
        }

        $this->upload_config = array(
            'upload_path' => $image_upload_folder,
            'allowed_types' => 'png|jpg|jpeg|bmp|tiff',
            'max_size' => 5120,
            'remove_space' => TRUE,
            'encrypt_name' => TRUE,
        );

        $this->upload->initialize($this->upload_config);

        if (!$this->upload->do_upload()) {
            $upload_error = $this->upload->display_errors();
            echo json_encode($upload_error);
        } else {
            $file_info = $this->upload->data();
            //$this->imageCopy($file_info['file_name']);
            $this->imageThumb($file_info['file_name']);
            $this->imageResize($file_info['file_name']);
            echo json_encode($file_info);
        }
    }
    
    public function imageResize($filename) {

        $dbData = json_decode($this->admin_estate_model->getSetings('image_settings')->value);
        foreach ($dbData as $key => $item) {
            $oldData[$key] = $item;
        }

        $config['image_library'] = 'gd2';
        $config['source_image'] = 'uploads/' . $filename;
        $config['new_image'] = 'uploads/' . $filename;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = ($oldData['image_maintain_ratio']) ? TRUE : FALSE;
        $config['width'] = $oldData['image_width'];
        $config['height'] = $oldData['image_height'];
        $config['quality'] = $oldData['image_quality'];

        $config['wm_font_path'] = $oldData['image_wm_font_path'];
        $config['wm_text'] = $oldData['image_wm_text'];
        $config['wm_type'] = "text";
        $config['wm_font_size'] = $oldData['image_wm_font_size'];
        $config['wm_font_color'] = $oldData['image_wm_font_color'];
        $config['wm_vrt_alignment'] = $oldData['image_wm_vrt_alignment'];
        $config['wm_hor_alignment'] = $oldData['image_wm_hor_alignment'];
        $config['wm_shadow_color'] = $oldData['image_wm_shadow_color'];
        $config['wm_shadow_distance'] = $oldData['image_wm_shadow_distance'];
        $config['wm_hor_offset'] = $oldData['image_wm_hor_offset'];
        
        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        if ($oldData['image_watermarking']) $this->image_lib->watermark();
        
    }
    
        
    public function imageThumb($filename) {
        $config['image_library'] = 'gd2';
        $config['source_image'] = 'uploads/' . $filename;
        $config['new_image']   = 'uploads/thumbs/' . $filename;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 320;
        $config['height'] = 220;
        
        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
    }    

    public function delete_file($id = null) {
        $this->load->helper('file');
        $file = './uploads/' . $id;
        $filethumb = './uploads/thumbs/' . $id;
        
        if ($id != NULL and file_exists($file)) {
            unlink($file);
            unlink($filethumb);

            $return_array = array('status' => 'success');
            echo json_encode($return_array);
        }
    }

}

