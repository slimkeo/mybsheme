<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
        /* cache control */
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 2607 Jul 2023 05:00:00 GMT");
    }

    //Default function, redirects to logged in user area
    public function index() {

        if ($this->session->userdata('user_login') == 1)
            redirect(base_url() . 'index.php?burial/dashboard', 'refresh');

        $this->load->view('backend/login');
    }


    //Validating login from ajax request
    function validate_login($email = '', $password = '') {
        $credential = array('email' => $email, 'password' => $password);


        // Checking login credential for admin 
        $query = $this->db->get_where('user', $credential);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $this->session->set_userdata('user_login', '1');
            $this->session->set_userdata('level',$row->level );
            $this->session->set_userdata('user_id', $row->id);
            $this->session->set_userdata('name', $row->name);
            // user_type 1 admin 2 clerk and 3 accounts
            $this->session->set_userdata('user_type',$row->level);
            $this->session->set_userdata('login_type', 'burial');
            return 'success';
        }

        
              // Checking login credential for parent `id`, `national_id`, `fullname`, `lastname`, `contact`, `dob`, `email`, `password`, `gender`, `address`, `town`, `organization`, `org_contact`, `salary`, `status`, `createdate`, `timestamp`, `file`

         $query = $this->db->get_where('client', $credential);
         if ($query->num_rows() > 0) {
             $row = $query->row();
             $this->session->set_userdata('client_login', '1');
             $this->session->set_userdata('client_id', $row->id);
             $this->session->set_userdata('login_user_id', $row->id);
             $this->session->set_userdata('name', $row->fullname);
             $this->session->set_userdata('login_type', 'client');
             return 'success';
         }

        return 'invalid';
    }

    /*     * *DEFAULT NOR FOUND PAGE**** */

    function four_zero_four() {
        $this->load->view('four_zero_four');
    }

    // PASSWORD RESET BY EMAIL
    function forgot_password()
    {
        $this->load->view('backend/forgot_password');
    }
    // PASSWORD RESET BY EMAIL
    function register()
    {
        $this->load->view('backend/register');
    }

    // SUBMIT APPLICATION
    function submit_application()
    {
        // Load models
        $this->load->model('Pending_member_model');
        $this->load->model('Application_document_model');
        
        // Configure upload
        $config['upload_path'] = './uploads/application_documents/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size'] = 5120; // 5MB
        $config['encrypt_name'] = TRUE;
        
        // Create upload directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }
        
        $this->load->library('upload', $config);
        
        // Prepare pending member data
        $member_data = array(
            'idnumber' => $this->input->post('idnumber'),
            'passbook_no' => $this->input->post('passbook_no') ? $this->input->post('passbook_no') : NULL,
            'employeeno' => $this->input->post('employeeno'),
            'tscno' => $this->input->post('tscno'),
            'surname' => $this->input->post('surname'),
            'name' => $this->input->post('name'),
            'cellnumber' => $this->input->post('cellnumber'),
            'dob' => $this->input->post('dob'),
            'gender' => $this->input->post('gender'),
            'schoolcode' => $this->input->post('schoolcode'),
            'resident' => $this->input->post('resident'),
            'application_status' => 'pending',
            'applied_at' => date('Y-m-d H:i:s')
        );
        
        // Check for duplicates
        if ($this->Pending_member_model->check_duplicate($member_data['idnumber'], $member_data['passbook_no'])) {
            $this->session->set_flashdata('flash_message_error', 'An application with this ID number or passbook number already exists.');
            redirect(base_url() . 'index.php?login/register', 'refresh');
            return;
        }
        
        // Insert pending member
        $pending_member_id = $this->Pending_member_model->create($member_data);
        
        if (!$pending_member_id) {
            $this->session->set_flashdata('flash_message_error', 'Failed to submit application. Please try again.');
            redirect(base_url() . 'index.php?login/register', 'refresh');
            return;
        }
        
        // Handle document uploads
        $document_types = $this->input->post('document_type');
        $uploaded_documents = array();
        $upload_errors = array();
        
        if (!empty($_FILES['documents']['name'][0])) {
            $files = $_FILES['documents'];
            $file_count = count($files['name']);
            
            for ($i = 0; $i < $file_count; $i++) {
                if (!empty($files['name'][$i]) && !empty($document_types[$i])) {
                    $_FILES['document']['name'] = $files['name'][$i];
                    $_FILES['document']['type'] = $files['type'][$i];
                    $_FILES['document']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['document']['error'] = $files['error'][$i];
                    $_FILES['document']['size'] = $files['size'][$i];
                    
                    if ($this->upload->do_upload('document')) {
                        $upload_data = $this->upload->data();
                        
                        $document_data = array(
                            'pending_member_id' => $pending_member_id,
                            'document_type' => $document_types[$i],
                            'file_name' => $upload_data['orig_name'],
                            'file_path' => 'uploads/application_documents/' . $upload_data['file_name'],
                            'mime_type' => $upload_data['file_type'],
                            'file_size' => $upload_data['file_size'],
                            'uploaded_at' => date('Y-m-d H:i:s')
                        );
                        
                        $this->Application_document_model->create($document_data);
                        $uploaded_documents[] = $upload_data['orig_name'];
                    } else {
                        $upload_errors[] = $files['name'][$i] . ': ' . $this->upload->display_errors('', '');
                    }
                }
            }
        }
        
        // If no documents were uploaded, delete the pending member record
        if (empty($uploaded_documents)) {
            $this->db->where('id', $pending_member_id);
            $this->db->delete('pending_members');
            $this->session->set_flashdata('flash_message_error', 'Please upload at least one document.');
            redirect(base_url() . 'index.php?login/register', 'refresh');
            return;
        }
        
        // Success message
        $message = 'Your application has been submitted successfully! ';
        if (!empty($upload_errors)) {
            $message .= 'However, some documents could not be uploaded: ' . implode(', ', $upload_errors);
        } else {
            $message .= 'Application ID: ' . $pending_member_id . '. Our team will review your application and contact you within 48 hours.';
        }
        
        $this->session->set_flashdata('flash_message', $message);
        redirect(base_url() . 'index.php?login/register', 'refresh');
    }
    function ajax_forgot_password()
    {
        $resp                   = array();
        $resp['status']         = 'false';
        $email                  = $_POST["email"];
        $reset_account_type     = '';
        //resetting user password here
        $new_password           =   substr( md5( rand(100000000,20000000000) ) , 0,7);

        // Checking credential for admin
        $query = $this->db->get_where('admin' , array('email' => $email));
        if ($query->num_rows() > 0) 
        {
            $reset_account_type     =   'admin';
            $this->db->where('email' , $email);
            $this->db->update('admin' , array('password' => sha1($new_password)));
            $resp['status']         = 'true';
        }

        // send new password to user email
        if ($reset_account_type !== '' ){
         $this->email_model->password_reset_email($new_password , $reset_account_type , $email);
        }
        

        $resp['submitted_data'] = $_POST;

        echo json_encode($resp);
    }

    // Normalize phone number: if 8 digits, prepend "268"
    private function normalize_phone_number($value) {
        if (empty($value)) {
            return $value;
        }
        
        // Remove any non-digit characters
        $digits_only = preg_replace('/\D/', '', $value);
        
        // If it's exactly 8 digits and doesn't start with 268, prepend 268
        if (strlen($digits_only) == 8 && substr($digits_only, 0, 3) !== '268') {
            return '268' . $digits_only;
        }
        
        // If it already starts with 268, return as is
        if (substr($digits_only, 0, 3) === '268') {
            return $digits_only;
        }
        
        // Otherwise, return the original value (could be passbook or ID number)
        return trim($value);
    }

    // Generate OTP for login
    function ajax_generate_otp() {
        $response = array();
        $response['status'] = 'error';
        $response['message'] = '';

        $identifier = trim($this->input->post('login_identifier'));
        
        if (empty($identifier)) {
            $response['message'] = 'Please enter your Passbook, ID, or Cell Number.';
            echo json_encode($response);
            return;
        }

        // Normalize phone number if it's 8 digits
        $normalized_identifier = $this->normalize_phone_number($identifier);

        // Find member by passbook_no, idnumber, or cellnumber
        // Check both original and normalized identifier to handle different formats in database
        $this->db->group_start();
        $this->db->where('passbook_no', $identifier);
        $this->db->or_where('passbook_no', $normalized_identifier);
        $this->db->or_where('idnumber', $identifier);
        $this->db->or_where('idnumber', $normalized_identifier);
        $this->db->or_where('cellnumber', $identifier);
        $this->db->or_where('cellnumber', $normalized_identifier);
        $this->db->group_end();
        
        $member_query = $this->db->get('members');
        
        if ($member_query->num_rows() == 0) {
            $response['message'] = 'User does not exist.';
            echo json_encode($response);
            return;
        }

        $member = $member_query->row();
        $cellnumber = $member->cellnumber;

        if (empty($cellnumber)) {
            $response['message'] = 'Cell number not found for this member.';
            echo json_encode($response);
            return;
        }

        // Generate unique 6-digit OTP
        $otp = $this->generate_login_otp();
        
        // Set expiration time (5 minutes from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        // Store OTP in login_otps table (create table if doesn't exist)
        // First, delete any existing unused OTPs for this identifier (use normalized)
        $this->db->where('identifier', $normalized_identifier);
        $this->db->where('used', 0);
        $this->db->delete('login_otps');
        
        // Insert new OTP (store normalized identifier)
        $otp_data = array(
            'identifier' => $normalized_identifier,
            'otp' => $otp,
            'member_id' => $member->id,
            'cellnumber' => $cellnumber,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s'),
            'used' => 0
        );
        
        // Send SMS with OTP
        $sms_result = $this->send_login_otp_sms($cellnumber, $otp);
        
        if ($sms_result['success']) {
            $this->db->insert('login_otps', $otp_data);
            $response['status'] = 'success';
            $response['message'] = 'If the user exists, an OTP has been sent and will expire in 5 minutes. If you do not receive an OTP, the user does not exist or the number is incorrect.';
        } else {
            $response['message'] = 'OTP generated but SMS sending failed. Please try again.';
        }
        
        echo json_encode($response);
    }

    // Login with OTP
    function ajax_otp_login() {
        $response = array();
        $response['status'] = 'error';
        $response['message'] = '';
        $response['redirect_url'] = '';

        $identifier = trim($this->input->post('login_identifier'));
        $otp = trim($this->input->post('otp'));
        
        if (empty($identifier) || empty($otp)) {
            $response['message'] = 'Please enter both identifier and OTP.';
            echo json_encode($response);
            return;
        }

        // Normalize phone number if it's 8 digits
        $normalized_identifier = $this->normalize_phone_number($identifier);

        // Verify OTP (check both original and normalized identifier)
        $this->db->group_start();
        $this->db->where('identifier', $identifier);
        $this->db->or_where('identifier', $normalized_identifier);
        $this->db->group_end();
        $this->db->where('otp', $otp);
        $this->db->where('used', 0);
        $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        
        $otp_query = $this->db->get('login_otps');
        
        if ($otp_query->num_rows() == 0) {
            $response['message'] = 'Invalid or expired OTP. Please generate a new OTP.';
            echo json_encode($response);
            return;
        }

        $otp_record = $otp_query->row();
        $member_id = $otp_record->member_id;

        // Get member details
        $member = $this->db->get_where('members', array('id' => $member_id))->row();
        
        if (!$member) {
            $response['message'] = 'Member not found.';
            echo json_encode($response);
            return;
        }

        // Mark OTP as used
        $this->db->where('id', $otp_record->id);
        $this->db->update('login_otps', array('used' => 1, 'used_at' => date('Y-m-d H:i:s')));

        // Set session for member login
        $this->session->set_userdata('member_login', '1');
        $this->session->set_userdata('member_id', $member->id);
        $this->session->set_userdata('login_user_id', $member->id);
        $this->session->set_userdata('name', trim($member->surname . ' ' . $member->name));
        $this->session->set_userdata('login_type', 'member');
        $this->session->set_userdata('passbook_no', $member->passbook_no);
        $this->session->set_userdata('idnumber', $member->idnumber);
        $this->session->set_userdata('cellnumber', $member->cellnumber);

        $response['status'] = 'success';
        $response['message'] = 'Login successful!';
        $response['redirect_url'] = base_url() . 'index.php?burial/dashboard';

        echo json_encode($response);
    }

    // Generate unique 6-digit OTP for login
    private function generate_login_otp($tries = 5) {
        for ($i = 0; $i < $tries; $i++) {
            $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $exists = $this->db->get_where('login_otps', array('otp' => $code, 'used' => 0))->num_rows();
            if ($exists == 0) {
                return $code;
            }
        }
        // Fallback: use microtime hash
        return substr(sha1(uniqid('', true)), 0, 6);
    }

    // Send login OTP via SMS
    private function send_login_otp_sms($phone, $otp) {
        $message = "SNAT Burial Scheme Login OTP: {$otp}. This code expires in 5 minutes. Do not share this code with anyone.";
        
        $encoded_message = urlencode($message);
        $api_key = "c25hdGJ1cmlhbEBzd2F6aS5uZXQtcmVhbHNtcw==";
        $url = "https://www.realsms.co.sz/urlSend?_apiKey={$api_key}&dest={$phone}&message={$encoded_message}";
        
        $response = @file_get_contents($url);
        
        if ($response !== FALSE) {
            return array('success' => true, 'message' => "SMS sent to {$phone}", 'api_response' => $response);
        } else {
            return array('success' => false, 'error' => "Failed to send SMS", 'api_response' => $response);
        }
    }

    /*     * *****LOGOUT FUNCTION ****** */

    function logout() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('logout_notification', 'logged_out');
        redirect(base_url(), 'refresh');
    }

}
