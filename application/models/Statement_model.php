<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statement_model extends CI_Model {

    public function get_by_member($memberid) {
        return $this->db
                    ->where('memberid', $memberid)
                    ->order_by('date', 'DESC')
                    ->get('statements')
                    ->result_array();
    }

    /**
     * Get statements for a member with limit/offset (used for pagination or "last N" lists)
     */
    public function get_by_member_paginated($memberid, $limit, $offset = 0) {
        return $this->db
                    ->where('memberid', $memberid)
                    ->order_by('date', 'DESC')
                    ->limit((int)$limit, (int)$offset)
                    ->get('statements')
                    ->result_array();
    }

    /**
     * Count all statements for a member (for pagination)
     */
    public function count_by_member($memberid) {
        return $this->db
                    ->where('memberid', $memberid)
                    ->count_all_results('statements');
    }

}