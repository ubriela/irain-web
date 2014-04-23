<?php 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


$config = array(
    'login' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|max_length[128]|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|max_length[128]|xss_clean|callback__authenticate_user'
        )
    ),
    'account' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|min_length[3]|max_length[128]|xss_clean'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|max_length[128]|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|max_length[128]|xss_clean|matches[repeatpw]|callback__user_exists|callback__create_user'
        ),
        array(
            'field' => 'repeatpw',
            'label' => 'Repeat Password',
            'rules' => 'trim|required|max_length[128]|xss_clean'
        )
    ),
    'change_password' => array(
        array(
            'field' => 'oldpassword',
            'label' => 'Old Password',
            'rules' => 'trim|required|max_length[128]|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|max_length[128]|xss_clean|matches[repeatpw]|callback__change_password'
        ),
        array(
            'field' => 'repeatpw',
            'label' => 'Repeat Password',
            'rules' => 'trim|required|max_length[128]|xss_clean'
        )
    ),
    'forgot_password' => array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|max_length[128]|xss_clean|callback__no_email_exists|callback__generate_reset_password'
        )
    ),
    'reset_password' => array(
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|max_length[128]|xss_clean|matches[repeatpw]|callback__reset_password'
        ),
        array(
            'field' => 'repeatpw',
            'label' => 'Repeat Password',
            'rules' => 'trim|required|max_length[128]|xss_clean'
        )
    ),
    'report_location_weather' => array(
        array(
            'field' => 'lat',
            'label' => 'Lat',
            'rules' => 'trim|required|callback_is_number'
        ),
        array(
            'field' => 'lng',
            'label' => 'Lng',
            'rules' => 'trim|required|callback_is_number'
        ),
        array(
            'field' => 'code',
            'label' => 'Code',
            'rules' => 'trim|required|callback_is_number|callback_range_value'
        ),
        array(
            'field' => 'time',
            'label' => 'Time',
            'rules' => 'trim|required|'
        )
    ),
    'report_location' => array(
        array(
            'field' => 'lat',
            'label' => 'Lat',
            'rules' => 'trim|required|callback_is_number'
        ),
        array(
            'field' => 'lng',
            'label' => 'Lng',
            'rules' => 'trim|required|callback_is_number'
        ),
        array(
            'field' => 'datetime',
            'label' => 'Time',
            'rules' => 'trim|required|'
        )
    ),
    'task_request' => array(
        array(
            'field' => 'title',
            'label' => 'Title',
            'rules' => 'trim|required|max_length[100]'
        ),
        array(
            'field' => 'lat',
            'label' => 'Lat',
            'rules' => 'trim|required|callback_is_number'
        ),
        array(
            'field' => 'lng',
            'label' => 'Lng',
            'rules' => 'trim|required|callback_is_number'
        ),
        array(
            'field' => 'request_date',
            'label' => 'Time',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'start_date',
            'label' => 'Startdate',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'end_date',
            'label' => 'Enddate',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'type',
            'label' => 'Type',
            'rules' => 'trim|required|callback_is_number'
        ),
        array(
            'field' => 'radius',
            'label' => 'Radius',
            'rules' => 'trim|required|callback_is_number'
        ),
    ),
    'task_response' => array(
        array(
            'field' => 'task_id',
            'label' => 'Taksid',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'response_code',
            'label' => 'Responsecode',
            'rules' => 'trim|required|callback_is_number|callback_range_value'
        ),
        array(
            'field' => 'response_date',
            'label' => 'Responsedate',
            'rules' => 'trim|required'
        )
    )
   
    
);
/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */
