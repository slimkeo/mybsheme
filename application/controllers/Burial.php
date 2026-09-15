<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Burial extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('phpqrcode/qrlib');
        $this->load->model('Member_model');
        $this->load->model('Beneficiary_model');
        $this->load->model('Statement_model');
        // load config for SMS (you'll create this config or set constants)
        $this->load->config('sms_config', true); // optional, see notes        
        /* Cache control */
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    /** DEFAULT FUNCTION **/
    public function index()
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        redirect(base_url() . 'index.php?burial/dashboard', 'refresh');
    }

    /** DASHBOARD **/
    function dashboard()
    {
        if ($this->session->userdata('member_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = "Member Dashboard";
        $this->load->view('backend/index', $page_data);
    }
/********** MANAGE beneficiaries ********************/
function beneficiaries($param1 = '', $param2 = '', $param3 = '')
{
    if ($this->session->userdata('user_login') == 1)
        redirect('login', 'refresh');

    // CREATE MEMBER
    if ($param1 == 'add') {

        $data['idnumber']    = $this->input->post('idnumber');
        $data['fullname']    = $this->input->post('fullname');
        $data['passbook_no'] = $this->input->post('dob');
        $data['status']     = 0;
        $data['memberid'] = $this->session->userdata('member_login');
        $data['user'] = 1;
        $file    = $this->input->post('file');
        $data['createdate']     = date('Y-m-d');

        // Prevent duplicate ID number or passbook number
        $this->db->group_start()
                 ->where('idnumber', $data['idnumber'])
                 ->or_where('memberid', $data['id'])
                 ->group_end();

        $exists = $this->db->get('beneficiaries')->num_rows();

        if ($exists > 0) {
            $this->session->set_flashdata('flash_message_error', 'Beneficairy already added');
        } else {
            $this->db->insert('beneficiaries', $data);
            $this->session->set_flashdata('flash_message', 'Beneficairy added successfully');
        }

        redirect(base_url() . 'index.php?burial/beneficiaries', 'refresh');
    }

    // UPDATE MEMBER
    if ($param1 == 'do_update') {

        $data['idnumber']    = $this->input->post('idnumber');
        $data['fullname']    = $this->input->post('fullname');
        $data['dob'] = $this->input->post('dob');

        $this->db->where('id', $param2);
        $this->db->update('beneficiaries', $data);

        $this->session->set_flashdata('flash_message', 'Beneficiary updated successfully');
        redirect(base_url() . 'index.php?burial/members', 'refresh');
    }


    $page_data['beneficiaries'] = $this->db->get_where('beneficiaries',array('memberid'=>$param1))->result_array();
    $page_data['page_name'] = 'beneficiaries';
    $page_data['page_title'] = 'Manage Beneficiaries';

    $this->load->view('backend/index', $page_data);
}

    /********** MEMBER DETAILS ********************/
    function member_details($memberid = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['memberid']    = $memberid;
        $page_data['page_name']  = 'member_details';
        $page_data['page_title'] = get_phrase('member_details');
        $this->load->view('backend/index', $page_data);
    }


    /********** STATEMENTS DETAILS ********************/
    function statement()
    {
        if ($this->session->userdata('user_login') == 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'statement';
        $page_data['page_title'] = 'Statements';
        $this->load->view('backend/index', $page_data);
    }
    /********** STATEMENTS DETAILS ********************/
    function payments()
    {
        if ($this->session->userdata('user_login') == 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'payments';
        $page_data['page_title'] = 'Payments';
        $this->load->view('backend/index', $page_data);
    }
        /********** POLICY DETAILS ********************/
        function policy()
        {
            if ($this->session->userdata('user_login') == 1)
                redirect(base_url(), 'refresh');
    
            $page_data['page_name']  = 'policy';
            $page_data['page_title'] = 'Policy';
            $this->load->view('backend/index', $page_data);
        }
public function get_members()
{
    $draw   = intval($this->input->post("draw"));
    $start  = intval($this->input->post("start"));
    $length = intval($this->input->post("length"));
    $search = $this->input->post("search")['value'];

    // --------------------------------------------
    // 1️⃣ Total records (no search)
    // --------------------------------------------
    $recordsTotal = $this->db->count_all("members");

    // --------------------------------------------
    // 2️⃣ Build filtered query
    // --------------------------------------------
    $this->db->from("members");

    if (!empty($search)) {
        $this->db->group_start();
        $this->db->like("idnumber", $search);
        $this->db->or_like("surname", $search);
        $this->db->or_like("name", $search);
        $this->db->or_like("cellnumber", $search);
        $this->db->or_like("passbook_no", $search);
        $this->db->group_end();
    }

    // --------------------------------------------
    // 3️⃣ Count filtered records
    // --------------------------------------------
    $recordsFiltered = $this->db->count_all_results('', false);

    // --------------------------------------------
    // 4️⃣ Pagination
    // --------------------------------------------
    $this->db->limit($length, $start);

    // --------------------------------------------
    // 5️⃣ Fetch results
    // --------------------------------------------
    $query = $this->db->get();

    $data = [];
    foreach($query->result() as $r){
        $data[] = [
            $r->code,
            $r->idnumber,
            $r->surname,
            $r->name,
            $r->passbook_no,
            $r->cellnumber,
            $r->gender,
            $r->schoolcode,
            '
            <a href="'.base_url().'index.php?burial/member_details/'.$r->code.'" class="btn btn-xs btn-info" target="_blank">
                <i class="fa fa-eye"></i>
            </a>

            <a href="#" class="btn btn-xs btn-success" onClick="showAjaxModal(\''.base_url().'index.php?modal/popup/modal_edit_member/'.$r->code.'\');">
                <i class="fa fa-pencil"></i>
            </a>

            <a href="#" class="btn btn-xs btn-danger" onClick="confirm_modal(\''.base_url().'index.php?burial/member/delete/'.$r->code.'\');">
                <i class="fa fa-trash"></i>
            </a>'
        ];
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    ]);
}

    ///initaite sms sending
    public function invite_batch_init()
    {
        // You can restrict members (e.g., active only). For now all members.
        $total = $this->Member_model->count_all_members();

        echo json_encode(['total' => (int)$total]);
    }
    public function invite_batch()
    {
        // prevent PHP timeout for this single request (but keep small batch)
        set_time_limit(60);

        $offset = intval($this->input->post('offset'));
        $limit = intval($this->input->post('limit'));

        if ($limit <= 0) $limit = 100;

        // fetch batch of members
        $members = $this->Member_model->get_members_batch($offset, $limit);

        $logs = [];
        $success_count = 0;

        foreach ($members as $m) {
            // generate (unique-ish) OTP code — 6 digits to avoid collisions
            $otp = $this->generate_unique_otp();

            // prepare attendance record
            $att = [
                'passbook_no' => $m['passbook_no'],
                'national_id' => $m['idnumber'],
                'agm' => 1,
                'fullname' => trim($m['surname'] . ' ' . $m['name']),
                'momo' =>  $m['cellnumber'],
                'otp' => $otp,
                'createdate' => date('Y-m-d H:i:s'),
                'attended_at' => null
            ];

            // insert into attendance table (ignore duplicates if member already invited)
            $insert_id = $this->Attendance_model->insert_if_not_exists($att);

            if ($insert_id) {
                // send SMS
                $sms_ok = $this->send_sms_otp($m['cellnumber'], $otp, $att);

                if ($sms_ok) {
                    $logs[] = "SMS sent to {$m['cellnumber']} (code: {$otp})";
                    $success_count++;
                } else {
                    $logs[] = "SMS FAILED for {$m['cellnumber']} (code: {$otp})";
                    // you may update attendance row with failed flag if desired
                }
            } else {
                $logs[] = "Member / Number already exists, skipping....";
            }
        }

        // compute processed count for client progress
        $processed = count($members);

        // total_success - useful at finish
        $total_success = $this->Attendance_model->count_sent(); // implement below

        echo json_encode([
            'processed' => $processed,
            'success_count' => $success_count,
            'logs' => $logs,
            'total_success' => $total_success
        ]);
    }

    /**
     * Generate unique 6-digit OTP.
     * Tries a few times to avoid DB collisions. Good enough for 15k.
     */
    private function generate_unique_otp($tries = 5)
    {
        for ($i = 0; $i < $tries; $i++) {
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            if (!$this->Attendance_model->otp_exists($code)) {
                return $code;
            }
        }
        // fallback: force unique by using microtime hashed (guaranteed unique-ish)
        return substr(sha1(uniqid('', true)), 0, 6);
    }

    /**
     * Generic SMS sender. Replace with your provider details.
     * Returns boolean.
     */

    public function send_sms_otp($phone,$otp, $attendance_row = null) {

        // 2️⃣ Prepare message
        $message = "SNAT Burial AGM: 13 Dec 2025, 07:00 AM, Metropolitan Evangelical Church. OTP:$otp Members: Passbook, ID & payslip. Pensioners: ID, Passbook & proof.";


        // 3️⃣ URL encode message
        $encoded_message = urlencode($message);

        // 4️⃣ API key
        $api_key = "c25hdGJ1cmlhbEBzd2F6aS5uZXQtcmVhbHNtcw=="; // Replace with your real API key

        // 5️⃣ Construct API URL
        //$phone="26876404197";
        $url = "https://www.realsms.co.sz/urlSend?_apiKey={$api_key}&dest={$phone}&message={$encoded_message}";

        // 6️⃣ Send SMS using file_get_contents
        $response = file_get_contents($url);

        if ($response !== FALSE) {
            // Optional: you can parse response if RealSMS returns JSON/text
            return ['success' => true, 'message' => "SMS sent to {$phone}", 'api_response' => $response];
        } else {
            return ['success' => false, 'error' => "Failed to send SMS", 'api_response' => $response];
        }
    }
    public function send_broadcast()
    {
        // prevent PHP timeout for this single request (but keep small batch)
        set_time_limit(60);

        $offset = intval($this->input->post('offset'));
        $limit = intval($this->input->post('limit'));
        $message = $this->input->post('message');
        $message = urlencode($message);

        if ($limit <= 0) $limit = 100;

        // fetch batch of members
        $members = $this->Member_model->get_members_batch($offset, $limit);

        $logs = [];
        $success_count = 0;

        foreach ($members as $m) {

                // send SMS
                $sms_ok = $this->broadcast_message($m['cellnumber'], $message);

                if ($sms_ok) {
                    $logs[] = "SMS sent to {$m['cellnumber']} (message: {$message})";
                    $success_count++;
                } else {
                    $logs[] = "SMS FAILED for {$m['cellnumber']} (message: {$message})";
                    // you may update attendance row with failed flag if desired
                }
        }

        // compute processed count for client progress
        $processed = count($members);

        echo json_encode([
            'processed' => $processed,
            'success_count' => $success_count,
            'logs' => $logs
        ]);
    }

    public function broadcast_message($phone,$message) {

        // 2️⃣ Prepare message
        /*$message = "SNAT Burial AGM TEST (internal staff and board members only). Date: 05 Dec 2025, 10:00 AM. Venue: Metropolitan Evangelical Church. Your code: $otp. Present this at registration.";*/


        // 4️⃣ API key
        $api_key = "c25hdGJ1cmlhbEBzd2F6aS5uZXQtcmVhbHNtcw=="; // Replace with your real API key

        // 5️⃣ Construct API URL
        //$phone="26876404197";
        $url = "https://www.realsms.co.sz/urlSend?_apiKey={$api_key}&dest={$phone}&message={$message}";

        // 6️⃣ Send SMS using file_get_contents
        $response = file_get_contents($url);

        if ($response !== FALSE) {
            // Optional: you can parse response if RealSMS returns JSON/text
            return ['success' => true, 'message' => "SMS sent to {$phone}", 'api_response' => $response];
        } else {
            return ['success' => false, 'error' => "Failed to send SMS", 'api_response' => $response];
        }
    }



    /********** MANAGE ATTENDANCE (Members Present at AGM) ********************/
    function attendance($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $data = [
                "national_id" => $this->input->post('national_id'),
                "fullname"    => $this->input->post('fullname'),
                "passbook_no" => $this->input->post('passbook_no'),
                "momo"        => $this->input->post('momo'),
                "agm"         => $this->input->post('agm'),
                "otp"         => $this->generate_unique_otp(),
                "createdate" => date('Y-m-d H:i:s'),
                "attended_at" => date('Y-m-d H:i:s'),
            ];            

            // Prevent duplicate momo number
            $res = $this->Attendance_model->add_manual_attendee($data);
            if ($res['success']) {
                $this->session->set_flashdata('flash_message', get_phrase('Member added successfully'));
            } else {
                
                $this->session->set_flashdata('flash_message_error', get_phrase('Member exists, not added'));
            }
            redirect(base_url() . 'index.php?burial/attendance', 'refresh');
        }

        if ($param1 == 'do_update') {
            $data['fullname']     = $this->input->post('fullname');
            $data['national_id']  = $this->input->post('national_id');
            $data['contact']      = $this->input->post('contact');
            $data['passbook']     = $this->input->post('passbook');
            $data['momo']         = $this->input->post('momo');
            $data['agm']          = $this->input->post('agm');

            $this->db->where('id', $param2);
            $this->db->update('attendance', $data);
            $this->session->set_flashdata('flash_message', get_phrase('Member updated successfully'));
            redirect(base_url() . 'index.php?burial/attendance', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('id', $param2);
            $this->db->delete('attendance');
            $this->session->set_flashdata('flash_message', get_phrase('Data deleted successfully'));
            redirect(base_url() . 'index.php?burial/attendance', 'refresh');
        }

        $page_data['attendees']   = $this->db->get('attendance')->result_array();
        $page_data['agms']   = $this->db->get('agms')->result_array();
        $page_data['page_name']   = 'attendance';
        $page_data['page_title']  = get_phrase('manage_attendance');
        $this->load->view('backend/index', $page_data);
    }
    public function get_attendance()
    {
        $draw   = intval($this->input->post("draw"));
        $start  = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $search = $this->input->post("search")['value'];

        // --------------------------------------------
        // 1️⃣ Total records (no search)
        // --------------------------------------------
        $recordsTotal = $this->db->count_all("attendance");

        // --------------------------------------------
        // 2️⃣ Build filtered query
        // --------------------------------------------
        $this->db->from("attendance");

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->or_like("passbook_no", $search);
            $this->db->or_like("national_id", $search);
            $this->db->or_like("otp", $search);
            $this->db->or_like("fullname", $search);
            $this->db->or_like("momo", $search);
            $this->db->group_end();
        }

        // --------------------------------------------
        // 3️⃣ Count filtered records
        // --------------------------------------------
        $recordsFiltered = $this->db->count_all_results('', false);

        // --------------------------------------------
        // 4️⃣ Pagination
        // --------------------------------------------
        $this->db->limit($length, $start);

        // --------------------------------------------
        // 5️⃣ Fetch results
        // --------------------------------------------
        $query = $this->db->get();
        $count=1;
        $data = [];
        foreach($query->result() as $r){
            $data[] = [
                $count++,
                $r->passbook_no,
                $r->national_id,
                $r->agm,
                $r->fullname,
                $r->momo,
                $r->otp,
                ($r->status == 1) ? "Attended" : "Not Attended",
                '
                <button 
                    class="btn btn-xs btn-warning resend-otp" 
                    data-url="' . base_url() . 'index.php?burial/send_sms_otp/' . $r->momo . '/' . $r->otp . '">
                    <i class="fa fa-refresh"></i> Resend OTP
                </button>
                '
            ];
        }

        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
    }
    //DISPLAY ATTENDED MEMBERS ON DATATABLE
     public function get_attended()
    {
        $draw   = intval($this->input->post("draw"));
        $start  = intval($this->input->post("start"));
        $length = intval($this->input->post("length"));
        $search = $this->input->post("search")['value'];

        // --------------------------------------------
        // 1️⃣ Total records (no search)
        // --------------------------------------------
        $this->db->where("status", 1);
        $recordsTotal = $this->db->count_all("attendance");

        // --------------------------------------------
        // 2️⃣ Build filtered query
        // --------------------------------------------
        $this->db->from("attendance");
         $this->db->where("status", 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->or_like("passbook_no", $search);
            $this->db->or_like("national_id", $search);
            $this->db->or_like("otp", $search);
            $this->db->or_like("fullname", $search);
            $this->db->or_like("momo", $search);
            $this->db->group_end();
        }

        // --------------------------------------------
        // 3️⃣ Count filtered records
        // --------------------------------------------
        $recordsFiltered = $this->db->count_all_results('', false);

        // --------------------------------------------
        // 4️⃣ Pagination
        // --------------------------------------------
        $this->db->limit($length, $start);

        // --------------------------------------------
        // 5️⃣ Fetch results
        // --------------------------------------------
        $query = $this->db->get();
        $count=1;
        $data = [];
        foreach($query->result() as $r){
            $data[] = [
                $count++,
                "MSISDN",
                $r->momo,
                $this->db->get_where('settings' , array('type'=>'momo_amount'))->row()->description,
                "Lunch" 
            ];
        }

        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ]);
    }   
    /********** MANAGE AGMs (Annual General Meetings) ********************/
    function agms($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $data['description'] = $this->input->post('description');
            $data['date']        =date('Y-m-d', strtotime($this->input->post('date')));
            $data['year']        = $this->input->post('year');
            $data['createdate']  = date("Y-m-d");
            $data['user']     = $this->session->userdata('user_id');

            $this->db->insert('agms', $data);
            $this->session->set_flashdata('flash_message', get_phrase('AGM added successfully'));
            redirect(base_url() . 'index.php?burial/agms', 'refresh');
        }

        if ($param1 == 'do_update') {
            $data['description'] = $this->input->post('description');
            $data['date']        = $this->input->post('date');
            $data['year']        = $this->input->post('year');

            $this->db->where('id', $param2);
            $this->db->update('agms', $data);
            $this->session->set_flashdata('flash_message', get_phrase('AGM updated successfully'));
            redirect(base_url() . 'index.php?burial/agms', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('id', $param2);
            $this->db->delete('agms');
            $this->session->set_flashdata('flash_message', get_phrase('AGM deleted successfully'));
            redirect(base_url() . 'index.php?burial/agms', 'refresh');
        }

        $page_data['agms']       = $this->db->get('agms')->result_array();
        $page_data['page_name']  = 'agms';
        $page_data['page_title'] = get_phrase('manage_agms');
        $this->load->view('backend/index', $page_data);
    }


    /********** report per agm ********************/
    function report_per_agm($agmid="")
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        $agmid = ($agmid==null) ? $this->input->post('agm') : $agmid ;

        $page_data['attendees']    = $this->db->get_where('attendance', array('agm' => $agmid))->result_array();
        $page_data['page_name']  = 'agm_details';
        $page_data['page_title'] = $this->db->get_where('agms', array('id' => $agmid))->row()->description.' Details';
        $this->load->view('backend/index', $page_data);
    }    

    /********** choose meeting for details ********************/
    function detailed_meetings()
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'detailed_meetings';
        $page_data['page_title'] = get_phrase('detailed_meetings');
        $this->load->view('backend/index', $page_data);
    }  
    /********** MANAGE USERS (System Users / Admins) ********************/
    function manage_users($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect('login', 'refresh');

        if ($param1 == 'create') {
            $data['name']         = $this->input->post('fullname');
            $data['email']        = $this->input->post('email');
            $data['national_id']  = $this->input->post('national_id');
            $data['level']        = $this->input->post('level');
            $data['phone']        = $this->input->post('phone');
            $data['password']     = sha1(substr($data['national_id'], -6)); // last 5 digits as default password
            $data['createdate']   = date("Y-m-d");

            //check if user exists
             $check = $this->db->get_where('user', array('national_id' => $data['national_id']))->num_rows();
            if ($check > 0) {
                $this->session->set_flashdata('flash_message_error', get_phrase('user_already_registered'));
            } else {
                $this->db->insert('user', $data);
                $this->session->set_flashdata('flash_message', get_phrase('user_already_successfully'));
                redirect(base_url() . 'index.php?burial/manage_users', 'refresh');
            }
        }

        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['email']       = $this->input->post('email');
            $data['national_id'] = $this->input->post('national_id');
            $data['level']       = $this->input->post('level');

            $this->db->where('id', $param2);
            $this->db->update('user', $data);
            $this->session->set_flashdata('flash_message', get_phrase('User updated successfully'));
            redirect(base_url() . 'index.php?burial/manage_users', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('id', $param2);
            $this->db->delete('user');
            $this->session->set_flashdata('flash_message', get_phrase('User deleted successfully'));
            redirect(base_url() . 'index.php?burial/manage_users', 'refresh');
        }

        $page_data['users']      = $this->db->get('user')->result_array();
        $page_data['page_name']  = 'manage_users';
        $page_data['page_title'] = get_phrase('manage_users');
        $this->load->view('backend/index', $page_data);
    }

    /********** USER / MEMBER DETAILS ********************/
    function user_details($user_id = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['user_id']    = $user_id;
        $page_data['page_name']  = 'user_details';
        $page_data['page_title'] = get_phrase('user_details');
        $this->load->view('backend/index', $page_data);
    }


    /********** USER / MEMBER DETAILS ********************/
    function sms_batch_invite($user_id = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'sms_batch_invite';
        $page_data['page_title'] = "SMS Batch Invite";
        $this->load->view('backend/index', $page_data);
    }
    function momo_agm()
    {
        if ($this->session->userdata('user_login') != 1)
            redirect('login', 'refresh');
        
        $page_data['page_name']  = 'momo_agm';
        $page_data['page_title'] = get_phrase('momo_agm');
        $this->load->view('backend/index', $page_data);
    }


    function pay_with_momo($agmid="")
    {
        if ($this->session->userdata('user_login') != 1)
            redirect('login', 'refresh');

        $agmid = ($agmid==null) ? $this->input->post('agm') : $agmid ;

        $page_data['page_name']  = 'pay_with_momo';
        $page_data['attendees']  = $this->db->get_where('attendance', array('agm' => $agmid))->result_array();
        $page_data['page_title'] = get_phrase('pay_with_momo');
        $this->load->view('backend/index', $page_data);
    }

    function update_with_momo()
    {
        if ($this->session->userdata('user_login') != 1)
            redirect('login', 'refresh');

        $attendeeid = $this->input->post('attendeeid');

            $data['paid']= 1;

            $this->db->where('id', $attendeeid);
            $this->db->update('attendance', $data);

         $response = array();

        $response['status'] = 'updated';

        //Replying ajax request with validation response
        echo json_encode($response);
    }    

    /********** SYSTEM SETTINGS ********************/
    function manage_system($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        if ($param1 == 'do_update') {
            $items = array('system_name', 'system_title', 'address', 'phone', 'system_email','momo_amount');
            foreach ($items as $item) {
                $data['description'] = $this->input->post($item);
                $this->db->where('type', $item);
                $this->db->update('settings', $data);
            }
            $this->session->set_flashdata('flash_message', get_phrase('data_updated'));
            redirect(base_url() . 'index.php?burial/manage_system', 'refresh');
        }

        if ($param1 == 'upload_logo') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?burial/manage_system', 'refresh');
        }

        if ($param1 == 'change_skin') {
            $skins = array('skin_colour', 'borders_style', 'header_colour', 'sidebar_colour', 'sidebar_size');
            foreach ($skins as $skin) {
                $data['description'] = $this->input->post($skin);
                $this->db->where('type', $skin);
                $this->db->update('settings', $data);
            }
            $this->session->set_flashdata('flash_message', get_phrase('theme_updated'));
            redirect(base_url() . 'index.php?burial/manage_system', 'refresh');
        }

        $page_data['page_name']  = 'manage_system';
        $page_data['page_title'] = get_phrase('manage_system');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }


    /********** MANAGE COMMUNIQUES ********************/
    function sms_communique($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        // ... (your existing language logic – unchanged except session check)
        $page_data['page_name']  = 'sms_communique';
        $page_data['page_title'] = "SMS Communique";
        $this->load->view('backend/index', $page_data);
    }
    /********** MANAGE Claims ********************/
    function claims()
    {
        if ($this->session->userdata('user_login') == 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        $page_data['page_name']  = 'claims';
        $page_data['page_title'] = "Claims";
        $this->load->view('backend/index', $page_data);
    }
    /********** LANGUAGE SETTINGS ********************/
    function manage_language($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        // ... (your existing language logic – unchanged except session check)
        $page_data['page_name']  = 'manage_language';
        $page_data['page_title'] = get_phrase('manage_language');
        $this->load->view('backend/index', $page_data);
    }

    /********** BACKUP & RESTORE ********************/
    function backup_restore($operation = '', $type = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        if ($operation == 'create') {
            $this->crud_model->create_backup($type);
        }
        if ($operation == 'restore') {
            $this->crud_model->restore_backup();
            $this->session->set_flashdata('backup_message', 'Backup Restored');
            redirect(base_url() . 'index.php?burial/backup_restore/', 'refresh');
        }
        if ($operation == 'delete') {
            $this->crud_model->truncate($type);
            $this->session->set_flashdata('backup_message', 'Data removed');
            redirect(base_url() . 'index.php?burial/backup_restore/', 'refresh');
        }

        $page_data['page_name']  = 'backup_restore';
        $page_data['page_title'] = get_phrase('manage_backup_restore');
        $this->load->view('backend/index', $page_data);
    }

    /********** MANAGE PROFILE & SECURITY ********************/

    function security_settings($param1 = '')
    {
        if ($this->session->userdata('user_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'update') {
            $old_password = sha1($this->input->post('old_password'));
            $new_password = sha1($this->input->post('new_password'));
            $user_id = $this->session->userdata('user_id');

            $stored_pass = $this->db->get_where('user', array('id' => $user_id))->row()->password;

            if ($stored_pass == $old_password) {
                $this->db->where('id', $user_id);
                $this->db->update('user', array('password' => $new_password));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message_error', get_phrase('old_password_incorrect'));
            }
            redirect(base_url() . 'index.php?burial/security_settings', 'refresh');
        }

        $page_data['page_name']  = 'security_settings';
        $page_data['page_title'] = get_phrase('change_password');
        $this->load->view('backend/index', $page_data);
    }
  
}