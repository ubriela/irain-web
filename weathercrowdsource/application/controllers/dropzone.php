<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 class Dropzone extends CI_Controller { 
    public function __construct() {
        parent::__construct(); 
        $this->load->helper(array('url','html','form'));
    } 
    public function index() { 
        $this->load->view('dropzone_view'); 
    } 
    public function upload() { 
        if (!empty($_FILES)) { 
            $tempFile = $_FILES['file']['tmp_name']; 
            $fileName = $_FILES['file']['name']; 
            $targetPath = getcwd() . '/uploads/'; 
            $targetFile = $targetPath . $fileName ; 
            move_uploaded_file($tempFile, $targetFile); 
            // if you want to save in db,where here 
            // with out model just for example 
            // $this->load->database(); 
            // load database 
            // $this->db->insert('file_table',array('file_name' => $fileName)); } } 
            } 
            /* End of file dropzone.js */ 
            /* Location: ./application/controllers/dropzone.php */ 
    }
 }