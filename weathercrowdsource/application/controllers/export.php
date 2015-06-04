<?php
class Export extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->database();
        
 
        // Here you should add some sort of user validation
        // to prevent strangers from pulling your table data
    }
 
    function index()
    {
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('test worksheet');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'This is A1 value');
        $this->excel->getActiveSheet()->setCellValue('B1', 'This is B1 value');
        //change the font size
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        //merge cell A1 until D1
        //$this->excel->getActiveSheet()->mergeCells('A1:D1');
        //set aligment to center for that merged cell (A1 to D1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $now = date("Y-m-d");
        $filename=$now.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache 
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
        $objWriter->save('file\\'.$filename);
        
    }
    public function test(){
        $sql = 'select tasks.taskid,tasks.place,ST_X(tasks.location) AS request_lat, ST_Y(tasks.location) AS request_lng,
        startdate,enddate,worker_place,ST_X(responses.worker_location) AS response_lat, ST_Y(responses.worker_location) AS response_lng,
        response_code,level,response_date from tasks,responses where tasks.iscompleted=1 and tasks.taskid=responses.taskid';
        $query = $this->db->query($sql);
        if($query->num_rows()>0){
            $index = 2;
            $column_taskid = 'A';
            $column_request_place = 'B';
            $column_request_lat = 'C';
            $column_request_lng = 'D';
            $column_startdate = 'E';
            $column_enddate = 'F';
            $column_response_place = 'G';
            $column_response_lat = 'H';
            $column_response_lng = 'I';
            $column_response_code = 'J';
            $column_response_level = 'K';
            $column_response_date = 'L';
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle('test worksheet');
            //set cell A1 content with some text
            $this->excel->getActiveSheet()->setCellValue('A1', 'taskid');
            $this->excel->getActiveSheet()->setCellValue('B1', 'request_place');
            $this->excel->getActiveSheet()->setCellValue('C1', 'request_lat');
            $this->excel->getActiveSheet()->setCellValue('D1', 'request_lng');
            $this->excel->getActiveSheet()->setCellValue('E1', 'startdate');
            $this->excel->getActiveSheet()->setCellValue('F1', 'enddate');
            $this->excel->getActiveSheet()->setCellValue('G1', 'response_place');
            $this->excel->getActiveSheet()->setCellValue('H1', 'response_lat');
            $this->excel->getActiveSheet()->setCellValue('I1', 'response_lng');
            $this->excel->getActiveSheet()->setCellValue('J1', 'response_code');
            $this->excel->getActiveSheet()->setCellValue('K1', 'response_level');
            $this->excel->getActiveSheet()->setCellValue('L1', 'response_date');
            foreach($query->result_array() as $row){
                $taskid = $row['taskid'];
                $request_place = $row['place'];
                $request_lat = $row['request_lat'];
                $request_lng = $row['request_lng'];
                $startdate = $row['startdate'];
                $enddate = $row['enddate'];
                $response_place = $row['worker_place'];
                $response_lat = $row['response_lat'];
                $response_lng = $row['response_lng'];
                $response_code = $row['response_code'];
                $response_level = $row['level'];
                $response_date = $row['response_date'];
                $this->excel->getActiveSheet()->setCellValue($column_taskid.$index, $taskid);
                $this->excel->getActiveSheet()->setCellValue($column_request_place.$index, $request_place);
                $this->excel->getActiveSheet()->setCellValue($column_request_lat.$index, $request_lat);
                $this->excel->getActiveSheet()->setCellValue($column_request_lng.$index, $request_lng);
                $this->excel->getActiveSheet()->setCellValue($column_startdate.$index, $startdate);
                $this->excel->getActiveSheet()->setCellValue($column_enddate.$index, $enddate);
                $this->excel->getActiveSheet()->setCellValue($column_response_place.$index, $response_place);
                $this->excel->getActiveSheet()->setCellValue($column_response_lat.$index, $response_lat);
                $this->excel->getActiveSheet()->setCellValue($column_response_lng.$index, $response_lng);
                $this->excel->getActiveSheet()->setCellValue($column_response_code.$index, $response_code);
                $this->excel->getActiveSheet()->setCellValue($column_response_level.$index, $response_level);
                $this->excel->getActiveSheet()->setCellValue($column_response_date.$index, $response_date);
                $index++;
                
            }
            
            //change the font size
            //$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
            //make the font become bold
            $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
            
            //merge cell A1 until D1
            //$this->excel->getActiveSheet()->mergeCells('A1:D1');
            //set aligment to center for that merged cell (A1 to D1)
            $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            foreach(range('A','L') as $columnID) {
                $this->excel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            $now = date("Y-m-d");
            $filename=$now.'.xls'; //save our workbook as this file name
            header('Content-Type: application/vnd.ms-excel'); //mime type
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
            header('Cache-Control: max-age=0'); //no cache 
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
            $objWriter->save('file/'.$filename);
        }
        
    }
}
?>