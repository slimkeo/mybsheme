<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Application_document_model extends CI_Model {

    public function create($data) {
        $this->db->insert('application_documents', $data);
        return $this->db->insert_id();
    }

    public function get_by_pending_member($pending_member_id) {
        return $this->db->get_where('application_documents', ['pending_member_id' => $pending_member_id])->result_array();
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('application_documents');
    }

    public function delete_by_pending_member($pending_member_id) {
        $this->db->where('pending_member_id', $pending_member_id);
        return $this->db->delete('application_documents');
    }
}
