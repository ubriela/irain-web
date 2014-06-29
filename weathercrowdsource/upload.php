<?php
$output_dir = "xml/";


if(isset($_FILES["myfile"]))
{
	//Filter the file types , if you want.
    $temp = explode(".", $_FILES["myfile"]["name"]);

    $allowedExts = array("xml");
    
    $extension = end($temp);
    
    if( in_array($extension, $allowedExts)){
    
   	    if ($_FILES["myfile"]["error"] > 0)
        	{
        	  echo "Error: " . $_FILES["file"]["error"] . "<br>";
        	}
        	else
        	{
        		//move the uploaded file to uploads folder;
            	move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $_FILES["myfile"]["name"]);
            
           	 echo "Uploaded File :".$_FILES["myfile"]["name"];
        	}
    }else
    {
    
         echo "Error,not Documentum type...";
    }
    


}
?>