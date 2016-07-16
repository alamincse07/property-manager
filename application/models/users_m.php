<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package     Real Estate CMS Pro
 * @author      Nurul Amin Muhit
 * @email       muhit18@gmail.com
 */
class Users_m extends CI_Model {

    protected $_table = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db
                ->select('users.*')
                ->select('groups.name group_slug, groups.description group_title, groups.level group_level')
                ->join('users_groups', 'users_groups.user_id = users.id', 'left')
                ->join('groups', 'users_groups.group_id = groups.id', 'left')
                ->order_by('users.id', 'desc');

        return $this->db->get($this->_table)->result();
    }

    public function get($id) {
        $this->db
                ->select('users.*')
                ->select('groups.name group_slug, groups.description group_title, groups.level group_level')
                ->join('users_groups', 'users_groups.user_id = users.id', 'left')
                ->join('groups', 'users_groups.group_id = groups.id', 'left')
                ->where('users.id', $id);

        return $this->db->get($this->_table)->row();
    }

    public function get_by($key, $value = '') {
        $this->db
                ->select('users.*')
                ->select('groups.name group_slug, groups.description group_title, groups.level group_level')
                ->join('users_groups', 'users_groups.user_id = users.id', 'left')
                ->join('groups', 'users_groups.group_id = groups.id', 'left');

        if (is_array($key)) {
            $this->db->where($key);
        } else {
            $this->db->where($key, $value);
        }

        return $this->db->get($this->_table)->row();
    }

    public function get_many_by($params = array()) {

        if (!empty($params['group_slug'])) {
            $this->db->where('groups.name', $params['group_slug']);
        }

        return $this->get_all();
    }

    public function insert($input) {
        $this->db->insert($this->_table, $input);
        return $this->db->insert_id();
    }

    public function update($id, $input) {
        $this->db->update($this->_table, array('users.id' => $id), $input);
    }

    public function count_by($key, $value = '') {
        $this->db
                ->join('users_groups', 'users_groups.user_id = users.id', 'left')
                ->join('groups', 'users_groups.group_id = groups.id', 'left');

        if (is_array($key)) {
            if (!empty($key['group_slug'])) {
                $this->db->where('groups.name', $key['group_slug']);
            }
        } else {
            $this->db->where($key, $value);
        }

        return $this->db->count_all_results($this->_table);
    }

    public function count_all() {
        return $this->db->count_all_results($this->_table);
    }

    public function check_exists($field, $value = '', $id = 0) {
        if (is_array($field)) {
            $params = $field;
            $id = $value;
        } else {
            $params[$field] = $value;
        }
        $params['users.id !='] = (int) $id;

        $this->db->where($params);

        return $this->db->count_all_results($this->_table) == 0;
    }

}

/* End of file users_m.php */
/* Location: ./application/models/users_m.php */