<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*	
 *	@author 	: Joyonto Roy
 *	date		: 27 september, 2014
 *	MapleLeaf School Management System
 *	http://codecanyon.net/user/Creativeitem
 *	support@creativeitem.com
 */

class Admin extends CI_Controller
{
    
    
	function __construct()
	{
		parent::__construct();
		$this->load->database();
        $this->load->library('session');
		
       /*cache control*/
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
        date_default_timezone_set("Asia/Dhaka");
    }
    
    /***default functin, redirects to login page if no admin logged in yet***/
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($this->session->userdata('admin_login') == 1)
            redirect(base_url() . 'index.php?admin/dashboard', 'refresh');
    }
    
    /***ADMIN DASHBOARD***/
    function dashboard()
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        $page_data['page_name']  = 'dashboard';
        $page_data['page_title'] = get_phrase('admin_dashboard');
        $this->load->view('backend/index', $page_data);
    }
    
    
    /****MANAGE STUDENTS CLASSWISE*****/
	function student_add()
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
		$page_data['page_name']  = 'student_add';
		$page_data['page_title'] = get_phrase('add_student');
		$this->load->view('backend/index', $page_data);
	}
	
	function student_bulk_add($param1 = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
			
		if ($param1 == 'import_excel')
		{
			move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');
			// Importing excel sheet for bulk student uploads

			include 'simplexlsx.class.php';
			
			$xlsx = new SimpleXLSX('uploads/student_import.xlsx');
			
			list($num_cols, $num_rows) = $xlsx->dimension();
			$f = 0;
			foreach( $xlsx->rows() as $r ) 
			{
				// Ignore the inital name row of excel file
				if ($f == 0)
				{
					$f++;
					continue;
				}
				for( $i=0; $i < $num_cols; $i++ )
				{
					if ($i == 0)	    $data['name']			=	$r[$i];
					else if ($i == 1)	$data['birthday']		=	$r[$i];
					else if ($i == 2)	$data['sex']		    =	$r[$i];
					else if ($i == 3)	$data['address']		=	$r[$i];
					else if ($i == 4)	$data['phone']			=	$r[$i];
					else if ($i == 5)	$data['email']			=	$r[$i];
					else if ($i == 6)	$data['password']		=	$r[$i];
					else if ($i == 7)	$data['roll']			=	$r[$i];
				}
				$data['class_id']	=	$this->input->post('class_id');
				
				$this->db->insert('student' , $data);
				//print_r($data);
			}
			redirect(base_url() . 'index.php?admin/student_information/' . $this->input->post('class_id'), 'refresh');
		}
		$page_data['page_name']  = 'student_bulk_add';
		$page_data['page_title'] = get_phrase('add_bulk_student');
		$this->load->view('backend/index', $page_data);
	}

    function student_promotion($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'promotion')
        {
            $from_session = $_POST['from_session'];
            $from_class_id = $_POST['from_class_id'];
            $from_section_id = $_POST['from_section_id'];
            $from_year = $_POST['from_year'];


            $to_session = $_POST['to_session'];
            $to_class_id = $_POST['to_class_id'];
            $to_section_id = $_POST['to_section_id'];
            $to_year = $_POST['to_year'];

            $building_id = $_POST['building'];
            $branch_id = $_POST['branch'];

            $stuforpromo = $_POST['stuforpromo'];
            $cnt = 0;
            $scc = 0;
            //Checking the Seat Capacity
            $seat_capacity_data = unserialize($this->crud_model->get_type_name_by_id('section',$to_section_id,'capacity'));
            $seat_capacity = $seat_capacity_data['capacity_'.$to_year];
            if($seat_capacity==''){
                $this->session->set_flashdata('flash_message_error' , 'Seat Capacity is not Set for Promoting Section');
                redirect(base_url() . 'index.php?admin/student_promotion/', 'refresh');
            }
            foreach($stuforpromo as $s){
                $cnt++;
                //$status = $this->get_payment_status($s);
                $status = 1;
                $current_students = $this->get_current_numbers_of_students($to_section_id,$to_year);
                $capacity = ($current_students<$seat_capacity) ? 1 : 0;
                if($status && $capacity){
                    $scc++;
                    $data['class_id'] =  $to_class_id;
                    $data['section_id'] =  $to_section_id;
                    $data['s_session'] =  $to_session;
                    $data['active'] =  2;
                    $data['parent_status'] =  0;
                    $data['mf_waiver'] =  0;
                    $data['ad_waiver'] =  0;
                    $data['ev_waiver'] =  0;
                    $data['year'] =  $to_year;

                    $data['monthly_fees'] =  '';
                    $data['admission_fees'] =  '';
                    $data['evaluation_fees'] =  '';
                    $data['s_vat'] =  '';
                    $data['total'] =  '';

                    $data['special_monthly_fee'] =  '';
                    $data['special_admission_fee'] =  '';
                    $data['special_evaluation_fee'] =  '';

                    if($to_session == '01'){
                        $data['payment_month_start_from'] =  1;
                    }else if($to_session == '02'){
                        $data['payment_month_start_from'] =  7;
                    }

                    $data['building_info'] =  $building_id;
                    $data['branch_info'] =  $branch_id;

                    $this->db->where('student_id', $s);
                    $this->db->update('student', $data);
                    $this->crud_model->clear_cache();
                }
            }

            if($scc){
                $this->session->set_flashdata('flash_message' , $scc.' Students Promoted');
            }
            if(($cnt - $scc) > 0){
                $this->session->set_flashdata('flash_message_error' , ($cnt - $scc).' Students Not Promoted');
            }
            redirect(base_url() . 'index.php?admin/student_promotion/', 'refresh');
        }

        if ($param1 == 'import_excel')
        {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_import.xlsx');
            // Importing excel sheet for bulk student uploads

            include 'simplexlsx.class.php';

            $xlsx = new SimpleXLSX('uploads/student_import.xlsx');

            list($num_cols, $num_rows) = $xlsx->dimension();
            $f = 0;
            foreach( $xlsx->rows() as $r )
            {
                // Ignore the inital name row of excel file
                if ($f == 0)
                {
                    $f++;
                    continue;
                }
                for( $i=0; $i < $num_cols; $i++ )
                {
                    if ($i == 0)	    $data['name']			=	$r[$i];
                    else if ($i == 1)	$data['birthday']		=	$r[$i];
                    else if ($i == 2)	$data['sex']		    =	$r[$i];
                    else if ($i == 3)	$data['address']		=	$r[$i];
                    else if ($i == 4)	$data['phone']			=	$r[$i];
                    else if ($i == 5)	$data['email']			=	$r[$i];
                    else if ($i == 6)	$data['password']		=	$r[$i];
                    else if ($i == 7)	$data['roll']			=	$r[$i];
                }
                $data['class_id']	=	$this->input->post('class_id');

                $this->db->insert('student' , $data);
                //print_r($data);
            }
            redirect(base_url() . 'index.php?admin/student_information/' . $this->input->post('class_id'), 'refresh');
        }
        $page_data['page_name']  = 'student_promotion';
        $page_data['page_title'] = get_phrase('Student Promotion');
        $this->load->view('backend/index', $page_data);
    }
	
	function student_information($class_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
			
		$page_data['page_name']  	= 'student_information';
		$page_data['page_title'] 	= get_phrase('student_information'). " - ".get_phrase('class')." : ".
											$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}
	
	function student_marksheet($class_id = '')
	{
		if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
			
		$page_data['page_name']  = 'student_marksheet';
		$page_data['page_title'] 	= get_phrase('student_marksheet'). " - ".get_phrase('class')." : ".
											$this->crud_model->get_class_name($class_id);
		$page_data['class_id'] 	= $class_id;
		$this->load->view('backend/index', $page_data);
	}

    function reactivate($param1 = ''){
        $data['deleted'] = 0;
        $this->db->where('student_id', $param1);
        $this->db->update('student',$data);
        $this->session->set_flashdata('flash_message' , get_phrase('data re-activated'));
        redirect(base_url() . 'index.php?admin/report/leaved_student', 'refresh');
    }
	
    function student($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            //Checking Seat Capacity
            $seat_capacity_data = unserialize($this->crud_model->get_type_name_by_id('section',$this->input->post('section_id'),'capacity'));
            $seat_capacity = $seat_capacity_data['capacity_'.$this->input->post('year')];
            $current_students = $this->get_current_numbers_of_students($this->input->post('section_id'),$this->input->post('year'));
            if($current_students>=$seat_capacity){
                $this->session->set_flashdata('flash_message_error' , 'Sorry!!! , Seat Capacity Overflow');
                redirect(base_url() . 'index.php?admin/student_add', 'refresh');
            }

            $data['name']       = $this->input->post('name');
            $data['birthday']   = $this->input->post('birthday');
            $data['gender']        = $this->input->post('gender');
            $data['present_address']    = $this->input->post('present_address');
            $data['parmanent_address']    = $this->input->post('parmanent_address');
            $data['phone']      = $this->input->post('phone');
            $data['sms_number']      = $this->input->post('sms_number');
            $data['email']      = $this->input->post('email');
            $data['password']   = $this->input->post('password');
            $data['class_id']   = $this->input->post('class_id');
            if ($this->input->post('section_id') != '') {
                $data['section_id'] = $this->input->post('section_id');
            }
            $data['parent_id']  = $this->input->post('parent_id');
            $data['parent_status']  = $this->input->post('parent_status');
            $data['mf_waiver']  = $this->input->post('mf_waiver');
            $data['ad_waiver']  = $this->input->post('ad_waiver');
            $data['ev_waiver']  = $this->input->post('ev_waiver');
            $data['c_lab_waiver']  = $this->input->post('c_lab_waiver');
            $data['p_lab_waiver']  = $this->input->post('p_lab_waiver');
            $data['active']  = $this->input->post('student_status');
            //Additional Parameters
            $data['institution_1']       = $this->input->post('institution_1');
            $data['class_1']       = $this->input->post('class_1');
            $data['year_1']       = $this->input->post('year_1');
            $data['position_1']       = $this->input->post('position_1');
            $data['passed_1']       = $this->input->post('passed_1');
            $data['institution_2']       = $this->input->post('institution_2');
            $data['class_2']       = $this->input->post('class_2');
            $data['year_2']       = $this->input->post('year_2');
            $data['position_2']       = $this->input->post('position_2');
            $data['passed_2']       = $this->input->post('passed_2');
            $data['institution_3']       = $this->input->post('institution_3');
            $data['class_3']       = $this->input->post('class_3');
            $data['year_3']       = $this->input->post('year_3');
            $data['position_3']       = $this->input->post('position_3');
            $data['passed_3']       = $this->input->post('passed_3');
            $data['reason_for_leaving']       = $this->input->post('reason_for_leaving');
            $data['c_brother_1']       = $this->input->post('c_brother_1');
            $data['cb_class_1']       = $this->input->post('cb_class_1');
            $data['cb_section_1']       = $this->input->post('cb_section_1');
            $data['c_brother_2']       = $this->input->post('c_brother_2');
            $data['cb_class_2']       = $this->input->post('cb_class_2');
            $data['cb_section_2']       = $this->input->post('cb_section_2');
            $data['c_brother_3']       = $this->input->post('c_brother_3');
            $data['cb_class_3']       = $this->input->post('cb_class_3');
            $data['cb_section_3']       = $this->input->post('cb_section_3');
            $data['p_brothers_1']       = $this->input->post('p_brothers_1');
            $data['pb_class_1']       = $this->input->post('pb_class_1');
            $data['pb_section_1']       = $this->input->post('pb_section_1');
            $data['p_brothers_2']       = $this->input->post('p_brothers_2');
            $data['pb_class_2']       = $this->input->post('pb_class_2');
            $data['pb_section_2']       = $this->input->post('pb_section_2');
            $data['p_brothers_3']       = $this->input->post('p_brothers_3');
            $data['pb_class_3']       = $this->input->post('pb_class_3');
            $data['pb_section_3']       = $this->input->post('pb_section_3');
            $data['monthly_fees']       = $this->input->post('monthly_fees');
            $data['admission_fees']       = $this->input->post('admission_fees');
            $data['evaluation_fees']       = $this->input->post('evaluation_fees');
            if($this->input->post('lab') == 1){
                $data['c_lab']       = 0;
                $data['p_lab']       = 0;
            }else if($this->input->post('lab') == 2){
                $data['c_lab']       = 1;
                $data['p_lab']       = 0;
            }else if($this->input->post('lab') == 3){
                $data['c_lab']       = 0;
                $data['p_lab']       = 1;
            }else if($this->input->post('lab') == 4){
                $data['c_lab']       = 1;
                $data['p_lab']       = 1;
            }
            $data['admission']       = 0;
            $data['evaluation']       = 0;
            if(sizeof($_POST['yearly_fee']) > 0){
                foreach($_POST['yearly_fee'] as $yf){
                    if($yf == "admission"){
                        $data['admission']       = 1;
                    }else if($yf == "evaluation"){
                        $data['evaluation']       = 1;
                    }
                }
            }
            $data['s_vat']       = $this->input->post('vat');
            $data['total']       = $this->input->post('total');
            $data['s_session']       = $this->input->post('s_session');
            $data['year']       = $this->input->post('year');
            $data['admission_date']       = $this->get_db_date_format($this->input->post('admission_date'));
            $data['admission_class']   = $this->input->post('class_id');
            $data['admission_section'] = $this->input->post('section_id');
            //Making roll auto matically
            if($data['s_session']=='01'){
                $fp = 'JAN/';
            }else{
                $fp = 'JUL/';
            }
            $fp .= substr($data['year'],2,2).'/';
            $query = $this->db->query("SELECT count(student_id) as students FROM student;");
            $serial = $query->row();
            $serial = $serial->students;
            $serial++;
            $fp .= $serial;

            $data['roll']       = $fp;
            $data['building_info']       = $this->input->post('building_info');
            $data['branch_info']       = $this->input->post('branch_info');
            $data['payment_month_start_from']       = $this->input->post('payment_month_start_from');
            $data['deleted']       = 0;
            $data['operator_id']       = $_SESSION['login_user_id'];
            $this->db->insert('student', $data);
            $student_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $student_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            //$this->email_model->account_opening_email('student', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/student_add/' . $data['class_id'], 'refresh');
        }
        if ($param2 == 'do_update') {
            $data['roll']       = $this->input->post('roll');
            //Checking Seat Capacity
            $student = $this->db->get_where('student' , array(
                'student_id' => $param3
            ))->row();
            $current_student_roll = $student->roll;
            if($current_student_roll != $data['roll']){
                //Checking for the Roll Duplicate
                $roll = $this->db->get_where('student' , array(
                    'roll' => $data['roll']
                ))->row();
                if($roll){
                    $this->session->set_flashdata('flash_message_error' , 'Sorry!!! , Student ID already Exists');
                    redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
                }
            }
            if($student->section_id != $this->input->post('section_id')){
                $seat_capacity_data = unserialize($this->crud_model->get_type_name_by_id('section',$this->input->post('section_id'),'capacity'));
                $seat_capacity = $seat_capacity_data['capacity_'.$this->input->post('year')];
                $current_students = $this->get_current_numbers_of_students($this->input->post('section_id'),$this->input->post('year'));
                if($current_students>=$seat_capacity){
                    $this->session->set_flashdata('flash_message_error' , 'Sorry!!! , Seat Capacity Overflow');
                    redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
                }
            }


            $data['name']       = $this->input->post('name');
            $data['birthday']   = $this->input->post('birthday');
            $data['gender']        = $this->input->post('gender');
            $data['present_address']    = $this->input->post('present_address');
            $data['parmanent_address']    = $this->input->post('parmanent_address');
            $data['phone']      = $this->input->post('phone');
            $data['sms_number']      = $this->input->post('sms_number');
            $data['email']      = $this->input->post('email');
            $data['password']   = $this->input->post('password');
            $data['class_id']   = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['parent_id']  = $this->input->post('parent_id');
            $data['parent_status']  = $this->input->post('parent_status');
            $data['mf_waiver']  = $this->input->post('mf_waiver');
            $data['ad_waiver']  = $this->input->post('ad_waiver');
            $data['ev_waiver']  = $this->input->post('ev_waiver');
            $data['c_lab_waiver']  = $this->input->post('c_lab_waiver');
            $data['p_lab_waiver']  = $this->input->post('p_lab_waiver');
            if(!$data['parent_status']){
                $data['mf_waiver']  = 0;
                $data['ad_waiver']  = 0;
                $data['ev_waiver']  = 0;
                $data['c_lab_waiver']  = 0;
                $data['p_lab_waiver']  = 0;
            }
            $data['active']  = $this->input->post('student_status');
            $data['roll']       = $this->input->post('roll');
            //Additional Parameters
            $data['institution_1']       = $this->input->post('institution_1');
            $data['class_1']       = $this->input->post('class_1');
            $data['year_1']       = $this->input->post('year_1');
            $data['position_1']       = $this->input->post('position_1');
            $data['passed_1']       = $this->input->post('passed_1');
            $data['institution_2']       = $this->input->post('institution_2');
            $data['class_2']       = $this->input->post('class_2');
            $data['year_2']       = $this->input->post('year_2');
            $data['position_2']       = $this->input->post('position_2');
            $data['passed_2']       = $this->input->post('passed_2');
            $data['institution_3']       = $this->input->post('institution_3');
            $data['class_3']       = $this->input->post('class_3');
            $data['year_3']       = $this->input->post('year_3');
            $data['position_3']       = $this->input->post('position_3');
            $data['passed_3']       = $this->input->post('passed_3');
            $data['reason_for_leaving']       = $this->input->post('reason_for_leaving');
            $data['c_brother_1']       = $this->input->post('c_brother_1');
            $data['cb_class_1']       = $this->input->post('cb_class_1');
            $data['cb_section_1']       = $this->input->post('cb_section_1');
            $data['c_brother_2']       = $this->input->post('c_brother_2');
            $data['cb_class_2']       = $this->input->post('cb_class_2');
            $data['cb_section_2']       = $this->input->post('cb_section_2');
            $data['c_brother_3']       = $this->input->post('c_brother_3');
            $data['cb_class_3']       = $this->input->post('cb_class_3');
            $data['cb_section_3']       = $this->input->post('cb_section_3');
            $data['p_brothers_1']       = $this->input->post('p_brothers_1');
            $data['pb_class_1']       = $this->input->post('pb_class_1');
            $data['pb_section_1']       = $this->input->post('pb_section_1');
            $data['p_brothers_2']       = $this->input->post('p_brothers_2');
            $data['pb_class_2']       = $this->input->post('pb_class_2');
            $data['pb_section_2']       = $this->input->post('pb_section_2');
            $data['p_brothers_3']       = $this->input->post('p_brothers_3');
            $data['pb_class_3']       = $this->input->post('pb_class_3');
            $data['pb_section_3']       = $this->input->post('pb_section_3');
            $data['monthly_fees']       = $this->input->post('monthly_fees');
            $data['admission_fees']       = $this->input->post('admission_fees');
            $data['evaluation_fees']       = $this->input->post('evaluation_fees');
            if($this->input->post('lab') == 1){
                $data['c_lab']       = 0;
                $data['p_lab']       = 0;
            }else if($this->input->post('lab') == 2){
                $data['c_lab']       = 1;
                $data['p_lab']       = 0;
            }else if($this->input->post('lab') == 3){
                $data['c_lab']       = 0;
                $data['p_lab']       = 1;
            }else if($this->input->post('lab') == 4){
                $data['c_lab']       = 1;
                $data['p_lab']       = 1;
            }
            $data['admission']       = 0;
            $data['evaluation']       = 0;
            if(sizeof($_POST['yearly_fee']) > 0){
                foreach($_POST['yearly_fee'] as $yf){
                    if($yf == "admission"){
                        $data['admission']       = 1;
                    }else if($yf == "evaluation"){
                        $data['evaluation']       = 1;
                    }
                }
            }
            $data['s_vat']       = $this->input->post('vat');
            $data['total']       = $this->input->post('total');
            $data['s_session']       = $this->input->post('s_session');
            $data['year']       = $this->input->post('year');

            $data['building_info']       = $this->input->post('building_info');
            $data['branch_info']       = $this->input->post('branch_info');
            $data['payment_month_start_from']       = $this->input->post('payment_month_start_from');
            $data['special_monthly_fee']       = $this->input->post('special_monthly_fee');
            $data['special_admission_fee']       = $this->input->post('special_admission_fee');
            $data['special_evaluation_fee']       = $this->input->post('special_evaluation_fee');
            $data['special_c_lab_fee']       = $this->input->post('special_c_lab_fee');
            $data['special_p_lab_fee']       = $this->input->post('special_p_lab_fee');
            $data['admission_date']       = $this->get_db_date_format($this->input->post('admission_date'));
            $data['edited_operator_id']       = $_SESSION['login_user_id'];
            
            $this->db->where('student_id', $param3);
            $this->db->update('student', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/student_image/' . $param3 . '.jpg');
            $this->crud_model->clear_cache();
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
        } 
		
        if ($param2 == 'delete') {
            $data['deleted'] = 1;
            $this->db->where('student_id', $param3);
            $this->db->update('student',$data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
        }

        if ($param2 == 'reactivate') {
            $data['deleted'] = 0;
            $this->db->where('student_id', $param3);
            $this->db->update('student',$data);
            $this->session->set_flashdata('flash_message' , get_phrase('data re-activated'));
            redirect(base_url() . 'index.php?admin/student_information/' . $param1, 'refresh');
        }
    }
     /****MANAGE PARENTS CLASSWISE*****/
    function parent($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {

            //Checking for the Duplicate
            $parent = $this->db->get_where('parent' , array(
                'phone' => $this->input->post('phone')
            ))->row();
            if($parent){
                $this->session->set_flashdata('flash_message_error' , 'Sorry Duplicate Parent Entry');
                redirect(base_url() . 'index.php?admin/parent/', 'refresh');
            }

            $data['father_name']        			= $this->input->post('father_name');
            $data['father_nationality']        			= $this->input->post('father_nationality');
            $data['father_occupation']        			= $this->input->post('father_occupation');
            $data['father_designation']        			= $this->input->post('father_designation');
            $data['father_tin']        			= $this->input->post('father_tin');
            $data['father_organization']        			= $this->input->post('father_organization');
            $data['father_nid']        			= $this->input->post('father_nid');

            $data['mother_name']        			= $this->input->post('mother_name');
            $data['mother_nationality']        			= $this->input->post('mother_nationality');
            $data['mother_occupation']        			= $this->input->post('mother_occupation');
            $data['mother_designation']        			= $this->input->post('mother_designation');
            $data['mother_tin']        			= $this->input->post('mother_tin');
            $data['mother_organization']        			= $this->input->post('mother_organization');
            $data['mother_nid']        			= $this->input->post('mother_nid');

            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone']       			= $this->input->post('phone');
            $data['address']     			= $this->input->post('address');
            $data['operator_id']       = $_SESSION['login_user_id'];

            $parent_id = $this->db->insert('parent', $data);
            move_uploaded_file($_FILES['father_file']['tmp_name'], 'uploads/parent_image/father' . $parent_id . '.jpg');
            move_uploaded_file($_FILES['mother_file']['tmp_name'], 'uploads/parent_image/mother' . $parent_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            //$this->email_model->account_opening_email('parent', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'edit') {

            //Checking for the Duplicate
            $parent = $this->db->get_where('parent' , array(
                'phone' => $this->input->post('phone'),
                'parent_id !=' => $param2
            ))->row();

            if($parent){
                $this->session->set_flashdata('flash_message_error' , 'Sorry Duplicate Parent Entry');
                redirect(base_url() . 'index.php?admin/parent/', 'refresh');
            }

            $data['father_name']        			= $this->input->post('father_name');
            $data['father_nationality']        			= $this->input->post('father_nationality');
            $data['father_occupation']        			= $this->input->post('father_occupation');
            $data['father_designation']        			= $this->input->post('father_designation');
            $data['father_tin']        			= $this->input->post('father_tin');
            $data['father_organization']        			= $this->input->post('father_organization');
            $data['father_nid']        			= $this->input->post('father_nid');

            $data['mother_name']        			= $this->input->post('mother_name');
            $data['mother_nationality']        			= $this->input->post('mother_nationality');
            $data['mother_occupation']        			= $this->input->post('mother_occupation');
            $data['mother_designation']        			= $this->input->post('mother_designation');
            $data['mother_tin']        			= $this->input->post('mother_tin');
            $data['mother_organization']        			= $this->input->post('mother_organization');
            $data['mother_nid']        			= $this->input->post('mother_nid');

            $data['email']       			= $this->input->post('email');
            $data['password']    			= $this->input->post('password');
            $data['phone']       			= $this->input->post('phone');
            $data['address']     			= $this->input->post('address');
            $data['edited_operator_id']       = $_SESSION['login_user_id'];

            $this->db->where('parent_id' , $param2);
            $this->db->update('parent' , $data);
            move_uploaded_file($_FILES['father_file']['tmp_name'], 'uploads/parent_image/father' . $param2 . '.jpg');
            move_uploaded_file($_FILES['mother_file']['tmp_name'], 'uploads/parent_image/mother' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->db->where('parent_id' , $param2);
            $this->db->delete('parent');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/parent/', 'refresh');
        }
        $page_data['page_title'] 	= get_phrase('all_parents');
        $page_data['page_name']  = 'parent';
        $page_data['parent_occupation']  = $this->db->get('parent_occupation')->result_array();
        $this->load->view('backend/index', $page_data);
    }
	
    
    /****MANAGE TEACHERS*****/
    function teacher($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            $data['password']    = $this->input->post('password');
            $this->db->insert('teacher', $data);
            $teacher_id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $teacher_id . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            $this->email_model->account_opening_email('teacher', $data['email']); //SEND EMAIL ACCOUNT OPENING EMAIL
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['birthday']    = $this->input->post('birthday');
            $data['sex']         = $this->input->post('sex');
            $data['address']     = $this->input->post('address');
            $data['phone']       = $this->input->post('phone');
            $data['email']       = $this->input->post('email');
            
            $this->db->where('teacher_id', $param2);
            $this->db->update('teacher', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/teacher_image/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        } else if ($param1 == 'personal_profile') {
            $page_data['personal_profile']   = true;
            $page_data['current_teacher_id'] = $param2;
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('teacher', array(
                'teacher_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('teacher_id', $param2);
            $this->db->delete('teacher');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/teacher/', 'refresh');
        }
        $page_data['teachers']   = $this->db->get('teacher')->result_array();
        $page_data['page_name']  = 'teacher';
        $page_data['page_title'] = get_phrase('manage_teacher');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE SUBJECTS*****/
    function subject($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            $this->db->insert('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/subject/'.$data['class_id'], 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']       = $this->input->post('name');
            $data['class_id']   = $this->input->post('class_id');
            $data['teacher_id'] = $this->input->post('teacher_id');
            
            $this->db->where('subject_id', $param2);
            $this->db->update('subject', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/subject/'.$data['class_id'], 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('subject', array(
                'subject_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('subject_id', $param2);
            $this->db->delete('subject');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/subject/'.$param3, 'refresh');
        }
		 $page_data['class_id']   = $param1;
        $page_data['subjects']   = $this->db->get_where('subject' , array('class_id' => $param1))->result_array();
        $page_data['page_name']  = 'subject';
        $page_data['page_title'] = get_phrase('manage_subject');
        $this->load->view('backend/index', $page_data);
    }
    
    /****MANAGE CLASSES*****/
    function classes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $data['teacher_id']   = $this->input->post('teacher_id');
            $this->db->insert('class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']         = $this->input->post('name');
            $data['name_numeric'] = $this->input->post('name_numeric');
            $data['teacher_id']   = $this->input->post('teacher_id');
            
            $this->db->where('class_id', $param2);
            $this->db->update('class', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class', array(
                'class_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_id', $param2);
            $this->db->delete('class');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/classes/', 'refresh');
        }
        $page_data['classes']    = $this->db->get('class')->result_array();
        $page_data['page_name']  = 'class';
        $page_data['page_title'] = get_phrase('manage_class');
        $this->load->view('backend/index', $page_data);
    }

    /****MANAGE SECTIONS*****/
    function section($class_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        // detect the first class
        if ($class_id == '')
            $class_id           =   $this->db->get('class')->first_row()->class_id;

        $page_data['page_name']  = 'section';
        $page_data['page_title'] = get_phrase('manage_sections');
        $page_data['class_id']   = $class_id;
        $this->load->view('backend/index', $page_data);    
    }

    function sections($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $data['session']   = $this->input->post('session');
            $sy = 2014;
            $ey = date('Y') + 1;
            $capacity = array();
            if($data['session'] == '01'){
                for($i = $sy ; $i <= $ey ; $i++){
                    $capacity['capacity_'.$i] = $this->input->post('capacity_'.$i);
                }
            }else{
                for($i = $sy ; $i <= $ey ; $i++){
                    $ny = $i.'-'.($i+1);
                    $capacity['capacity_'.$ny] = $this->input->post('capacity_'.$ny);
                }
            }
            $data['capacity'] =   serialize($capacity);
            $this->db->insert('section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'edit') {
            $data['name']       =   $this->input->post('name');
            $data['nick_name']  =   $this->input->post('nick_name');
            $data['class_id']   =   $this->input->post('class_id');
            $data['teacher_id'] =   $this->input->post('teacher_id');
            $data['session']   = $this->input->post('session');
            $sy = 2014;
            $ey = date('Y') + 1;
            $capacity = array();
            if($data['session'] == '01'){
                for($i = $sy ; $i <= $ey ; $i++){
                    $capacity['capacity_'.$i] = $this->input->post('capacity_'.$i);
                }
            }else{
                for($i = $sy ; $i <= $ey ; $i++){
                    $ny = $i.'-'.($i+1);
                    $capacity['capacity_'.$ny] = $this->input->post('capacity_'.$ny);
                }
            }
            $data['capacity'] =   serialize($capacity);
            $this->db->where('section_id' , $param2);
            $this->db->update('section' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/section/' . $data['class_id'] , 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('section_id' , $param2);
            $this->db->delete('section');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/section' , 'refresh');
        }
    }

    function get_class_section($class_id='',$s_session='')
    {
        if($s_session==''){
            $sections = $this->db->get_where('section' , array(
                'class_id' => $class_id
            ))->result_array();
        }else{
            $sections = $this->db->get_where('section' , array(
                'class_id' => $class_id,
                'session' => $s_session
            ))->result_array();
        }

        echo '<option value="">Select Section</option>';
        foreach ($sections as $row) {
            echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_students_by_class_section($class_id,$section_id)
    {
        $students = $this->db->get_where('student' , array(
            'class_id' => $class_id,
            'section_id' => $section_id
        ))->result_array();
        foreach ($students as $row) {
            $parents = $this->db->get_where('parent' , array(
                'parent_id' => $row['parent_id']
            ))->result_array();
            $info = $row['roll'].','.$row['name'].','.$parents[0]['father_name'];
            echo '<option class="'.$info.'" value="' . $row['student_id'] . '">' . $row['name'] . '</option>';
        }
    }

    function get_students_by_id($student_id)
    {
        $student_id = str_replace('-','/',$student_id);

        $query = $this->db->query("SELECT * FROM student WHERE roll='$student_id' AND deleted='0' AND (active='1' OR active='2')");
        $students = $query->result_array();

//        $students = $this->db->get_where('student' , array(
//            'roll' => $student_id,'deleted'=>0,'active'=>1
//        ))->result_array();

        $data = '';

        //Checking Branch Access allowed or not
        $user_level = $_SESSION['level'];
        $branch_id = $_SESSION['branch'];
        if($user_level==11){
//            $query = $this->db->query("SELECT buildings FROM branch WHERE branch_id='$branch_id'");
//            $temp = $query->row();
//            $b = explode(',',$temp->buildings);
            if($students[0]['branch_info']==$branch_id){
                $allowed = 1;
            }else{
                $allowed = 0;
            }
        }else{
            $allowed = 1;
        }

        if($students!='' && $allowed){
            foreach ($students as $row) {
                $data .= $row['name'];

                $special_monthly_fee = $row['special_monthly_fee'];
                $special_admission_fee = $row['special_admission_fee'];
                $special_evaluation_fee = $row['special_evaluation_fee'];

                $mf_waiver = $row['mf_waiver'];
                $ad_waiver = $row['ad_waiver'];
                $ev_waiver = $row['ev_waiver'];
                $c_lab_waiver = $row['c_lab_waiver'];
                $p_lab_waiver = $row['p_lab_waiver'];

                //Generating Parent Info
                $parents = $this->db->get_where('parent' , array(
                    'parent_id' => $row['parent_id']
                ))->result_array();
                if(isset($parents[0]['father_name'])){
                    $father_name = $parents[0]['father_name'];
                }else{
                    $father_name = '';
                }
                $data .= '~'.$father_name;

                //Generating Class and Section Info and Academic Fees
                $academic_fees = $this->db->get_where('academic_fees ' , array(
                    'class_id' => $row['class_id'],
                    'year' => $row['year']
                ))->row();

                $class_name = $this->db->get_where('class' , array(
                    'class_id' => $row['class_id']
                ))->row();

                $data .= '~'.$class_name->name;


                //Checking for the Special Monthly Fee
                if($special_monthly_fee > 0){
                    if($mf_waiver > 0){
                        if($mf_waiver == 100){
                            $data .= '~'.'0';
                        }else{
                            $data .= '~'.($special_monthly_fee*$mf_waiver/100);
                        }
                    }else{
                        $data .= '~'.$special_monthly_fee;
                    }
                }else{
                    if($mf_waiver > 0){
                        if($mf_waiver == 100){
                            $data .= '~'.'0';
                        }else{
                            $data .= '~'.($academic_fees->mf*$mf_waiver/100);
                        }
                    }else{
                        $data .= '~'.$academic_fees->mf;
                    }
                }
                //Checking for the Special Admission Fee
                if($special_admission_fee > 0){
                    if($ad_waiver > 0){
                        if($ad_waiver == 100){
                            $data .= '~'.'0';
                        }else{
                            $data .= '~'.($special_admission_fee*$ad_waiver/100);
                        }
                    }else{
                        $data .= '~'.$special_admission_fee;
                    }
                }else{
                    if($ad_waiver > 0){
                        if($ad_waiver == 100){
                            $data .= '~'.'0';
                        }else{
                            $data .= '~'.($academic_fees->ad*$ad_waiver/100);
                        }
                    }else{
                        $data .= '~'.$academic_fees->ad;
                    }
                }
                //Checking for the Special Evaluation Fee
                if($special_evaluation_fee > 0){
                    if($ev_waiver > 0){
                        if($ev_waiver == 100){
                            $data .= '~'.'0';
                        }else{
                            $data .= '~'.($special_evaluation_fee*$ev_waiver/100);
                        }
                    }else{
                        $data .= '~'.$special_evaluation_fee;
                    }
                }else{
                    if($ev_waiver > 0){
                        if($ev_waiver == 100){
                            $data .= '~'.'0';
                        }else{
                            $data .= '~'.($academic_fees->ev*$ev_waiver/100);
                        }
                    }else{
                        $data .= '~'.$academic_fees->ev;
                    }
                }

                $section = $this->db->get_where('section' , array(
                    'section_id' => $row['section_id']
                ))->result_array();
                $data .= '~'.$section[0]['name'];
                $data .= '~'.$row['parent_status'];

                //Getting building name
                $building = $this->db->get_where('building' , array(
                    'id' => $row['building_info']
                ))->result_array();
                $data .= '~'.$building[0]['building_name'];
                $data .= '~'.$row['year'];
                //Checking for the C Lab
                if($c_lab_waiver > 0){
                    if($c_lab_waiver == 100){
                        $data .= '~'.'0';
                    }else{
                        $data .= '~'.($academic_fees->c_lab*$c_lab_waiver/100);
                    }
                }else{
                    $data .= '~'.$academic_fees->c_lab;
                }
                //Checking for the P Lab
                if($p_lab_waiver > 0){
                    if($p_lab_waiver == 100){
                        $data .= '~'.'0';
                    }else{
                        $data .= '~'.($academic_fees->p_lab*$p_lab_waiver/100);
                    }
                }else{
                    $data .= '~'.$academic_fees->p_lab;
                }
                $data .= '~'.$academic_fees->tc;

                print_r($data);
            }
        }else{
            print_r($data);
        }
    }

    function get_students_payment_status_by_id($student_id)
    {
        $student_id = str_replace('-','/',$student_id);
        $students = $this->db->get_where('student' , array(
            'roll' => $student_id,
            'deleted' => 0
        ))->row();
        //print_r($students);
        $id = $students->student_id;
        $s_session = $students->s_session;
        $status = $students->active;

        $special_admission_fee = $students->special_admission_fee;
        $special_evaluation_fee = $students->special_evaluation_fee;
        $special_c_lab_fee = $students->special_c_lab_fee;
        $special_p_lab_fee = $students->special_p_lab_fee;

        $mf_waiver = $students->mf_waiver;
        $ad_waiver = $students->ad_waiver;
        $ev_waiver = $students->ev_waiver;
        $c_lab_waiver = $students->c_lab_waiver;
        $p_lab_waiver = $students->p_lab_waiver;

        if($s_session=='01'){
            $from_month = $students->payment_month_start_from;
            $to_month = date('m');
            $from_year = $students->year;
            $to_year = $students->year;
            //$to_month = $to_month + ($to_year-$from_year)*12;
            if($from_year<date('Y')){
                $to_month = 12;
            }
        }else if($s_session=='07'){
            $from_month = $students->payment_month_start_from;
            if($from_month <= 6){
                $from_month = $from_month + 12 ;
            }
            $to_month = date('m');
            $ttt = explode('-',$students->year);
            $from_year = $ttt[0];
            $to_year = $ttt[1];

            if($to_year < date('Y')){
                $to_month = 18;
            }else if($to_year == date('Y')){
                $to_month = $to_month + 12;
            }else{
                $to_month = 18;
            }
        }

        $data = $id.'~';
        $payment_uptodate = 1;
        $months = array('January','February','March','April','May','June','July','August','September','October','November','December');
        $year = $students->year;

        $fine = 0;
        $fine_amount = $this->db->get_where('settings', array('type' => 'fine_amount'))->row()->description;
        $fine_date = $this->db->get_where('settings', array('type' => 'fine_date'))->row()->description;

        //Checking for the tuition fee paid or not
        $is_increased = 0;
        if($from_month > 12){
            $from_year++;
            $is_increased = 1;
        }
        while($from_month<=$to_month && $from_year<=$to_year){
            $m = $from_month%12;
            if($m==0){
                $m = 12;
            }
            if($from_month==13){
                if($is_increased == 0){
                    $from_year++;
                }
            }
            //Searching
            if($m<10){
                $school_fee = $this->db->get_where('school_fee' , array(
                    'student_id' => $id,
                    'month' => '0'.$m,
                    'year' => $from_year,
                    'deleted' => 0
                ))->row();
            }else{
                $school_fee = $this->db->get_where('school_fee' , array(
                    'student_id' => $id,
                    'month' => $m,
                    'year' => $from_year,
                    'deleted' => 0
                ))->row();
            }
            if(!$school_fee){
                $data.= '<p style="color:red;">This student has due for '.$months[$m-1].' '.$from_year.'</p>';
                $payment_uptodate = 0;
            }
            $from_month++;
        }

        if($payment_uptodate){
            //Getting last payment month
            $query = $this->db->query("SELECT * FROM school_fee WHERE student_id='$id' AND deleted='0' ORDER BY year DESC,month DESC   LIMIT 1");
            $res = $query->row();
            $last_payment_month = intval($res->month);
            $last_payment_year = intval($res->year);
            if(!$last_payment_month){
                $last_payment_month = $students->payment_month_start_from;
                $last_payment_month--;
                if($last_payment_month==0){
                    $last_payment_month = 12;
                }
            }
            if(!$last_payment_year){
                if($s_session=='01'){
                    $last_payment_year = $year;
                    if($last_payment_month==12){
                        $last_payment_year--;
                    }
                }else if($s_session=='07'){
                    $ttt = explode('-',$year);
                    $last_payment_year = $ttt[0];
                }
            }

            $data.= '<p style="color:green;">This student Payment is uptodate to '.$months[$last_payment_month-1].' '.$last_payment_year.'</p>~';

            //Code for the dropdown month
            if($s_session==01){
                for($j=$last_payment_month+1;$j<=12;$j++){
                    if($j<10){
                        $j = '0'.$j;
                    }
                    $data.= '<option value="'.$j.'">'.$months[$j-1].'</option>';
                }
            }else if($s_session==07){
                $ttt = explode('-',$year);
                $next_payment_year = $ttt[1];
                if($last_payment_year==$next_payment_year){
                    $last_payment_month = $last_payment_month + 12;
                }
                for($j=$last_payment_month+1;$j<=18;$j++){
                    $mm = $j%12;
                    if($mm==0){$mm=12;}
                    if($j<10){
                        $j = '0'.$j;
                    }
                    $data.= '<option value="'.$j.'">'.$months[$mm-1].'</option>';
                }
            }

            $data.= '~';
        }else{
            $data.= '~';
            //Code for the dropdown month
            if($s_session==01){
                $i = $students->payment_month_start_from;
                for(;$i<=12;$i++){
                    if($i<10){
                        $school_fee = $this->db->get_where('school_fee' , array(
                            'student_id' => $id,
                            'month' => '0'.$i,
                            'year' => $year,
                            'deleted' => 0
                        ))->row();
                    }else{
                        $school_fee = $this->db->get_where('school_fee' , array(
                            'student_id' => $id,
                            'month' => $i,
                            'year' => $year,
                            'deleted' => 0
                        ))->row();
                    }
                    if(!$school_fee){
                        $j = $i;
                        if($j<10){
                            $j = '0'.$j;
                        }
                        $data.= '<option value="'.$j.'">'.$months[$j-1].'</option>';
                    }
                }
            }else if($s_session==07){
                $ttt = explode('-',$year);
                $year = $ttt[0];
                $i = $students->payment_month_start_from;
                if($i <= 6){
                    $i = $i + 12;
                }
                $is_increased = 0;
                if($i > 12){
                    $year++;
                    $is_increased = 1;
                }
                for(;$i<=18;$i++){
                    $mm = $i%12;
                    if($mm==0){$mm=12;}
                    if($i==13){if($is_increased == 0){$year++;}}
                    if($mm<10){
                        $school_fee = $this->db->get_where('school_fee' , array(
                            'student_id' => $id,
                            'month' => '0'.$mm,
                            'year' => $year,
                            'deleted' => 0
                        ))->row();
                    }else{
                        $school_fee = $this->db->get_where('school_fee' , array(
                            'student_id' => $id,
                            'month' => $mm,
                            'year' => $year,
                            'deleted' => 0
                        ))->row();
                    }
                    if(!$school_fee){
                        $j = $i;
                        if($j<10){
                            $j = '0'.$j;
                        }
                        $data.= '<option value="'.$j.'">'.$months[$mm-1].'</option>';
                    }
                }
            }

            $data.= '~';
        }

        //Checking for the last payment due
        $query = $this->db->query("SELECT * FROM payment WHERE student_id='$id' AND deleted='0' ORDER BY payment_id DESC LIMIT 1");
        $last_payment = $query->row();
        if($last_payment){
            if($last_payment->adjustment_due!=''){
                $a_due = $last_payment->adjustment_due;
                $a_to = $last_payment->adjustment_to;
                $due_on = 'Due on ';

                $a_due = explode(',',$a_due);
                $a_to = explode(',',$a_to);

                $i = 0;
                foreach($a_to as $ato){
                    $ato = explode(':',$ato);
                    $field_name = $ato[0];
                    $field_text = $ato[1];
                    $field_total_name = $ato[2];
                    $field_total = $a_due[$i];

                    if($field_name=='monthly_fee'){
                        $field_name = 'due_on_'.$field_name;
                    }

                    if($field_total_name=='fee_total'){
                        $field_total_name = 'due_on_'.$field_total_name;
                    }

                    $data.= '<tr><td><input type="text" class="form-control h_input" name="'.$field_name.'" id="'.$field_name.'" value="'.$due_on.$field_text.'" autofocus readonly></td>
                        <td></td>
                        <td></td>
                        <td>
                        <input type="text" class="form-control h_input amount" name="'.$field_total_name.'" id="'.$field_total_name.'" base="'.$field_total.'" value="'.$field_total.'" autofocus readonly>
                        <input type="hidden" name="'.$field_total_name.'_base" id="'.$field_total_name.'_base" value="'.$field_total.'">
                        </td>
                        </tr>';
                    $i++;
                }
                $data.= '~';

                /*$due_on = 'Due on ';
                if($last_payment->adjustment_to==1){
                    $due_on .= 'Monthly Fee';
                }else if($last_payment->adjustment_to==2){
                    $due_on .= 'Admission Fee';
                }else if($last_payment->adjustment_to==3){
                    $due_on .= 'Evaluation Fee';
                }
                $data.= '<tr><td><input type="text" class="form-control h_input" name="previous_due" id="previous_due" value="'.$due_on.'" autofocus readonly></td>
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control h_input amount" name="previous_due_amount" id="previous_due_amount" value="'.$last_payment->adjustment_due.'" autofocus readonly></td>
                        </tr>~';*/
            }else{
                $data.= '~';
            }
        }else{
            $data.= '~';
        }
        $data.= $fine;

        //Checking for the Admission Fee and Evaluation fee
        $payment_year = $students->year;

        $admission_status = 0;
        $evaluation_status = 0;
        //Checking Lab Fee Eligibility
        if(isset($students->c_lab) && $students->c_lab > 0){
            $clab_status = 0;
        }else{
            $clab_status = 1;
        }
        if(isset($students->p_lab) && $students->p_lab > 0){
            $plab_status = 0;
        }else{
            $plab_status = 1;
        }

        $sql = "SELECT * FROM payment WHERE student_id='$id' AND payment_year='$payment_year' AND deleted='0'";
        $query = $this->db->query($sql);
        $payments = $query->result_array();

        foreach($payments as $payment){
            $payment_id = $payment['payment_id'];
            $payment_items = $this->db->get_where('payment_items' , array(
                'payment_id' => $payment_id
            ))->result_array();
            foreach($payment_items as $item){
                if($item['form_item_name']=='add_fee_nameadmission_fee'){
                    $admission_status = 1;
                }
                if($item['form_item_name']=='add_fee_nameevaluation_fee'){
                    $evaluation_status = 1;
                }
                if($item['form_item_name']=='add_fee_namec_lab_fee'){
                    $clab_status = 1;
                }
                if($item['form_item_name']=='add_fee_namep_lab_fee'){
                    $plab_status = 1;
                }
            }
        }

        //Getting Admission fee and Evaluation fee
        $academic_fees = $this->db->get_where('academic_fees ' , array(
            'class_id' => $students->class_id,
            'year' => $students->year
        ))->row();

        //Special Admission and Waver
        if($special_admission_fee > 0){
            if($ad_waiver > 0){
                if($ad_waiver == 100){
                    $admission_fee = 0;
                }else{
                    $admission_fee = $special_admission_fee*$ad_waiver/100;
                }
            }else{
                $admission_fee = $special_admission_fee;
            }
        }else{
            if($ad_waiver > 0){
                if($ad_waiver == 100){
                    $admission_fee = 0;
                }else{
                    $admission_fee = $academic_fees->ad*$ad_waiver/100;
                }
            }else{
                $admission_fee = $academic_fees->ad;
            }
        }
        //Special Evaluation and Waver
        if($special_evaluation_fee > 0){
            if($ev_waiver > 0){
                if($ev_waiver == 100){
                    $evaluation_fee = 0;
                }else{
                    $evaluation_fee = $special_evaluation_fee*$ev_waiver/100;
                }
            }else{
                $evaluation_fee = $special_evaluation_fee;
            }
        }else{
            if($ev_waiver > 0){
                if($ev_waiver == 100){
                    $evaluation_fee = 0;
                }else{
                    $evaluation_fee = $academic_fees->ev*$ev_waiver/100;
                }
            }else{
                $evaluation_fee = $academic_fees->ev;
            }
        }
        //Special C Lab and Waver
        if($special_c_lab_fee > 0){
            if($c_lab_waiver > 0){
                if($c_lab_waiver == 100){
                    $c_lab_fee = 0;
                }else{
                    $c_lab_fee = $special_c_lab_fee*$c_lab_waiver/100;
                }
            }else{
                $c_lab_fee = $special_c_lab_fee;
            }
        }else{
            if($c_lab_waiver > 0){
                if($c_lab_waiver == 100){
                    $c_lab_fee = 0;
                }else{
                    $c_lab_fee = $academic_fees->c_lab*$c_lab_waiver/100;
                }
            }else{
                $c_lab_fee = $academic_fees->c_lab;
            }
        }
        //Special P Lab and Waver
        if($special_p_lab_fee > 0){
            if($p_lab_waiver > 0){
                if($p_lab_waiver == 100){
                    $p_lab_fee = 0;
                }else{
                    $p_lab_fee = $special_p_lab_fee*$p_lab_waiver/100;
                }
            }else{
                $p_lab_fee = $special_p_lab_fee;
            }
        }else{
            if($p_lab_waiver > 0){
                if($p_lab_waiver == 100){
                    $p_lab_fee = 0;
                }else{
                    $p_lab_fee = $academic_fees->p_lab*$p_lab_waiver/100;
                }
            }else{
                $p_lab_fee = $academic_fees->p_lab;
            }
        }

        $data.= '~';
        if($status==1){
            if($admission_status==0 && $students->admission){
                $data.= '<tr id="admission_fee">
                        <td><button class="close admission_fee" style="width: 15%;float: left"><img src="'.base_url().'assets/images/close.png"></button><input style="width: 85%;float: left" class="form-control h_input" name="add_fee_nameadmission_fee" id="add_fee_nameadmission_fee" value="Admission Fee" autofocus="" readonly="" type="text"></td>
                        <td></td>
                        <td></td>
                        <td>
                        <input class="form-control h_input amount" name="add_fee_totaladmission_fee" id="add_fee_totaladmission_fee" base="'.$admission_fee.'" value="'.$admission_fee.'" autofocus="" readonly="" type="text">
                        <input name="add_fee_totaladmission_fee_base" id="add_fee_totaladmission_fee_base" value="'.$admission_fee.'" type="hidden">
                        </td>
                        <td><input class="form-control h_input waiver add_fee_totaladmission_fee" name="add_fee_nameadmission_fee_waiver" id="add_fee_nameadmission_fee_waiver" value="" base="" type="text"></td>
                        <td><input class="form-control h_input" name="add_fee_nameadmission_fee_comment" id="add_fee_nameadmission_fee_comment" type="text"></td>
                     </tr>';
            }
        }
        if($evaluation_status==0 && $students->evaluation){
            $data.= '<tr id="evaluation_fee">
                        <td><button class="close evaluation_fee" style="width: 15%;float: left"><img src="'.base_url().'assets/images/close.png"></button><input style="width: 85%;float: left" class="form-control h_input" name="add_fee_nameevaluation_fee" id="add_fee_nameevaluation_fee" value="Evaluation Fee" autofocus="" readonly="" type="text"></td>
                        <td></td>
                        <td></td>
                        <td>
                        <input class="form-control h_input amount" name="add_fee_totalevaluation_fee" id="add_fee_totalevaluation_fee" base="'.$evaluation_fee.'" value="'.$evaluation_fee.'" autofocus="" readonly="" type="text">
                        <input name="add_fee_totalevaluation_fee_base" id="add_fee_totalevaluation_fee_base" value="'.$evaluation_fee.'" type="hidden">
                        </td>
                        <td><input class="form-control h_input waiver add_fee_totalevaluation_fee" name="add_fee_nameevaluation_fee_waiver" id="add_fee_nameevaluation_fee_waiver" value="" base="" type="text"></td>
                        <td><input class="form-control h_input" name="add_fee_nameevaluation_fee_comment" id="add_fee_nameevaluation_fee_comment" type="text"></td>
                     </tr>';
        }
        if($clab_status==0){
            $data.= '<tr id="c_lab_fee">
                        <td><button class="close c_lab_fee" style="width: 15%;float: left"><img src="'.base_url().'assets/images/close.png"></button><input style="width: 85%;float: left" class="form-control h_input" name="add_fee_namec_lab_fee" id="add_fee_namec_lab_fee" value="Chemistry Lab" autofocus="" readonly="" type="text"></td>
                        <td></td>
                        <td></td>
                        <td>
                        <input class="form-control h_input amount" name="add_fee_totalc_lab_fee" id="add_fee_totalc_lab_fee" base="'.$c_lab_fee.'" value="'.$c_lab_fee.'" autofocus="" readonly="" type="text">
                        <input name="add_fee_totalc_lab_fee_base" id="add_fee_totalc_lab_fee_base" value="'.$c_lab_fee.'" type="hidden">
                        </td>
                        <td><input class="form-control h_input waiver add_fee_totalc_lab_fee" name="add_fee_namec_lab_fee_waiver" id="add_fee_namec_lab_fee_waiver" value="" base="" type="text"></td>
                        <td><input class="form-control h_input" name="add_fee_namec_lab_fee_comment" id="add_fee_namec_lab_fee_comment" type="text"></td>
                     </tr>';
        }
        if($plab_status==0){
            $data.= '<tr id="p_lab_fee">
                        <td><button class="close p_lab_fee" style="width: 15%;float: left"><img src="'.base_url().'assets/images/close.png"></button><input style="width: 85%;float: left" class="form-control h_input" name="add_fee_namep_lab_fee" id="add_fee_namep_lab_fee" value="Physics Lab" autofocus="" readonly="" type="text"></td>
                        <td></td>
                        <td></td>
                        <td>
                        <input class="form-control h_input amount" name="add_fee_totalp_lab_fee" id="add_fee_totalp_lab_fee" base="'.$p_lab_fee.'" value="'.$p_lab_fee.'" autofocus="" readonly="" type="text">
                        <input name="add_fee_totalp_lab_fee_base" id="add_fee_totalp_lab_fee_base" value="'.$p_lab_fee.'" type="hidden">
                        </td>
                        <td><input class="form-control h_input waiver add_fee_totalp_lab_fee" name="add_fee_namep_lab_fee_waiver" id="add_fee_namep_lab_fee_waiver" value="" base="" type="text"></td>
                        <td><input class="form-control h_input" name="add_fee_namep_lab_fee_comment" id="add_fee_namep_lab_fee_comment" type="text"></td>
                     </tr>';
        }

        print_r($data);
    }

    function get_fine_status($from='',$to='',$id=''){
        $students = $this->db->get_where('student' , array(
            'student_id' => $id,
            'deleted' => 0
        ))->row();

        $s_session = $students->s_session;
        $status = $students->active;

        //Checking for the Fine
        $from_month = intval($from);
        $to_month = intval($to);
        if($s_session=='01'){
            $from_year = $students->year;
            $to_year = $students->year;
        }else if($s_session=='07'){
            $ttt = explode('-',$students->year);
            $from_year = $ttt[0];
            $to_year = $ttt[1];
        }

        $fine = 0;
        $fine_amount = $this->db->get_where('settings', array('type' => 'fine_amount'))->row()->description;
        $fine_date = $this->db->get_where('settings', array('type' => 'fine_date'))->row()->description;

        //Checking for the tuition fee paid or not
        $year_already_incremented = 0;
        if($from_month > 12){
            $year_already_incremented = 1;
            $from_year++;
        }
        while($from_month<=$to_month && $from_year<=$to_year){
            $m = $from_month%12;
            if($m==0){
                $m = 12;
            }
            if($from_month==13){
                if($year_already_incremented == 0){
                    $from_year++;
                }
            }
            //Searching
            if($m<10){
                $school_fee = $this->db->get_where('school_fee' , array(
                    'student_id' => $id,
                    'month' => '0'.$m,
                    'year' => $from_year,
                    'deleted' => 0
                ))->row();
            }else{
                $school_fee = $this->db->get_where('school_fee' , array(
                    'student_id' => $id,
                    'month' => $m,
                    'year' => $from_year,
                    'deleted' => 0
                ))->row();
            }
            if(!$school_fee){
                //Fine management here
                if($from_year < date('Y')){
                    $fine = $fine + $fine_amount;
                }else if(date('Y') == $from_year){
                    if($m<date('m')){
                        $fine = $fine + $fine_amount;
                    }else{
                        if((date('d')>=$fine_date) && (date('m') == $m)){
                            $fine = $fine + $fine_amount;
                        }
                    }
                }
            }
            $from_month++;
        }

        print_r($fine);
    }

    function get_class_subject($class_id)
    {
        $subjects = $this->db->get_where('subject' , array(
            'class_id' => $class_id
        ))->result_array();
        foreach ($subjects as $row) {
            echo '<option value="' . $row['subject_id'] . '">' . $row['name'] . '</option>';
        }
    }

    /****MANAGE EXAMS*****/
    function exam($param1 = '', $param2 = '' , $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['comment'] = $this->input->post('comment');
            $this->db->insert('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        }
        if ($param1 == 'edit' && $param2 == 'do_update') {
            $data['name']    = $this->input->post('name');
            $data['date']    = $this->input->post('date');
            $data['comment'] = $this->input->post('comment');
            
            $this->db->where('exam_id', $param3);
            $this->db->update('exam', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('exam', array(
                'exam_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('exam_id', $param2);
            $this->db->delete('exam');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/exam/', 'refresh');
        }
        $page_data['exams']      = $this->db->get('exam')->result_array();
        $page_data['page_name']  = 'exam';
        $page_data['page_title'] = get_phrase('manage_exam');
        $this->load->view('backend/index', $page_data);
    }

    /****** SEND EXAM MARKS VIA SMS ********/
    function exam_marks_sms($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_sms') {

            $exam_id    =   $this->input->post('exam_id');
            $class_id   =   $this->input->post('class_id');
            $receiver   =   $this->input->post('receiver');

            // get all the students of the selected class
            $students = $this->db->get_where('student' , array(
                'class_id' => $class_id
            ))->result_array();
            // get the marks of the student for selected exam
            foreach ($students as $row) {
                if ($receiver == 'student')
                    $receiver_phone = $row['phone'];
                if ($receiver == 'parent' && $row['parent_id'] != '') 
                    $receiver_phone = $this->db->get_where('parent' , array('parent_id' => $row['parent_id']))->row()->phone;
                

                $this->db->where('exam_id' , $exam_id);
                $this->db->where('student_id' , $row['student_id']);
                $marks = $this->db->get('mark')->result_array();
                $message = '';
                foreach ($marks as $row2) {
                    $subject       = $this->db->get_where('subject' , array('subject_id' => $row2['subject_id']))->row()->name;
                    $mark_obtained = $row2['mark_obtained'];  
                    $message      .= $row2['student_id'] . $subject . ' : ' . $mark_obtained . ' , ';
                    
                }
                // send sms
                $this->sms_model->send_sms( $message , $receiver_phone );
            }
            $this->session->set_flashdata('flash_message' , get_phrase('message_sent'));
            redirect(base_url() . 'index.php?admin/exam_marks_sms' , 'refresh');
        }
                
        $page_data['page_name']  = 'exam_marks_sms';
        $page_data['page_title'] = get_phrase('send_marks_by_sms');
        $this->load->view('backend/index', $page_data);
    }

    /****MANAGE EXAM MARKS*****/
    function marks($exam_id = '', $class_id = '', $subject_id = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($this->input->post('operation') == 'selection') {
            $page_data['exam_id']    = $this->input->post('exam_id');
            $page_data['class_id']   = $this->input->post('class_id');
            $page_data['subject_id'] = $this->input->post('subject_id');
            
            if ($page_data['exam_id'] > 0 && $page_data['class_id'] > 0 && $page_data['subject_id'] > 0) {
                redirect(base_url() . 'index.php?admin/marks/' . $page_data['exam_id'] . '/' . $page_data['class_id'] . '/' . $page_data['subject_id'], 'refresh');
            } else {
                $this->session->set_flashdata('mark_message', 'Choose exam, class and subject');
                redirect(base_url() . 'index.php?admin/marks/', 'refresh');
            }
        }
        if ($this->input->post('operation') == 'update') {
            $data['mark_obtained'] = $this->input->post('mark_obtained');
            $data['comment']       = $this->input->post('comment');
            
            $this->db->where('mark_id', $this->input->post('mark_id'));
            $this->db->update('mark', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/marks/' . $this->input->post('exam_id') . '/' . $this->input->post('class_id') . '/' . $this->input->post('subject_id'), 'refresh');
        }
        $page_data['exam_id']    = $exam_id;
        $page_data['class_id']   = $class_id;
        $page_data['subject_id'] = $subject_id;
        
        $page_data['page_info'] = 'Exam marks';
        
        $page_data['page_name']  = 'marks';
        $page_data['page_title'] = get_phrase('manage_exam_marks');
        $this->load->view('backend/index', $page_data);
    }
    
    
    /****MANAGE GRADES*****/
    function grade($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            $this->db->insert('grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['grade_point'] = $this->input->post('grade_point');
            $data['mark_from']   = $this->input->post('mark_from');
            $data['mark_upto']   = $this->input->post('mark_upto');
            $data['comment']     = $this->input->post('comment');
            
            $this->db->where('grade_id', $param2);
            $this->db->update('grade', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('grade', array(
                'grade_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('grade_id', $param2);
            $this->db->delete('grade');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/grade/', 'refresh');
        }
        $page_data['grades']     = $this->db->get('grade')->result_array();
        $page_data['page_name']  = 'grade';
        $page_data['page_title'] = get_phrase('manage_grade');
        $this->load->view('backend/index', $page_data);
    }
    
    /**********MANAGING CLASS ROUTINE******************/
    function class_routine($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        if ($param1 == 'create') {
            $data['class_id']   = $this->input->post('class_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']   = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['day']        = $this->input->post('day');
            $this->db->insert('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/class_routine/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['class_id']   = $this->input->post('class_id');
            $data['subject_id'] = $this->input->post('subject_id');
            $data['time_start'] = $this->input->post('time_start') + (12 * ($this->input->post('starting_ampm') - 1));
            $data['time_end']   = $this->input->post('time_end') + (12 * ($this->input->post('ending_ampm') - 1));
            $data['day']        = $this->input->post('day');
            
            $this->db->where('class_routine_id', $param2);
            $this->db->update('class_routine', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/class_routine/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('class_routine', array(
                'class_routine_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('class_routine_id', $param2);
            $this->db->delete('class_routine');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/class_routine/', 'refresh');
        }
        $page_data['page_name']  = 'class_routine';
        $page_data['page_title'] = get_phrase('manage_class_routine');
        $this->load->view('backend/index', $page_data);
    }
	
	/****** DAILY ATTENDANCE *****************/
	function manage_attendance($date='',$month='',$year='',$class_id='')
	{
		if($this->session->userdata('admin_login')!=1)redirect('login' , 'refresh');
		
		if($_POST)
		{
			// Loop all the students of $class_id
            $students   =   $this->db->get_where('student', array('class_id' => $class_id))->result_array();
            foreach ($students as $row)
            {
                $attendance_status  =   $this->input->post('status_' . $row['student_id']);

                $this->db->where('student_id' , $row['student_id']);
                $this->db->where('date' , $this->input->post('date'));

                $this->db->update('attendance' , array('status' => $attendance_status));
            }

			$this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
			redirect(base_url() . 'index.php?admin/manage_attendance/'.$date.'/'.$month.'/'.$year.'/'.$class_id , 'refresh');
		}
        $page_data['date']     =	$date;
        $page_data['month']    =	$month;
        $page_data['year']     =	$year;
        $page_data['class_id'] =	$class_id;
		
        $page_data['page_name']  =	'manage_attendance';
        $page_data['page_title'] =	get_phrase('manage_daily_attendance');
		$this->load->view('backend/index', $page_data);
	}
	function attendance_selector()
	{
		redirect(base_url() . 'index.php?admin/manage_attendance/'.$this->input->post('date').'/'.
					$this->input->post('month').'/'.
						$this->input->post('year').'/'.
							$this->input->post('class_id') , 'refresh');
	}
    /******MANAGE BILLING / INVOICES WITH STATUS*****/
    function edit_invoice($invoice_id)
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        $page_data['page_name']  = 'edit_invoice';
        $page_data['param2'] = $invoice_id;
        $page_data['page_title'] = get_phrase('edit invoice');
        $this->load->view('backend/index', $page_data);
    }

    function invoice($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'create') {

            //Checking for the Duplicate payment
            $student_id = $this->input->post('student_real_id');
            $students = $this->db->get_where('student' , array(
                'student_id' => $student_id
            ))->row();
            $s_session = $students->s_session;

//            if(isset($_POST['month_from']) && isset($_POST['month_to'])){
//                if($s_session=='01'){
//                    $year = $students->year;
//                }else if($s_session=='07'){
//                    $temp = explode('-',$students->year);
//                    $year = $temp[0];
//                }
//
//                $m_from = intval($_POST['month_from']);
//                $m_to = intval($_POST['month_to']);
//                for($i = $m_from;$i<= $m_to;$i++){
//                    if($i<10){
//                        $school_fee = $this->db->get_where('school_fee' , array(
//                            'student_id' => $student_id,
//                            'month' => '0'.$i,
//                            'year' => $year,
//                            'deleted' => 0
//                        ))->row();
//                    }else{
//                        $school_fee = $this->db->get_where('school_fee' , array(
//                            'student_id' => $student_id,
//                            'month' => $i,
//                            'year' => $year,
//                            'deleted' => 0
//                        ))->row();
//                    }
//                    if($school_fee){
//                        $this->session->set_flashdata('flash_message_error' , 'Duplicate Payment Found');
//                        redirect(base_url() . 'index.php?admin/invoice', 'refresh');
//                    }
//                }
//            }

            //data for the paymet table
            $payment['student_id']         = $this->input->post('student_real_id');
            $payment['total_amount']              = $this->input->post('invoice_total_amount');
            $payment['vat']              = $this->input->post('invoice_vat');
            $payment['total_receivable']              = $this->input->post('invoice_total_receivable');
            $payment['received_amount']        = $this->input->post('invoice_received_amount');
            $payment['returned_amount']             = $this->input->post('invoice_return_amount');
            $payment['adjustment_amount']             = $this->input->post('invoice_adjustment_amount');
            $payment['adjustment_due']        = $this->input->post('h_adjustment_due');
            $payment['adjustment_to']                = $this->input->post('h_adjustment_to');
            $payment['month_from']             = $this->input->post('month_from');
            $payment['month_to']             = $this->input->post('month_to');
            $payment['payment_year']             = $this->input->post('student_payment_year');
            $payment['book_no']             = $this->input->post('book_no');
            $payment['monthly_fee']             = $this->input->post('real_monthly_fee');
            $payment['monthly_fee_given']             = $this->input->post('fee_total');
            //Getting building name
            $building_id = $this->crud_model->get_type_name_by_id('student',$this->input->post('student_real_id'),'building_info');
            $query = $this->db->query("SELECT * FROM building WHERE id='$building_id'");
            $building_info = $query->result_array();
            if(isset($building_info[0]['building_name'])){
                $payment['building_name']             = $building_info[0]['building_name'];
            }else{
                $payment['building_name']             = '';
            }

            $payment['building_id']             = $building_id;
            $payment['branch_id']             = $this->input->post('branch_name');
            $payment['timestamp'] = $this->get_db_date_format($this->input->post('date'));
            $payment['collector_id'] = $_SESSION['admin_id'];
            $payment['comment'] = $this->input->post('refund_comment');
            if($this->input->post('invoice_total_receivable')){
                $payment['payment_type'] = 1;
            }else{
                $payment['payment_type'] = 2;
            }
            $payment['deleted'] = 0;

            //Checking for the Only Due Payment
            if(isset($_POST['due_on_monthly_fee']) && !isset($_POST['month_from']) && !isset($_POST['month_to'])){
                $months_jan = array(
                    'January' => '01',
                    'February' => '02',
                    'March' => '03',
                    'April' => '04',
                    'May' => '05',
                    'June' => '06',
                    'July' => '07',
                    'August' => '08',
                    'September' => '09',
                    'October' => '10',
                    'November' => '11',
                    'December' => '12'
                );
                $months_jul = array(
                    'January' => '13',
                    'February' => '14',
                    'March' => '15',
                    'April' => '16',
                    'May' => '17',
                    'June' => '18',
                    'July' => '07',
                    'August' => '08',
                    'September' => '09',
                    'October' => '10',
                    'November' => '11',
                    'December' => '12'
                );
                $temp = explode(' ',$_POST['due_on_monthly_fee']);
                $month_name = $temp[2];
                //Checking Student Session
                $temp = explode('-',$payment['payment_year']);
                $l = sizeof($temp);
                if($l == 1){
                    $payment['month_from']             = $months_jan[$month_name];
                    $payment['month_to']             = $months_jan[$month_name];
                }else if($l == 2){
                    $payment['month_from']             = $months_jul[$month_name];
                    $payment['month_to']             = $months_jul[$month_name];
                }
                $payment['monthly_fee_given']             = $_POST['due_on_fee_total'];
            }

            $this->db->insert('payment' , $payment);
            $payment_id = $this->db->insert_id();

            //data for the payment_items table

            if(isset($_POST['monthly_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'monthly_fee';
                $payment_item['form_amount_name'] = 'fee_total';
                $payment_item['form_waiver_name'] = 'fee_waiver';
                $payment_item['form_comment_name'] = 'fee_comment';
                $payment_item['item_name'] = $this->input->post('monthly_fee');
                $payment_item['item_amount'] = $this->input->post('fee_total');
                $payment_item['waiver_amount'] = $this->input->post('fee_waiver');
                $payment_item['comment'] = $this->input->post('fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['fee_total_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nameadmission_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nameadmission_fee';
                $payment_item['form_amount_name'] = 'add_fee_totaladmission_fee';
                $payment_item['form_waiver_name'] = 'add_fee_nameadmission_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_nameadmission_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_nameadmission_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totaladmission_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_nameadmission_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_nameadmission_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totaladmission_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_namec_lab_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_namec_lab_fee';
                $payment_item['form_amount_name'] = 'add_fee_totalc_lab_fee';
                $payment_item['form_waiver_name'] = 'add_fee_namec_lab_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_namec_lab_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_namec_lab_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalc_lab_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_namec_lab_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_namec_lab_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totalc_lab_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_namep_lab_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_namep_lab_fee';
                $payment_item['form_amount_name'] = 'add_fee_totalp_lab_fee';
                $payment_item['form_waiver_name'] = 'add_fee_namep_lab_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_namep_lab_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_namep_lab_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalp_lab_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_namep_lab_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_namep_lab_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totalp_lab_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nameevaluation_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nameevaluation_fee';
                $payment_item['form_amount_name'] = 'add_fee_totalevaluation_fee';
                $payment_item['form_waiver_name'] = 'add_fee_nameevaluation_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_nameevaluation_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_nameevaluation_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalevaluation_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_nameevaluation_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_nameevaluation_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totalevaluation_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nametc'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nametc';
                $payment_item['form_amount_name'] = 'add_fee_totaltc';
                $payment_item['form_waiver_name'] = 'add_fee_nametc_waiver';
                $payment_item['form_comment_name'] = 'add_fee_nametc_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_nametc');
                $payment_item['item_amount'] = $this->input->post('add_fee_totaltc');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_nametc_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_nametc_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totaltc_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['fine'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'fine';
                $payment_item['form_amount_name'] = 'fine_amount';
                $payment_item['form_waiver_name'] = '';
                $payment_item['form_comment_name'] = '';
                $payment_item['item_name'] = $this->input->post('fine');
                $payment_item['item_amount'] = $this->input->post('fine_amount');
                $payment_item['waiver_amount'] = '';
                $payment_item['comment'] = '';
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = '';

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['due_on_monthly_fee']) && isset($_POST['month_from']) && isset($_POST['month_to'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'due_on_monthly_fee';
                $payment_item['form_amount_name'] = 'due_on_fee_total';
                $payment_item['form_waiver_name'] = '';
                $payment_item['form_comment_name'] = '';
                $payment_item['item_name'] = $this->input->post('due_on_monthly_fee');
                $payment_item['item_amount'] = $this->input->post('due_on_fee_total');
                $payment_item['waiver_amount'] = '';
                $payment_item['comment'] = '';
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = '';

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nameremission'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nameremission';
                $payment_item['form_amount_name'] = 'add_fee_totalremission';
                $payment_item['form_waiver_name'] = '';
                $payment_item['form_comment_name'] = '';
                $payment_item['item_name'] = $this->input->post('add_fee_nameremission');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalremission');
                $payment_item['waiver_amount'] = '';
                $payment_item['comment'] = '';
                $payment_item['month_from'] = $this->input->post('remission_month_from');
                $payment_item['month_to'] = $this->input->post('remission_month_to');
                $payment_item['base'] = '';

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            for($i= 1;$i <=20 ; $i++){
                $n = 'add_fee_name'.$i;
                $t = 'add_fee_total'.$i;
                $w = 'add_fee_name'.$i.'_waiver';
                $c = 'add_fee_name'.$i.'_comment';
                $b = 'add_fee_total'.$i.'_base';
                if(isset($_POST[$n])){
                    $name = $_POST[$n];
                    $total = $_POST[$t];
                    $waiver = $_POST[$w];
                    $comment = $_POST[$c];
                    $base = $_POST[$b];
                    $payment_item['payment_id'] = $payment_id;
                    $payment_item['form_item_name'] = $n;
                    $payment_item['form_amount_name'] = $t;
                    $payment_item['form_waiver_name'] = $w;
                    $payment_item['form_comment_name'] = $c;
                    $payment_item['item_name'] = $name;
                    $payment_item['item_amount'] = $total;
                    $payment_item['waiver_amount'] = $waiver;
                    $payment_item['comment'] = $comment;
                    $payment_item['month_from'] = '';
                    $payment_item['month_to'] = '';
                    $payment_item['base'] = $base;

                    if($payment_item['item_amount'] > 0)
                        $this->db->insert('payment_items', $payment_item);
                }
            }

            //Data for the school fee table
            if(isset($_POST['month_from']) && isset($_POST['month_to'])){
                if($s_session=='01'){
                    $year = $students->year;
                }else if($s_session=='07'){
                    $temp = explode('-',$students->year);
                    $year = $temp[0];
                }

                $m_from = intval($_POST['month_from']);
                $m_to = intval($_POST['month_to']);
                $is_year_incremented = 0;
                if($m_from > 12){
                    $is_year_incremented = 1;
                    $year = $year + 1;
                }
                for($i = $m_from;$i<= $m_to;$i++){
                    $school_fee['student_id'] = $this->input->post('student_real_id');
                    $school_fee['payment_id'] = $payment_id;
                    $j = $i;
                    $m = $j % 12;
                    if($m==0){
                        $m=12;
                    }
                    if($i==13){
                        if($is_year_incremented == 0){
                            $year = $year + 1;
                        }
                    }
                    if($m<10 && $m>0){
                        $school_fee['month'] = '0'.$m;
                    }else{
                        $school_fee['month'] = $m;
                    }
                    $school_fee['year'] = $year;
                    $school_fee['fee'] = $this->input->post('real_monthly_fee');
                    $school_fee['date'] = $this->input->post('date');
                    $school_fee['deleted'] = 0;

                    $this->db->insert('school_fee', $school_fee);
                }
            }

            if(isset($_POST['remission_month_from']) && isset($_POST['remission_month_to'])){
                $m_to = intval($_POST['month_from']) - 1;
                $m_from = $_POST['remission_month_from'];
                if($s_session=='01'){
                    $year = $students->year;
                }else if($s_session=='07'){
                    $temp = explode('-',$students->year);
                    $year = $temp[0];
                }
                for($i = $m_from;$i<= $m_to;$i++){
                    $school_fee['student_id'] = $this->input->post('student_real_id');
                    $school_fee['payment_id'] = $payment_id;
                    $m = $i%12;
                    if($m==0){$m=12;}
                    if($i==13){$year++;}
                    if($m<10 && $m>0)
                        $school_fee['month'] = '0'.$m;
                    else
                        $school_fee['month'] = $m;
                    $school_fee['year'] = $year;
                    $school_fee['fee'] = 0;
                    $school_fee['date'] = $this->input->post('date');
                    $school_fee['deleted'] = 0;
                    $this->db->insert('school_fee', $school_fee);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }
        if ($param1 == 'do_update') {

            //Checking for the Duplicate payment
            $student_id = $this->input->post('student_real_id');
            $students = $this->db->get_where('student' , array(
                'student_id' => $student_id
            ))->row();
            $s_session = $students->s_session;

//            if(isset($_POST['month_from']) && isset($_POST['month_to'])){
//                if($s_session=='01'){
//                    $year = $students->year;
//                }else if($s_session=='07'){
//                    $temp = explode('-',$students->year);
//                    $year = $temp[0];
//                }
//
//                $m_from = intval($_POST['month_from']);
//                $m_to = intval($_POST['month_to']);
//                for($i = $m_from;$i<= $m_to;$i++){
//                    if($i<10){
//                        $school_fee = $this->db->get_where('school_fee' , array(
//                            'student_id' => $student_id,
//                            'month' => '0'.$i,
//                            'year' => $year,
//                            'deleted' => 0
//                        ))->row();
//                    }else{
//                        $school_fee = $this->db->get_where('school_fee' , array(
//                            'student_id' => $student_id,
//                            'month' => $i,
//                            'year' => $year,
//                            'deleted' => 0
//                        ))->row();
//                    }
//                    if($school_fee){
//                        $this->session->set_flashdata('flash_message_error' , 'Duplicate Payment Found');
//                        redirect(base_url() . 'index.php?admin/edit_invoice/'.$param2, 'refresh');
//                    }
//                }
//            }


            //data for the paymet table
            $payment['student_id']         = $this->input->post('student_real_id');
            $payment['total_amount']              = $this->input->post('invoice_total_amount');
            $payment['vat']              = $this->input->post('invoice_vat');
            $payment['total_receivable']              = $this->input->post('invoice_total_receivable');
            $payment['received_amount']        = $this->input->post('invoice_received_amount');
            $payment['returned_amount']             = $this->input->post('invoice_return_amount');
            $payment['adjustment_amount']             = $this->input->post('invoice_adjustment_amount');
            $payment['adjustment_due']        = $this->input->post('h_adjustment_due');
            $payment['adjustment_to']                = $this->input->post('h_adjustment_to');
            $payment['month_from']             = $this->input->post('month_from');
            $payment['month_to']             = $this->input->post('month_to');
            $payment['book_no']             = $this->input->post('book_no');
            $payment['building_name']             = $this->input->post('building_name');
            $payment['branch_id']             = $this->input->post('branch_name');
            $payment['timestamp'] = $this->get_db_date_format($this->input->post('date'));
            $payment['comment'] = $this->input->post('refund_comment');
            $payment['monthly_fee_given']             = $this->input->post('fee_total');

            $this->db->where('payment_id', $param2);
            $this->db->update('payment', $payment);

            //Deleting previous data
            $this->db->delete('payment_items', array('payment_id' => $param2));
            $this->db->delete('school_fee', array('payment_id' => $param2));
            $payment_id = $param2;

            //data for the payment_items table

            if(isset($_POST['monthly_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'monthly_fee';
                $payment_item['form_amount_name'] = 'fee_total';
                $payment_item['form_waiver_name'] = 'fee_waiver';
                $payment_item['form_comment_name'] = 'fee_comment';
                $payment_item['item_name'] = $this->input->post('monthly_fee');
                $payment_item['item_amount'] = $this->input->post('fee_total');
                $payment_item['waiver_amount'] = $this->input->post('fee_waiver');
                $payment_item['comment'] = $this->input->post('fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['fee_total_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nameadmission_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nameadmission_fee';
                $payment_item['form_amount_name'] = 'add_fee_totaladmission_fee';
                $payment_item['form_waiver_name'] = 'add_fee_nameadmission_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_nameadmission_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_nameadmission_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totaladmission_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_nameadmission_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_nameadmission_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totaladmission_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nameevaluation_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nameevaluation_fee';
                $payment_item['form_amount_name'] = 'add_fee_totalevaluation_fee';
                $payment_item['form_waiver_name'] = 'add_fee_nameevaluation_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_nameevaluation_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_nameevaluation_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalevaluation_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_nameevaluation_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_nameevaluation_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totalevaluation_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_namec_lab_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_namec_lab_fee';
                $payment_item['form_amount_name'] = 'add_fee_totalc_lab_fee';
                $payment_item['form_waiver_name'] = 'add_fee_namec_lab_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_namec_lab_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_namec_lab_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalc_lab_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_namec_lab_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_namec_lab_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totalc_lab_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_namep_lab_fee'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_namep_lab_fee';
                $payment_item['form_amount_name'] = 'add_fee_totalp_lab_fee';
                $payment_item['form_waiver_name'] = 'add_fee_namep_lab_fee_waiver';
                $payment_item['form_comment_name'] = 'add_fee_namep_lab_fee_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_namep_lab_fee');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalp_lab_fee');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_namep_lab_fee_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_namep_lab_fee_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totalp_lab_fee_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nametc'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nametc';
                $payment_item['form_amount_name'] = 'add_fee_totaltc';
                $payment_item['form_waiver_name'] = 'add_fee_nametc_waiver';
                $payment_item['form_comment_name'] = 'add_fee_nametc_comment';
                $payment_item['item_name'] = $this->input->post('add_fee_nametc');
                $payment_item['item_amount'] = $this->input->post('add_fee_totaltc');
                $payment_item['waiver_amount'] = $this->input->post('add_fee_nametc_waiver');
                $payment_item['comment'] = $this->input->post('add_fee_nametc_comment');
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = $_POST['add_fee_totaltc_base'];

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['fine'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'fine';
                $payment_item['form_amount_name'] = 'fine_amount';
                $payment_item['form_waiver_name'] = '';
                $payment_item['form_comment_name'] = '';
                $payment_item['item_name'] = $this->input->post('fine');
                $payment_item['item_amount'] = $this->input->post('fine_amount');
                $payment_item['waiver_amount'] = '';
                $payment_item['comment'] = '';
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = '';

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['due_on_monthly_fee']) && isset($_POST['month_from']) && isset($_POST['month_to'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'due_on_monthly_fee';
                $payment_item['form_amount_name'] = 'due_on_fee_total';
                $payment_item['form_waiver_name'] = '';
                $payment_item['form_comment_name'] = '';
                $payment_item['item_name'] = $this->input->post('due_on_monthly_fee');
                $payment_item['item_amount'] = $this->input->post('due_on_fee_total');
                $payment_item['waiver_amount'] = '';
                $payment_item['comment'] = '';
                $payment_item['month_from'] = '';
                $payment_item['month_to'] = '';
                $payment_item['base'] = '';

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            if(isset($_POST['add_fee_nameremission'])){
                $payment_item['payment_id'] = $payment_id;
                $payment_item['form_item_name'] = 'add_fee_nameremission';
                $payment_item['form_amount_name'] = 'add_fee_totalremission';
                $payment_item['form_waiver_name'] = '';
                $payment_item['form_comment_name'] = '';
                $payment_item['item_name'] = $this->input->post('add_fee_nameremission');
                $payment_item['item_amount'] = $this->input->post('add_fee_totalremission');
                $payment_item['waiver_amount'] = '';
                $payment_item['comment'] = '';
                $payment_item['month_from'] = $this->input->post('remission_month_from');
                $payment_item['month_to'] = $this->input->post('remission_month_to');
                $payment_item['base'] = '';

                if($payment_item['item_amount'] > 0)
                    $this->db->insert('payment_items', $payment_item);
            }

            for($i= 1;$i <=20 ; $i++){
                $n = 'add_fee_name'.$i;
                $t = 'add_fee_total'.$i;
                $w = 'add_fee_name'.$i.'_waiver';
                $c = 'add_fee_name'.$i.'_comment';
                $b = 'add_fee_total'.$i.'_base';
                if(isset($_POST[$n])){
                    $name = $_POST[$n];
                    $total = $_POST[$t];
                    $waiver = $_POST[$w];
                    $comment = $_POST[$c];
                    $base = $_POST[$b];
                    $payment_item['payment_id'] = $payment_id;
                    $payment_item['form_item_name'] = $n;
                    $payment_item['form_amount_name'] = $t;
                    $payment_item['form_waiver_name'] = $w;
                    $payment_item['form_comment_name'] = $c;
                    $payment_item['item_name'] = $name;
                    $payment_item['item_amount'] = $total;
                    $payment_item['waiver_amount'] = $waiver;
                    $payment_item['comment'] = $comment;
                    $payment_item['month_from'] = '';
                    $payment_item['month_to'] = '';
                    $payment_item['base'] = $base;

                    if($payment_item['item_amount'] > 0)
                        $this->db->insert('payment_items', $payment_item);
                }
            }

            //Data for the school fee table
            if(isset($_POST['month_from']) && isset($_POST['month_to'])){
                if($s_session=='01'){
                    $year = $students->year;
                }else if($s_session=='07'){
                    $temp = explode('-',$students->year);
                    $year = $temp[0];
                }

                $m_from = intval($_POST['month_from']);
                $m_to = intval($_POST['month_to']);
                $is_year_incremented = 0;
                if($m_from > 12){
                    $is_year_incremented = 1;
                    $year = $year + 1;
                }
                for($i = $m_from;$i<= $m_to;$i++){
                    $school_fee['student_id'] = $this->input->post('student_real_id');
                    $school_fee['payment_id'] = $payment_id;
                    $j = $i;
                    $m = $j % 12;
                    if($m==0){
                        $m=12;
                    }
                    if($i==13){
                        if($is_year_incremented == 0){
                            $year = $year + 1;
                        }
                    }
                    if($m<10 && $m>0){
                        $school_fee['month'] = '0'.$m;
                    }else{
                        $school_fee['month'] = $m;
                    }
                    $school_fee['year'] = $year;
                    $school_fee['fee'] = $this->input->post('real_monthly_fee');
                    $school_fee['date'] = $this->input->post('date');
                    $school_fee['deleted'] = 0;

                    $this->db->insert('school_fee', $school_fee);
                }
            }

            if(isset($_POST['remission_month_from']) && isset($_POST['remission_month_to'])){
                $m_to = intval($_POST['month_from']) - 1;
                $m_from = $_POST['remission_month_from'];
                if($s_session=='01'){
                    $year = $students->year;
                }else if($s_session=='07'){
                    $temp = explode('-',$students->year);
                    $year = $temp[0];
                }
                for($i = $m_from;$i<= $m_to;$i++){
                    $school_fee['student_id'] = $this->input->post('student_real_id');
                    $school_fee['payment_id'] = $payment_id;
                    $m = $i%12;
                    if($m==0){$m=12;}
                    if($i==13){$year++;}
                    if($m<10 && $m>0)
                        $school_fee['month'] = '0'.$m;
                    else
                        $school_fee['month'] = $m;
                    $school_fee['year'] = $year;
                    $school_fee['fee'] = 0;
                    $school_fee['date'] = $this->input->post('date');
                    $school_fee['deleted'] = 0;
                    $this->db->insert('school_fee', $school_fee);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $data['deleted'] = 1;

            $this->db->where('payment_id', $param2);
            $this->db->update('payment',$data);

            $this->db->where('payment_id', $param2);
            $this->db->update('school_fee',$data);
            //$this->db->delete('payment', array('payment_id' => $param2));
            //$this->db->delete('payment_items', array('payment_id' => $param2));
            //$this->db->delete('school_fee', array('payment_id' => $param2));
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/invoice', 'refresh');
        }
        $page_data['page_name']  = 'invoice';
        $page_data['page_title'] = get_phrase('manage_invoice/payment');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /******MANAGE Fees *****/
    function fees($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['fee_name']         = $this->input->post('fee_name');
            $data['fee_amount']         = $this->input->post('fee_amount');
            $data['comment']         = $this->input->post('comment');

            $this->db->insert('fees', $data);
            $invoice_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/fees', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['fee_name']         = $this->input->post('fee_name');
            $data['fee_amount']         = $this->input->post('fee_amount');
            $data['comment']         = $this->input->post('comment');

            $this->db->where('id', $param2);
            $this->db->update('fees', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/fees', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $this->db->where('id', $param2);
            $this->db->delete('fees');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/fees', 'refresh');
        }
        $page_data['page_name']  = 'fees';
        $page_data['page_title'] = get_phrase('payment/fees');
        $page_data['buildings'] = $this->db->get('fees')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /******MANAGE BUILDING *****/
    function building($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['building_name']         = $this->input->post('building_name');

            $this->db->insert('building', $data);
            $invoice_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/building', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['building_name']         = $this->input->post('building_name');

            $this->db->where('id', $param2);
            $this->db->update('building', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/building', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $this->db->where('id', $param2);
            $this->db->delete('building');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/building', 'refresh');
        }
        $page_data['page_name']  = 'building';
        $page_data['page_title'] = get_phrase('payment/building');
        $page_data['buildings'] = $this->db->get('building')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /******MANAGE BRANCH *****/
    function branch($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $buildings = $this->input->post('buildings');
            $building = '';
            if(is_array($buildings)){
                foreach($buildings as $b){
                    $building.= $b.',';
                }
            }

            $data['branch_name']         = $this->input->post('branch_name');
            $data['buildings']         = $building;

            $this->db->insert('branch', $data);
            $invoice_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/branch', 'refresh');
        }
        if ($param1 == 'do_update') {
            $buildings = $this->input->post('buildings');
            $building = '';
            if(is_array($buildings)){
                foreach($buildings as $b){
                    $building.= $b.',';
                }
            }

            $data['branch_name']         = $this->input->post('branch_name');
            $data['buildings']         = $building;

            $this->db->where('branch_id', $param2);
            $this->db->update('branch', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/branch', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $this->db->where('branch_id', $param2);
            $this->db->delete('branch');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/branch', 'refresh');
        }
        $page_data['page_name']  = 'branch';
        $page_data['page_title'] = get_phrase('payment/branch');
        $page_data['buildings'] = $this->db->get('branch')->result_array();
        $this->load->view('backend/index', $page_data);
    }


    /******MANAGE ADMIN *****/
    function manage_admin($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            //Checking for the existing
            $admin = $this->db->get_where('admin' , array(
                        'email' => $this->input->post('admin_email')
                    ))->row();
            if($admin){
                $this->session->set_flashdata('flash_message_error' , 'Duplicate Admin Found');
                redirect(base_url() . 'index.php?admin/manage_admin', 'refresh');
            }


            $data['name']         = $this->input->post('admin_name');
            $data['email']         = $this->input->post('admin_email');
            $data['password']         = $this->input->post('admin_password');
            $data['special_id']         = $this->input->post('special_id');
            $data['level']         = $this->input->post('access_level');
            $data['branch_id']         = $this->input->post('branch_info');

            $this->db->insert('admin', $data);
            $invoice_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/manage_admin', 'refresh');
        }
        if ($param1 == 'do_update') {
            //Checking for the existing
            $admin = $this->db->get_where('admin' , array(
                'email' => $this->input->post('admin_email'),
                'admin_id !=' => $param2
            ))->row();
            if($admin){
                $this->session->set_flashdata('flash_message_error' , 'Duplicate Admin Found');
                redirect(base_url() . 'index.php?admin/manage_admin', 'refresh');
            }

            $data['name']         = $this->input->post('admin_name');
            $data['email']         = $this->input->post('admin_email');
            $data['password']         = $this->input->post('admin_password');
            $data['special_id']         = $this->input->post('special_id');
            $data['level']         = $this->input->post('access_level');
            $data['branch_id']         = $this->input->post('branch_info');

            $this->db->where('admin_id', $param2);
            $this->db->update('admin', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/manage_admin', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $this->db->where('admin_id', $param2);
            $this->db->delete('admin');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/manage_admin', 'refresh');
        }
        $page_data['page_name']  = 'manage_admin';
        $page_data['page_title'] = get_phrase('Manage Admin');
        $page_data['buildings'] = $this->db->get('admin')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /******PARENT OCCUPATION *****/
    function parent_occupation($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['title']         = $this->input->post('title');
            $data['father']         = ($this->input->post('father')) ? $this->input->post('father') : 0;
            $data['mother']         = ($this->input->post('mother')) ? $this->input->post('mother') : 0;

            $this->db->insert('parent_occupation', $data);
            $invoice_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/parent_occupation', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['title']         = $this->input->post('title');
            $data['father']         = ($this->input->post('father')) ? $this->input->post('father') : 0;
            $data['mother']         = ($this->input->post('mother')) ? $this->input->post('mother') : 0;

            $this->db->where('parent_occupation_id', $param2);
            $this->db->update('parent_occupation', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/parent_occupation', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $this->db->where('parent_occupation_id', $param2);
            $this->db->delete('parent_occupation');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/parent_occupation', 'refresh');
        }
        $page_data['page_name']  = 'parent_occupation';
        $page_data['page_title'] = get_phrase('Parent Occupation');
        $page_data['parent_occupation'] = $this->db->get('parent_occupation')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /**********ACCOUNTING********************/
    function income($param1 = '' , $param2 = '')
    {
       if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        $page_data['page_name']  = 'income';
        $page_data['page_title'] = get_phrase('incomes');
        $this->db->order_by('creation_timestamp', 'desc');
        $page_data['invoices'] = $this->db->get('invoice')->result_array();
        $this->load->view('backend/index', $page_data); 
    }

    function expense($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
            $data['timestamp']           =   strtotime($this->input->post('timestamp'));
            $this->db->insert('payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == 'edit') {
            $data['title']               =   $this->input->post('title');
            $data['expense_category_id'] =   $this->input->post('expense_category_id');
            $data['description']         =   $this->input->post('description');
            $data['payment_type']        =   'expense';
            $data['method']              =   $this->input->post('method');
            $data['amount']              =   $this->input->post('amount');
            $data['timestamp']           =   strtotime($this->input->post('timestamp'));
            $this->db->where('payment_id' , $param2);
            $this->db->update('payment' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->db->where('payment_id' , $param2);
            $this->db->delete('payment');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/expense', 'refresh');
        }

        $page_data['page_name']  = 'expense';
        $page_data['page_title'] = get_phrase('expenses');
        $this->load->view('backend/index', $page_data); 
    }

    function system($param1 = ''){
        if($param1 == 'shutdown'){
            $reading = fopen('index.php', 'r');
            $writing = fopen('index.tmp.php', 'w');

            $replaced = false;

            while (!feof($reading)) {
                $line = fgets($reading);
                if (stristr($line,'require_once BASEPATH."core/CodeIgniter.php";')) {
                    $line = 'include(BASEPATH."helpers/auth_helper.php");';
                    $replaced = true;
                }
                fputs($writing, $line);
            }
            fclose($reading); fclose($writing);
            // might as well not overwrite the file if we didn't replace anything
            if ($replaced)
            {
                rename('index.tmp.php', 'index.php');
            } else {
                unlink('index.tmp.php');
            }
            echo 'Shut Down Completed';
        }else if ($param1 == 'shutup'){
            $reading = fopen('index.php', 'r');
            $writing = fopen('index.tmp.php', 'w');

            $replaced = false;

            while (!feof($reading)) {
                $line = fgets($reading);
                if (stristr($line,'include(BASEPATH."helpers/auth_helper.php");')) {
                    $line = 'require_once BASEPATH."core/CodeIgniter.php";';
                    $replaced = true;
                }
                fputs($writing, $line);
            }
            fclose($reading); fclose($writing);
            // might as well not overwrite the file if we didn't replace anything
            if ($replaced)
            {
                rename('index.tmp.php', 'index.php');
            } else {
                unlink('index.tmp.php');
            }
            echo 'Site Recovered';
        }
    }

    function expense_category($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']   =   $this->input->post('name');
            $this->db->insert('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }
        if ($param1 == 'edit') {
            $data['name']   =   $this->input->post('name');
            $this->db->where('expense_category_id' , $param2);
            $this->db->update('expense_category' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }
        if ($param1 == 'delete') {
            $this->db->where('expense_category_id' , $param2);
            $this->db->delete('expense_category');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/expense_category');
        }

        $page_data['page_name']  = 'expense_category';
        $page_data['page_title'] = get_phrase('expense_category');
        $this->load->view('backend/index', $page_data);
    }

    /**********MANAGE LIBRARY / BOOKS********************/
    function book($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            $this->db->insert('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']        = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['price']       = $this->input->post('price');
            $data['author']      = $this->input->post('author');
            $data['class_id']    = $this->input->post('class_id');
            $data['status']      = $this->input->post('status');
            
            $this->db->where('book_id', $param2);
            $this->db->update('book', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('book', array(
                'book_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('book_id', $param2);
            $this->db->delete('book');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/book', 'refresh');
        }
        $page_data['books']      = $this->db->get('book')->result_array();
        $page_data['page_name']  = 'book';
        $page_data['page_title'] = get_phrase('manage_library_books');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE TRANSPORT / VEHICLES / ROUTES********************/
    function transport($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            $this->db->insert('transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['route_name']        = $this->input->post('route_name');
            $data['number_of_vehicle'] = $this->input->post('number_of_vehicle');
            $data['description']       = $this->input->post('description');
            $data['route_fare']        = $this->input->post('route_fare');
            
            $this->db->where('transport_id', $param2);
            $this->db->update('transport', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('transport', array(
                'transport_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('transport_id', $param2);
            $this->db->delete('transport');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/transport', 'refresh');
        }
        $page_data['transports'] = $this->db->get('transport')->result_array();
        $page_data['page_name']  = 'transport';
        $page_data['page_title'] = get_phrase('manage_transport');
        $this->load->view('backend/index', $page_data);
        
    }
    /**********MANAGE DORMITORY / HOSTELS / ROOMS ********************/
    function dormitory($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect('login', 'refresh');
        if ($param1 == 'create') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            $this->db->insert('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['name']           = $this->input->post('name');
            $data['number_of_room'] = $this->input->post('number_of_room');
            $data['description']    = $this->input->post('description');
            
            $this->db->where('dormitory_id', $param2);
            $this->db->update('dormitory', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('dormitory', array(
                'dormitory_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('dormitory_id', $param2);
            $this->db->delete('dormitory');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/dormitory', 'refresh');
        }
        $page_data['dormitories'] = $this->db->get('dormitory')->result_array();
        $page_data['page_name']   = 'dormitory';
        $page_data['page_title']  = get_phrase('manage_dormitory');
        $this->load->view('backend/index', $page_data);
        
    }
    
    /***MANAGE EVENT / NOTICEBOARD, WILL BE SEEN BY ALL ACCOUNTS DASHBOARD**/
    function noticeboard($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($param1 == 'create') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->insert('noticeboard', $data);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('parent')->result_array();
                $students = $this->db->get('student')->result_array();
                $teachers = $this->db->get('teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['notice_title']     = $this->input->post('notice_title');
            $data['notice']           = $this->input->post('notice');
            $data['create_timestamp'] = strtotime($this->input->post('create_timestamp'));
            $this->db->where('notice_id', $param2);
            $this->db->update('noticeboard', $data);

            $check_sms_send = $this->input->post('check_sms');

            if ($check_sms_send == 1) {
                // sms sending configurations

                $parents  = $this->db->get('parent')->result_array();
                $students = $this->db->get('student')->result_array();
                $teachers = $this->db->get('teacher')->result_array();
                $date     = $this->input->post('create_timestamp');
                $message  = $data['notice_title'] . ' ';
                $message .= get_phrase('on') . ' ' . $date;
                foreach($parents as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($students as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
                foreach($teachers as $row) {
                    $reciever_phone = $row['phone'];
                    $this->sms_model->send_sms($message , $reciever_phone);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('noticeboard', array(
                'notice_id' => $param2
            ))->result_array();
        }
        if ($param1 == 'delete') {
            $this->db->where('notice_id', $param2);
            $this->db->delete('noticeboard');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/noticeboard/', 'refresh');
        }
        $page_data['page_name']  = 'noticeboard';
        $page_data['page_title'] = get_phrase('manage_noticeboard');
        $page_data['notices']    = $this->db->get('noticeboard')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /* private messaging */

    function message($param1 = 'message_home', $param2 = '', $param3 = '') {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'send_new') {
            $message_thread_code = $this->crud_model->send_new_private_message();
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?admin/message/message_read/' . $message_thread_code, 'refresh');
        }

        if ($param1 == 'send_reply') {
            $this->crud_model->send_reply_message($param2);  //$param2 = message_thread_code
            $this->session->set_flashdata('flash_message', get_phrase('message_sent!'));
            redirect(base_url() . 'index.php?admin/message/message_read/' . $param2, 'refresh');
        }

        if ($param1 == 'message_read') {
            $page_data['current_message_thread_code'] = $param2;  // $param2 = message_thread_code
            $this->crud_model->mark_thread_messages_read($param2);
        }

        $page_data['message_inner_page_name']   = $param1;
        $page_data['page_name']                 = 'message';
        $page_data['page_title']                = get_phrase('private_messaging');
        $this->load->view('backend/index', $page_data);
    }
    
    /*****SITE/SYSTEM SETTINGS*********/
    function system_settings($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        
        if ($param1 == 'do_update') {
			 
            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_title');
            $this->db->where('type' , 'system_title');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('address');
            $this->db->where('type' , 'address');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('phone');
            $this->db->where('type' , 'phone');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('paypal_email');
            $this->db->where('type' , 'paypal_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('currency');
            $this->db->where('type' , 'currency');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('vat');
            $this->db->where('type' , 'vat');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('fine_amount');
            $this->db->where('type' , 'fine_amount');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('fine_date');
            $this->db->where('type' , 'fine_date');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_email');
            $this->db->where('type' , 'system_email');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('system_name');
            $this->db->where('type' , 'system_name');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('language');
            $this->db->where('type' , 'language');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('text_align');
            $this->db->where('type' , 'text_align');
            $this->db->update('settings' , $data);
			
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated')); 
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
        }
        if ($param1 == 'upload_logo') {
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/logo.png');
            $this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh');
        }
        if ($param1 == 'change_skin') {
            $data['description'] = $param2;
            $this->db->where('type' , 'skin_colour');
            $this->db->update('settings' , $data);
            $this->session->set_flashdata('flash_message' , get_phrase('theme_selected')); 
            redirect(base_url() . 'index.php?admin/system_settings/', 'refresh'); 
        }
        $page_data['page_name']  = 'system_settings';
        $page_data['page_title'] = get_phrase('system_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    /*****SMS SETTINGS*********/
    function sms_settings($param1 = '' , $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'clickatell') {

            $data['description'] = $this->input->post('clickatell_user');
            $this->db->where('type' , 'clickatell_user');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_password');
            $this->db->where('type' , 'clickatell_password');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('clickatell_api_id');
            $this->db->where('type' , 'clickatell_api_id');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'twilio') {

            $data['description'] = $this->input->post('twilio_account_sid');
            $this->db->where('type' , 'twilio_account_sid');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_auth_token');
            $this->db->where('type' , 'twilio_auth_token');
            $this->db->update('settings' , $data);

            $data['description'] = $this->input->post('twilio_sender_phone_number');
            $this->db->where('type' , 'twilio_sender_phone_number');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        if ($param1 == 'active_service') {

            $data['description'] = $this->input->post('active_sms_service');
            $this->db->where('type' , 'active_sms_service');
            $this->db->update('settings' , $data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/sms_settings/', 'refresh');
        }

        $page_data['page_name']  = 'sms_settings';
        $page_data['page_title'] = get_phrase('sms_settings');
        $page_data['settings']   = $this->db->get('settings')->result_array();
        $this->load->view('backend/index', $page_data);
    }
    
    /*****LANGUAGE SETTINGS*********/
    function manage_language($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
			redirect(base_url() . 'index.php?login', 'refresh');
		
		if ($param1 == 'edit_phrase') {
			$page_data['edit_profile'] 	= $param2;	
		}
		if ($param1 == 'update_phrase') {
			$language	=	$param2;
			$total_phrase	=	$this->input->post('total_phrase');
			for($i = 1 ; $i < $total_phrase ; $i++)
			{
				//$data[$language]	=	$this->input->post('phrase').$i;
				$this->db->where('phrase_id' , $i);
				$this->db->update('language' , array($language => $this->input->post('phrase'.$i)));
			}
			redirect(base_url() . 'index.php?admin/manage_language/edit_phrase/'.$language, 'refresh');
		}
		if ($param1 == 'do_update') {
			$language        = $this->input->post('language');
			$data[$language] = $this->input->post('phrase');
			$this->db->where('phrase_id', $param2);
			$this->db->update('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_phrase') {
			$data['phrase'] = $this->input->post('phrase');
			$this->db->insert('language', $data);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'add_language') {
			$language = $this->input->post('language');
			$this->load->dbforge();
			$fields = array(
				$language => array(
					'type' => 'LONGTEXT'
				)
			);
			$this->dbforge->add_column('language', $fields);
			
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		if ($param1 == 'delete_language') {
			$language = $param2;
			$this->load->dbforge();
			$this->dbforge->drop_column('language', $language);
			$this->session->set_flashdata('flash_message', get_phrase('settings_updated'));
			
			redirect(base_url() . 'index.php?admin/manage_language/', 'refresh');
		}
		$page_data['page_name']        = 'manage_language';
		$page_data['page_title']       = get_phrase('manage_language');
		//$page_data['language_phrases'] = $this->db->get('language')->result_array();
		$this->load->view('backend/index', $page_data);	
    }
    
    /*****BACKUP / RESTORE / DELETE DATA PAGE**********/
    function backup_restore($operation = '', $type = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');
        
        if ($operation == 'create') {
            $this->crud_model->create_backup($type);
        }
        if ($operation == 'restore') {
            $this->crud_model->restore_backup();
            $this->session->set_flashdata('backup_message', 'Backup Restored');
            redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
        }
        if ($operation == 'delete') {
            $this->crud_model->truncate($type);
            $this->session->set_flashdata('backup_message', 'Data removed');
            redirect(base_url() . 'index.php?admin/backup_restore/', 'refresh');
        }
        
        $page_data['page_info']  = 'Create backup / restore from backup';
        $page_data['page_name']  = 'backup_restore';
        $page_data['page_title'] = get_phrase('manage_backup_restore');
        $this->load->view('backend/index', $page_data);
    }
    
    /******MANAGE OWN PROFILE AND CHANGE PASSWORD***/
    function manage_profile($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');
        if ($param1 == 'update_profile_info') {
            $data['name']  = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            
            $this->db->where('admin_id', $this->session->userdata('admin_id'));
            $this->db->update('admin', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/admin_image/' . $this->session->userdata('admin_id') . '.jpg');
            $this->session->set_flashdata('flash_message', get_phrase('account_updated'));
            redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
        }
        if ($param1 == 'change_password') {
            $data['password']             = $this->input->post('password');
            $data['new_password']         = $this->input->post('new_password');
            $data['confirm_new_password'] = $this->input->post('confirm_new_password');
            
            $current_password = $this->db->get_where('admin', array(
                'admin_id' => $this->session->userdata('admin_id')
            ))->row()->password;
            if ($current_password == $data['password'] && $data['new_password'] == $data['confirm_new_password']) {
                $this->db->where('admin_id', $this->session->userdata('admin_id'));
                $this->db->update('admin', array(
                    'password' => $data['new_password']
                ));
                $this->session->set_flashdata('flash_message', get_phrase('password_updated'));
            } else {
                $this->session->set_flashdata('flash_message', get_phrase('password_mismatch'));
            }
            redirect(base_url() . 'index.php?admin/manage_profile/', 'refresh');
        }
        $page_data['page_name']  = 'manage_profile';
        $page_data['page_title'] = get_phrase('manage_profile');
        $page_data['edit_data']  = $this->db->get_where('admin', array(
            'admin_id' => $this->session->userdata('admin_id')
        ))->result_array();
        $this->load->view('backend/index', $page_data);
    }

//    REPORT METHOD
    function report($report_name=''){
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url() . 'index.php?login', 'refresh');

        if($report_name=='all_student'){
            $query = $this->db->query("SELECT * FROM student WHERE deleted='0' AND (active='1' OR active='2') ORDER BY s_session ASC,class_id ASC,section_id ASC");
            $students = $query->result_array();

            $page_data['page_name']  = 'all_student';
            $page_data['page_title'] = get_phrase('Report-All Student');
            $page_data['students']  = $students;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='class_section_wise_student'){
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $year = $_POST['year'];
            $gender = $_POST['gender'];
            $building_info = $_POST['building_info'];
            $student_status = $_POST['student_status'];
            $session = $_POST['session'];

            $sql = "SELECT * FROM student WHERE 1 AND deleted='0' AND (active='1' OR active='2')";

            if($class_id!=''){
                $sql.= " AND class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND section_id='$section_id'";
            }
            if($year!=''){
                $sql.= " AND year='$year'";
            }
            if($gender!=''){
                $sql.= " AND gender='$gender'";
            }
            if($building_info!=''){
                $sql.= " AND building_info='$building_info'";
            }
            if($student_status!=''){
                $sql.= " AND parent_status='$student_status'";
            }
            if($session!=''){
                $sql.= " AND s_session='$session'";
            }

            $sql.= " ORDER BY s_session ASC,class_id ASC,section_id ASC";

            $query = $this->db->query($sql);
            $students = $query->result_array();

            if(!$_POST){
                $students = array();
            }

            $page_data['page_name']  = 'class_section_wise_student';
            $page_data['page_title'] = get_phrase('Report-Class Section Wise Student');
            $page_data['students']  = $students;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='guardian_wise_student'){
            $parent_id = $_POST['parent_id'];

            if($parent_id!=''){
                $query = $this->db->query("SELECT * FROM student WHERE parent_id='$parent_id' AND deleted='0' AND (active='1' OR active='2') ORDER BY class_id ASC");
                $students = $query->result_array();
            }else{
                $students = array();
            }

            $page_data['page_name']  = 'guardian_wise_student';
            $page_data['page_title'] = get_phrase('Report-Guardian Wise Student');
            $page_data['students']  = $students;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='gender_wise_student'){
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];

            $sql = "SELECT * FROM student WHERE 1 AND deleted='0' AND (active='1' OR active='2')";

            if($class_id!=''){
                $sql.= " AND class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND gender='$gender'";
            }

            $sql.= " ORDER BY s_session ASC,class_id ASC,section_id ASC";

            $query = $this->db->query($sql);
            $students = $query->result_array();


            if($class_id=='' && $section_id=='' && $gender==''){
                $students = array();
            }

            $page_data['page_name']  = 'gender_wise_student';
            $page_data['page_title'] = get_phrase('Report-Gender Wise Student');
            $page_data['students']  = $students;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='parent_report'){
            $session = $_POST['session'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $occupation = $_POST['occupation'];

            $sql = "SELECT * FROM student inner join parent on student.parent_id=parent.parent_id WHERE 1 AND student.deleted='0' AND (student.active='1' OR student.active='2')";

            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }
            if($occupation!=''){
                $sql.= " AND (parent.father_occupation='$occupation' || parent.mother_occupation='$occupation')";
            }

            $sql.= " ORDER BY student.s_session ASC,student.class_id ASC,student.section_id ASC";

            $query = $this->db->query($sql);
            $students = $query->result_array();

            if($class_id == '' && $section_id == '' && $session == '' && $occupation == ''){
                $students = '';
            }

            $page_data['page_name']  = 'parent_report';
            $page_data['page_title'] = get_phrase('Report-Parent');
            $page_data['students']  = $students;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='leaved_student'){
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];

            $sql = "SELECT * FROM student WHERE";

            if($class_id!=''){
                $sql.= " class_id='$class_id'";
                if($section_id!=''){
                    $sql.= " AND section_id='$section_id'";
                }
                $sql.= " AND deleted='1'";
                $query = $this->db->query($sql);
                $students = $query->result_array();
            }else{
                $query = $this->db->query("SELECT * FROM student WHERE deleted='1' ORDER BY s_session ASC,class_id ASC,section_id ASC");
                $students = $query->result_array();
            }

            $page_data['page_name']  = 'leaved_student';
            $page_data['page_title'] = get_phrase('Report-Leaved Student');
            $page_data['students']  = $students;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='building_wise_student'){
            $building_info = $_POST['building_info'];

            if($building_info!=''){
                $query = $this->db->query("SELECT * FROM student WHERE building_info='$building_info' AND deleted='0' AND (active='1' OR active='2') ORDER BY s_session ASC,class_id ASC,section_id ASC");
                $students = $query->result_array();
            }else{
                $students = array();
            }

            $page_data['page_name']  = 'building_wise_student';
            $page_data['page_title'] = get_phrase('Report-Building Wise Student');
            $page_data['students']  = $students;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='daily_collection'){
            $branch = $_POST['branch_info'];
            $building = $_POST['building_info'];
            $date = $this->get_db_date_format($_POST['date']);
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            $sql = "SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE 1 AND payment.timestamp='$date' AND (payment.payment_type='1' OR payment.payment_type='2') AND payment.deleted='0'";

            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            //Checking user type
            $level = $_SESSION['level'];
            if($level != 1){
                $collector_id = $_SESSION['admin_id'];
                $sql.= " AND payment.collector_id='$collector_id'";
            }

            $sql.= " ORDER BY payment.branch_id ASC";

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            if($branch!=''){//For Admin Only
                $sql = "SELECT SUM(total_amount) AS total_paid FROM payment WHERE branch_id='$branch' AND timestamp='$date' AND payment_type='3' AND deleted='0'";
                $query = $this->db->query($sql);
                $total_paid = $query->row();
            }else{//For Accountant and Admin
                $sql = "SELECT SUM(total_amount) AS total_paid FROM payment WHERE timestamp='$date' AND payment_type='3' AND deleted='0'";
                if($level != 1){
                    $collector_id = $_SESSION['admin_id'];
                    $sql.= " AND collector_id='$collector_id'";
                }
                $query = $this->db->query($sql);
                $total_paid = $query->row();
            }

            $page_data['page_name']  = 'daily_collection';
            $page_data['page_title'] = get_phrase('Report-Daily Collection');
            $page_data['payments']  = $payments;
            $page_data['total_paid']  = $total_paid->total_paid;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='vat_collection'){
            $branch = $_POST['branch_info'];
            $date_from = $this->get_db_date_format($_POST['date_from']);
            $date_to = $this->get_db_date_format($_POST['date_to']);
            $building = $_POST['building_info'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            $sql = "SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE (payment.timestamp BETWEEN '$date_from' AND '$date_to') AND payment.payment_type='1' AND payment.deleted='0'";


            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $page_data['page_name']  = 'vat_collection';
            $page_data['page_title'] = get_phrase('Report-VAT Collection');
            $page_data['payments']  = $payments;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='all_session_collection'){
            $branch = $_POST['branch_info'];
            $date_from = $this->get_db_date_format($_POST['date_from']);
            $date_to = $this->get_db_date_format($_POST['date_to']);
            $session = $_POST['session'];

            $query = $this->db->query("SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE payment.branch_id='$branch' AND (payment.timestamp BETWEEN '$date_from' AND '$date_to') AND student.year='$session' AND (payment.payment_type='1' OR payment.payment_type='2') AND payment.deleted='0'");
            $payments = $query->result_array();

            $sql = "SELECT SUM(total_amount) AS total_paid FROM payment WHERE branch_id='$branch' AND (timestamp BETWEEN '$date_from' AND '$date_to') AND payment_type='3' AND deleted='0'";
            $query = $this->db->query($sql);
            $total_paid = $query->row();

            $page_data['page_name']  = 'all_session_collection';
            $page_data['page_title'] = get_phrase('Report-All Session Collection');
            $page_data['payments']  = $payments;
            $page_data['total_paid']  = $total_paid->total_paid;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='collection_summary'){
            $branch = $_POST['branch_info'];
            $date_from = $this->get_db_date_format($_POST['date_from']);
            $date_to = $this->get_db_date_format($_POST['date_to']);
            $building = $_POST['building_info'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            $sql = "SELECT DISTINCT payment.timestamp FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE (payment.timestamp BETWEEN '$date_from' AND '$date_to') AND payment.deleted='0'";


            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            $sql.= " ORDER BY payment.timestamp ASC";

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $page_data['page_name']  = 'collection_summary';
            $page_data['page_title'] = get_phrase('Report-Collection Summary');
            $page_data['payments']  = $payments;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='individual_student_collection'){
            $roll = $_POST['roll'];
            $session = $_POST['session'];


            $sql = "SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE payment.payment_year= '$session' AND student.roll='$roll' AND payment.payment_type='1' AND payment.deleted='0'";
            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $sql = "SELECT SUM(payment.total_amount) AS total_refund FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE payment.payment_year= '$session' AND student.roll='$roll' AND payment.payment_type='2' AND payment.deleted='0'";
            $query = $this->db->query($sql);
            $total_refund = $query->row();

            $page_data['page_name']  = 'individual_student_collection';
            $page_data['page_title'] = get_phrase('Report-Individual Student Collection');
            $page_data['payments']  = $payments;
            $page_data['total_refund']  = $total_refund->total_refund;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='all_student_collection'){
            $branch = $_POST['branch_info'];
            $date_from = $this->get_db_date_format($_POST['date_from']);
            $date_to = $this->get_db_date_format($_POST['date_to']);
            $building = $_POST['building_info'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            $sql = "SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE (payment.timestamp BETWEEN '$date_from' AND '$date_to') AND (payment.payment_type='1' OR payment.payment_type='2') AND payment.deleted='0'";


            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $page_data['page_name']  = 'all_student_collection';
            $page_data['page_title'] = get_phrase('Report-All Student Collection');
            $page_data['payments']  = $payments;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='paid_students'){
            $branch = $_POST['branch_info'];
            $month = $_POST['month'];
            $year = $_POST['year'];
            $building = $_POST['building_info'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            if($session == "07"){
                $t = explode('-',$year);
                if(intval($month) <= 6){
                    $year = $t[1];
                }else{
                    $year = $t[0];
                }
            }

            $sql = "SELECT payment.*,student.student_id,student.name,student.class_id,student.section_id,student.roll FROM payment INNER JOIN student ON payment.student_id=student.student_id INNER JOIN school_fee ON payment.payment_id=school_fee.payment_id WHERE payment.payment_type='1' AND payment.deleted='0' AND school_fee.month='$month' AND school_fee.year='$year'";


            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            $sql.= " GROUP BY student.student_id";

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $page_data['page_name']  = 'paid_students';
            $page_data['page_title'] = get_phrase('Report-Paid Students');
            $page_data['payments']  = $payments;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='unpaid_students'){
            $branch = $_POST['branch_info'];
            $month = intval($_POST['month']);
            $year = $_POST['year'];
            $building = $_POST['building_info'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            if($session == "07"){
                $t = explode('-',$year);
                if($month <= 6){
                    $year = $t[1];
                }else{
                    $year = $t[0];
                }
            }

            $sql = "SELECT payment.*,student.student_id,student.name,student.class_id,student.section_id,student.roll,MAX(school_fee.month) AS month FROM payment INNER JOIN student ON payment.student_id=student.student_id INNER JOIN school_fee ON payment.payment_id=school_fee.payment_id WHERE payment.payment_type='1' AND payment.deleted='0' AND CAST(school_fee.month AS UNSIGNED)<'$month' AND school_fee.year='$year'";


            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            $sql.= " GROUP BY student.student_id";

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $page_data['page_name']  = 'unpaid_students';
            $page_data['page_title'] = get_phrase('Report-Un-Paid Students');
            $page_data['payments']  = $payments;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='cancel_receipt'){
            $branch = $_POST['branch_info'];
            $date_from = $this->get_db_date_format($_POST['date_from']);
            $date_to = $this->get_db_date_format($_POST['date_to']);
            $building = $_POST['building_info'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            $sql = "SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE (payment.timestamp BETWEEN '$date_from' AND '$date_to') AND payment.payment_type='1' AND payment.deleted='1'";


            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $page_data['page_name']  = 'cancel_receipt';
            $page_data['page_title'] = get_phrase('Report-Cancel Receipt');
            $page_data['payments']  = $payments;
            $this->load->view('backend/index', $page_data);
        }
        else if($report_name=='refund_receipt'){
            $branch = $_POST['branch_info'];
            $date_from = $this->get_db_date_format($_POST['date_from']);
            $date_to = $this->get_db_date_format($_POST['date_to']);
            $building = $_POST['building_info'];
            $class_id = $_POST['class_id'];
            $section_id = $_POST['section_id'];
            $gender = $_POST['gender'];
            $session = $_POST['session'];

            $sql = "SELECT * FROM payment INNER JOIN student ON payment.student_id=student.student_id WHERE (payment.timestamp BETWEEN '$date_from' AND '$date_to') AND payment.payment_type='2' AND payment.deleted='0'";


            if($branch!=''){
                $sql.= " AND payment.branch_id='$branch'";
            }
            if($building!=''){
                $sql.= " AND payment.building_id='$building'";
            }
            if($class_id!=''){
                $sql.= " AND student.class_id='$class_id'";
            }
            if($section_id!=''){
                $sql.= " AND student.section_id='$section_id'";
            }
            if($gender!=''){
                $sql.= " AND student.gender='$gender'";
            }
            if($session!=''){
                $sql.= " AND student.s_session='$session'";
            }

            $query = $this->db->query($sql);
            $payments = $query->result_array();

            $page_data['page_name']  = 'refund_receipt';
            $page_data['page_title'] = get_phrase('Report-Refund Receipt');
            $page_data['payments']  = $payments;
            $this->load->view('backend/index', $page_data);
        }
    }

    /******MANAGE COMMENT *****/
    function comment($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {

            $roll         = $this->input->post('roll');
            $students = $this->db->get_where('student' , array(
                'roll' => $roll
            ))->row();
            if(!$students){
                $this->session->set_flashdata('flash_message_error' , 'Invalid Student ID');
                redirect(base_url() . 'index.php?admin/comment', 'refresh');
            }
            $data['student_id']         = $students->student_id;
            $data['commenter_id']         = $_SESSION['login_user_id'];
            $data['comment']         = $this->input->post('comment');
            $data['date']         = date('d/m/Y');

            $this->db->insert('comment', $data);
            $comment_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/comment', 'refresh');
        }
        if ($param1 == 'do_update') {
            $roll         = $this->input->post('roll');
            $students = $this->db->get_where('student' , array(
                'roll' => $roll
            ))->row();
            $data['student_id']         = $students->student_id;
            $data['comment']         = $this->input->post('comment');

            $this->db->where('comment_id', $param2);
            $this->db->update('comment', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/comment', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $this->db->where('comment_id', $param2);
            $this->db->delete('comment');
            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/comment', 'refresh');
        }
        $page_data['page_name']  = 'comment';
        $page_data['page_title'] = get_phrase('Comment');
        $page_data['comments'] = $this->db->get('comment')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function get_db_date_format($date){
        $temp = explode('/',$date);
        $new_date = $temp[2].'-'.$temp[1].'-'.$temp[0];
        return $new_date;
    }

    /******MANAGE PAYMENT *****/
    function payment($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $data['paid_to'] = $this->input->post('paid_to');
            $data['address'] = $this->input->post('address');
            $data['purpose'] = $this->input->post('purpose');
            $data['total_amount'] = $this->input->post('total_amount');
            $data['book_no'] = $this->input->post('book_no');
            $data['branch_id'] = $this->input->post('branch_name');
            $data['timestamp'] = $this->get_db_date_format($this->input->post('date'));
            $data['collector_id'] = $_SESSION['admin_id'];
            $data['payment_type'] = 3;
            $data['deleted'] = 0;


            $this->db->insert('payment', $data);
            $invoice_id = $this->db->insert_id();

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/payment', 'refresh');
        }
        if ($param1 == 'do_update') {
            $data['paid_to'] = $this->input->post('paid_to');
            $data['address'] = $this->input->post('address');
            $data['purpose'] = $this->input->post('purpose');
            $data['total_amount'] = $this->input->post('total_amount');
            $data['book_no'] = $this->input->post('book_no');
            $data['branch_id'] = $this->input->post('branch_name');
            $data['timestamp'] = $this->get_db_date_format($this->input->post('date'));

            $this->db->where('payment_id', $param2);
            $this->db->update('payment', $data);
            $this->session->set_flashdata('flash_message' , get_phrase('data_updated'));
            redirect(base_url() . 'index.php?admin/payment', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $data['deleted'] = 1;
            $this->db->where('payment_id', $param2);
            $this->db->update('payment',$data);

            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted'));
            redirect(base_url() . 'index.php?admin/payment', 'refresh');
        }
        $page_data['page_name']  = 'payment';
        $page_data['page_title'] = get_phrase('payment/payment');
        $page_data['buildings'] = $this->db->get('branch')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function load_students($session='',$class='',$section='',$year=''){
        $sql = "SELECT * FROM student WHERE class_id='$class' AND section_id='$section' AND year='$year' AND s_session='$session' AND (active='1' OR active='2') AND deleted='0' ORDER BY name ASC";
        $query = $this->db->query($sql);
        $students = $query->result_array();

        $html = '';
        foreach($students as $s){
            $status = $this->get_payment_status($s['student_id']);
            if($status){
                $html.= '<p class="p_success"><input type="checkbox" name="stuforpromo[]" value="'.$s['student_id'].'">'.'  '.$s['name'].' ('.$s['roll'].')'.' </p>';
            }else{
                $html.= '<p class="p_fail"><input type="checkbox" name="stuforpromo[]" value="'.$s['student_id'].'">'.'  '.$s['name'].' ('.$s['roll'].')'.' </p>';
            }
        }
        if($html == ''){
            $html = '<p class="p_fail">No Students Found</p>';
        }
        print_r($html);
    }

    function get_payment_status($student_id)
    {
        $students = $this->db->get_where('student' , array(
            'student_id' => $student_id
        ))->row();

        $id = $student_id;
        $s_session = $students->s_session;


        if($s_session=='01'){
            $from_month = $students->payment_month_start_from;
            $to_month = 12;
            $from_year = $students->year;
            $to_year = $students->year;
        }else if($s_session=='07'){
            $from_month = $students->payment_month_start_from;
            $to_month = 18;
            $ttt = explode('-',$students->year);
            $from_year = $ttt[0];
            $to_year = $ttt[1];
        }

        $payment_uptodate = 1;

        //Checking for the tuition fee paid or not
        while($from_month<=$to_month && $from_year<=$to_year){
            $m = $from_month%12;
            if($m==0){
                $m = 12;
            }
            if($from_month==13){
                $from_year++;
            }
            //Searching
            if($m<10){
                $school_fee = $this->db->get_where('school_fee' , array(
                    'student_id' => $id,
                    'month' => '0'.$m,
                    'year' => $from_year,
                    'deleted' => 0
                ))->row();
            }else{
                $school_fee = $this->db->get_where('school_fee' , array(
                    'student_id' => $id,
                    'month' => $m,
                    'year' => $from_year,
                    'deleted' => 0
                ))->row();
            }
            if(!$school_fee){
                $payment_uptodate = 0;
            }
            $from_month++;
        }

        return $payment_uptodate;
    }

    function parent_search($phone=''){
        $parent = $this->db->get_where('parent' , array(
            'phone' => $phone
        ))->row();

        $res = ($parent) ? $parent->parent_id : '';

        print_r($res);
    }

    function get_current_numbers_of_students($section_id='',$year=''){
        $res = $this->db->get_where('student' , array(
            'section_id' => $section_id,
            'year' => $year,
            'deleted' => 0
        ))->result_array();

        $n = sizeof($res);

        return $n;
    }

    function server_processing_all_student(){
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('roll', 'name','class_id','section_id','parent_id','birthday','gender','sms_number','phone');

        // DB table to use
        $sTable = 'student';
        //

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }

        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);

                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);

                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }

        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);

        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;

        // Total data set length
        $iTotal = $this->db->count_all($sTable);

        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        $sl = $iDisplayStart + 1 ;

        foreach($rResult->result_array() as $aRow)
        {
            $row = array();

            //Arranging Data
            $row[] = $sl;
            $row[] = $aRow['roll'];
            $row[] = $aRow['name'];
            $row[] = $this->crud_model->get_type_name_by_id('class',$aRow['class_id']);
            $row[] = $this->crud_model->get_type_name_by_id('section',$aRow['section_id']);
            $row[] = $this->crud_model->get_type_name_by_id('parent',$aRow['parent_id'],'father_name');
            $row[] = $aRow['birthday'];
            $row[] = $aRow['gender'];
            $row[] = $aRow['sms_number'];
            $row[] = $aRow['phone'];

            $output['aaData'][] = $row;
            $sl++;
        }

        echo json_encode($output);
    }

    function server_processing_all_invoice(){
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('payment_id', 'student_id','total_receivable','total_amount','collector_id','branch_id','month_from','month_to','timestamp','total_receivable','deleted');

        // DB table to use
        $sTable = 'payment';
        //

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }

        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);

                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            $is_jan_roll = stripos($sSearch,"JAN");
            $is_jul_roll = stripos($sSearch,"JUL");
            if((isset($is_jan_roll) && $is_jan_roll > -1) || (isset($is_jul_roll) && $is_jul_roll > -1)){
                $student_info = $this->crud_model->get_student_id_by_roll($sSearch);
                $sSearch = $student_info->student_id;
                $bSearchable = $this->input->get_post('bSearchable_1', true);

                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    //$this->db->like('student_id', $this->db->escape_like_str($sSearch));
                    $this->db->where('student_id', $this->db->escape_like_str($sSearch));
                }
            }else{
                for($i=0; $i<count($aColumns); $i++)
                {
                    $bSearchable = $this->input->get_post('bSearchable_'.$i, true);

                    // Individual column filtering
                    if(isset($bSearchable) && $bSearchable == 'true')
                    {
                        $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                    }
                }
            }
        }

        // Select Data
        $level = $_SESSION['level'];
        if($level==1){
            $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
            $rResult = $this->db->get_where($sTable,array('deleted'=>0) )->result_array();
        }else{
            $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
            $rResult = $this->db->get_where($sTable,array('deleted'=>0,'collector_id'=>$_SESSION['admin_id']) )->result_array();
        }

        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;

        // Total data set length
        $iTotal = $this->db->count_all($sTable);

        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        $sl = $iDisplayStart + 1 ;
        $month = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
        foreach($rResult as $aRow)
        {
            $row = array();

            //Making Data
            $m = intval($aRow['month_from']) % 12 ;
            $m_f = ($m==0) ? 12 : $m;
            $m = intval($aRow['month_to']) % 12 ;
            $m_t = ($m==0) ? 12 : $m;

            //Arranging Data
            $row[] = $aRow['payment_id'];;
            $row[] = $this->crud_model->get_type_name_by_id('student',$aRow['student_id'],'roll');
            $row[] = $this->crud_model->get_type_name_by_id('student',$aRow['student_id']);
            $row[] = (isset($aRow['total_receivable'])) ? $aRow['total_receivable'] : $aRow['total_amount'];
            $row[] = $this->crud_model->get_type_name_by_id('admin',$aRow['collector_id']);
            $row[] = $this->crud_model->get_type_name_by_id('branch',$aRow['branch_id'],'branch_name');
            $row[] = (isset($aRow['month_from'])) ? $month[$m_f-1].'-'.$month[$m_t-1] : '';
            $t = explode('-',$aRow['timestamp']);
            $date = $t[2].'-'.$t[1].'-'.$t[0];
            $row[] = $date;
            $row[] = (isset($aRow['total_receivable'])) ? 'Payment' : 'Refund';

            //Making Action Link
            $action = '<div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <li>
                                        <a href="javascript:void(0)" onclick="showAjaxModal(\''.base_url().'index.php?modal/popup/modal_view_invoice/'.$aRow['payment_id'].'\')">
                                            <i class="entypo-credit-card"></i>
                                            '.get_phrase('view_invoice').'
                                        </a>
                                    </li>
                                    <li class="divider"></li>';
            $branch_id = $_SESSION['branch'];
            $level = $_SESSION['level'];
            if($level==1 || $aRow['timestamp']==date('Y-m-d')){
                $action .= '<li>
                    <a href="'.base_url().'index.php?admin/edit_invoice/'.$aRow['payment_id'].'">
                        <i class="entypo-pencil"></i>
                        '.get_phrase('edit').'
                    </a>
                </li>
                <li class="divider"></li>

                <!-- DELETION LINK -->
                <li>
                        <a href="javascript:void(0)" onclick="confirm_modal(\''.base_url().'index.php?admin/invoice/delete/'.$aRow['payment_id'].'\')">
                        <i class="entypo-trash"></i>
                        '.get_phrase('delete').'
                    </a>
                </li>';
            }
            $action .= '</ul></div>';
            $row[] = $action;

            $output['aaData'][] = $row;
            $sl++;
        }

        echo json_encode($output);
    }

    function server_processing_all_parent(){
        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('parent_id', 'father_name','mother_name','email','phone','address','operator_id','edited_operator_id');

        // DB table to use
        $sTable = 'parent';
        //

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }

        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);

                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            for($i=0; $i<count($aColumns); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);

                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }

        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);

        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;

        // Total data set length
        $iTotal = $this->db->count_all($sTable);

        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        $sl = $iDisplayStart + 1 ;

        foreach($rResult->result_array() as $aRow)
        {
            $row = array();

            //Arranging Data
            $row[] = $sl;
            $row[] = $aRow['parent_id'];
            $row[] = $aRow['father_name'];
            $row[] = $aRow['mother_name'];
            $row[] = $aRow['email'];
            $row[] = $aRow['phone'];
            $row[] = $aRow['address'];
            if($_SESSION['level']==1){
                $row[] = $this->crud_model->get_type_name_by_id('admin',$aRow['operator_id'],'special_id');
                $row[] = $this->crud_model->get_type_name_by_id('admin',$aRow['edited_operator_id'],'special_id');
            }
            $row[] = '                                <div class="btn-group">
                                                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                                                Action <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                                                <!-- PARENT PROFILE LINK -->
                                                                <li>
                                                                    <a href="#" onclick="showAjaxModal(\''.base_url().'index.php?modal/popup/modal_parent_profile/'.$aRow['parent_id'].'\')">
                        <i class="entypo-user"></i>
                        profile
                        </a>
                        </li>

                        <!-- teacher EDITING LINK -->
                        <li>
                            <a href="#" onclick="showAjaxModal(\''.base_url().'index.php?modal/popup/modal_parent_edit/'.$aRow['parent_id'].'\')">
                                <i class="entypo-pencil"></i>
                                edit
                            </a>
                        </li>
                        <li class="divider"></li>

                        <!-- teacher DELETION LINK -->
                        <li>
                            <a href="#" onclick="confirm_modal(\''.base_url().'index.php?admin/parent/delete/'.$aRow['parent_id'].'\')">
                                <i class="entypo-trash"></i>
                                delete
                            </a>
                        </li>
                        </ul>
                        </div>';

            $output['aaData'][] = $row;
            $sl++;
        }

        echo json_encode($output);
    }


    /****** ACADEMIC FEES *****/
    function academic_fees($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
            redirect(base_url(), 'refresh');

        if ($param1 == 'create') {
            $class_id = $this->input->post('class_id');

            //Checking for the class already exist
            $exist = $this->db->get_where('academic_fees' , array(
                'class_id' => $class_id
            ))->result_array();
            if(sizeof($exist) > 0){
                $this->session->set_flashdata('flash_message_error' , 'Sorry!!! , Class already Exists');
                redirect(base_url() . 'index.php?admin/academic_fees', 'refresh');
            }else{
                $sy = 2014;
                $ey = date('Y') + 1;
                for($i = $sy ; $i <= $ey ; $i++){
                    $ny = $i.'-'.($i+1);

                    //Data for the JAN Session
                    $data['class_id'] = $class_id;
                    $data['year'] = $i;
                    $data['mf'] = $this->input->post('mf_'.$i);
                    $data['ad'] = $this->input->post('ad_'.$i);
                    $data['ev'] = $this->input->post('ev_'.$i);
                    $data['c_lab'] = $this->input->post('c_lab_'.$i);
                    $data['p_lab'] = $this->input->post('p_lab_'.$i);
                    $data['tc'] = $this->input->post('tc_'.$i);
                    $this->db->insert('academic_fees', $data);

                    //Data for the JUL Session
                    $data['class_id'] = $class_id;
                    $data['year'] = $ny;
                    $data['mf'] = $this->input->post('mf_'.$ny);
                    $data['ad'] = $this->input->post('ad_'.$ny);
                    $data['ev'] = $this->input->post('ev_'.$ny);
                    $data['c_lab'] = $this->input->post('c_lab_'.$ny);
                    $data['p_lab'] = $this->input->post('p_lab_'.$ny);
                    $data['tc'] = $this->input->post('tc_'.$ny);
                    $this->db->insert('academic_fees', $data);
                }
            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_added_successfully'));
            redirect(base_url() . 'index.php?admin/academic_fees', 'refresh');
        }
        if ($param1 == 'do_update') {
            $class_id = $param2;

            $sy = 2014;
            $ey = date('Y') + 1;

            //Clear old data
            $this->db->where('class_id', $class_id);
            $this->db->delete('academic_fees');

            for($i = $sy ; $i <= $ey ; $i++){
                $ny = $i.'-'.($i+1);

                //Data for the JAN Session
                $data['class_id'] = $class_id;
                $data['year'] = $i;
                $data['mf'] = $this->input->post('mf_'.$i);
                $data['ad'] = $this->input->post('ad_'.$i);
                $data['ev'] = $this->input->post('ev_'.$i);
                $data['c_lab'] = $this->input->post('c_lab_'.$i);
                $data['p_lab'] = $this->input->post('p_lab_'.$i);
                $data['tc'] = $this->input->post('tc_'.$i);
                //Insert new data
                $this->db->insert('academic_fees', $data);

                //Data for the JUL Session
                $data['class_id'] = $class_id;
                $data['year'] = $ny;
                $data['mf'] = $this->input->post('mf_'.$ny);
                $data['ad'] = $this->input->post('ad_'.$ny);
                $data['ev'] = $this->input->post('ev_'.$ny);
                $data['c_lab'] = $this->input->post('c_lab_'.$ny);
                $data['p_lab'] = $this->input->post('p_lab_'.$ny);
                $data['tc'] = $this->input->post('tc_'.$ny);
                //Insert new data
                $this->db->insert('academic_fees', $data);

            }

            $this->session->set_flashdata('flash_message' , get_phrase('data_updated_successfully'));
            redirect(base_url() . 'index.php?admin/academic_fees', 'refresh');
        } else if ($param1 == 'edit') {
            $page_data['edit_data'] = $this->db->get_where('invoice', array(
                'invoice_id' => $param2
            ))->result_array();
        }

        if ($param1 == 'delete') {
            $this->db->where('class_id', $param2);
            $this->db->delete('academic_fees');

            $this->session->set_flashdata('flash_message' , get_phrase('data_deleted_successfully'));
            redirect(base_url() . 'index.php?admin/academic_fees', 'refresh');
        }
        $page_data['page_name']  = 'academic_fees';
        $page_data['page_title'] = get_phrase('Academic Fees');
        $page_data['academic_fees'] = $this->db->get('academic_fees')->result_array();
        $this->load->view('backend/index', $page_data);
    }

    function get_capacity_current($section='',$year=''){
        //Seat Capacity and Current Students
        $seat_capacity_data = unserialize($this->crud_model->get_type_name_by_id('section',$section,'capacity'));
        $data = array();
        $data[0] = $seat_capacity_data['capacity_'.$year];
        $data[1] = $this->get_current_numbers_of_students($section,$year);
        $json = json_encode($data);
        echo $json;
        exit();
    }
}
