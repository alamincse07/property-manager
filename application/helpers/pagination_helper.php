<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * RealEstate Pagination Helpers
 *  
 * @package     Real Estate CMS
 * @author      Nurul Amin Muhit
 * @copyright   Software Developer Pro
 * 
 * @created     2014-03-08
 * @updated     2014-03-08
 */
if (!function_exists('create_pagination')) {

    /**
     * The Pagination helper cuts out some of the bumf of normal pagination
     *
     * @param string $uri The current URI.
     * @param int $total_rows The total of the items to paginate.
     * @param int|null $limit How many to show at a time.
     * @param int $uri_segment The current page.
     * @param boolean $full_tag_wrap Option for the Pagination::create_links()
     * @return array The pagination array. 
     * @see Pagination::create_links()
     */
    function create_pagination($uri, $total_rows, $limit = null, $uri_segment = 4, $full_tag_wrap = true) {
        $ci = & get_instance();
        $ci->load->library('pagination');

        $current_page = $ci->uri->segment($uri_segment, 0);
        $suffix = $ci->config->item('url_suffix');

        $limit = $limit === null ? 10 : $limit;

        // Initialize pagination
        $ci->pagination->initialize(array(
            'suffix' => $suffix,
            'base_url' => (!$suffix) ? rtrim(site_url($uri), $suffix) : site_url($uri),
            'total_rows' => $total_rows,
            'per_page' => $limit,
            'uri_segment' => $uri_segment,
            'use_page_numbers' => true,
            'reuse_query_string' => true,
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
        ));

        $offset = $limit * ($current_page - 1);

        //avoid having a negative offset
        if ($offset < 0)
            $offset = 0;

        return array(
            'current_page' => $current_page,
            'per_page' => $limit,
            'limit' => $limit,
            'offset' => $offset,
            'links' => $ci->pagination->create_links($full_tag_wrap)
        );
    }

}