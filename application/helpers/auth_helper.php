<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * RealEstate Auth Helpers
 *  
 * @package     Real Estate CMS
 * @author      Nurul Amin Muhit
 * @copyright   Software Developer Pro
 * 
 * @created     2014-03-08
 * @updated     2014-03-08
 */
if (!function_exists('is_login')) {

    /**
     * The Auth helper check user permissions
     *
     * @param string $level The user access level.
     * @return void
     */
    function is_login($level = 2) {
        $ci = & get_instance();
        
        if (!$ci->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('login', 'refresh');
        } elseif (!$ci->ion_auth->is_admin($level)) {
            $ci->load->helper('admin');
            redirect(site_url(), 'refresh');
        }
    }

}