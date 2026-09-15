<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pending_member_model extends CI_Model {

    public function create($data) {
        $this->db->insert('pending_members', $data);
        return $this->db->insert_id();
    }

    public function get_by_id($id) {
        return $this->db->get_where('pending_members', ['id' => $id])->row_array();
    }

    public function get_all($status = null) {
        if ($status) {
            $this->db->where('application_status', $status);
        }
        return $this->db->order_by('applied_at', 'DESC')->get('pending_members')->result_array();
    }

    public function update_status($id, $status, $reviewed_by = null, $rejection_reason = null) {
        $data = [
            'application_status' => $status,
            'reviewed_at' => date('Y-m-d H:i:s'),
            'reviewed_by' => $reviewed_by
        ];
        
        if ($rejection_reason) {
            $data['rejection_reason'] = $rejection_reason;
        }
        
        $this->db->where('id', $id);
        return $this->db->update('pending_members', $data);
    }

    public function check_duplicate($idnumber, $passbook_no = null) {
        $this->db->where('idnumber', $idnumber);
        if ($passbook_no) {
            $this->db->or_where('passbook_no', $passbook_no);
        }
        return $this->db->get('pending_members')->num_rows() > 0;
    }
}
