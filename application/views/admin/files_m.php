<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * @package     Real Estate CMS Pro
 * @author      Nurul Amin Muhit
 * @email       muhit18@gmail.com
 */
class Files_m extends CI_Model {

    function get_files() {
        $this->db->join('users', 'users.id = files.owner');
        $query = $this->db->get('files');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function public_files() {
        $this->db->where('private', '0');
        $this->db->join('users', 'users.id = files.owner');
        $query = $this->db->get('files');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function get_file_details($file) {
        if ($this->session->userdata('level') == 1) {
            $this->db->where('private', '0');
        }
        $this->db->where('file_id', $file);
        $this->db->join('users', 'users.id = files.owner');
        $query = $this->db->get('files');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    function file_info($file) {
        $this->db->where('file_id', $file);
        $this->db->join('users', 'users.id = files.owner');
        $query = $this->db->get('files');
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        }
    }

    function memberships() {
        $query = $this->db->get('memberships');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
    }

    public function insert_file($filename, $name, $packages, $private, $notes) {
        if (!$private) {
            $private = '0';
        } else {
            $private = '1';
        }
        $data = array(
            'file' => $filename,
            'name' => $name,
            'packages' => $packages,
            'private' => $private,
            'owner' => $this->session->userdata('user_id'),
            'notes' => $notes
        );
        $this->db->insert('files', $data);
        return $this->db->insert_id();
    }

    public function delete_file($file_id) {
        $file = $this->get_file($file_id);
        if (!$this->db->where('file_id', $file_id)->delete('files')) {
            return FALSE;
        }
        unlink('./uploads/' . $file->file);
        return TRUE;
    }

    public function get_file($file_id) {
        return $this->db->select()
                        ->from('files')
                        ->where('file_id', $file_id)
                        ->get()
                        ->row();
    }

}

/* End of file files_m.php */
/* Location: ./application/models/files_m.php */