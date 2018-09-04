<?php

/** Set default timezone (will throw a notice otherwise) */
date_default_timezone_set('Asia/Kolkata');

include 'PHPExcel/IOFactory.php';

if(isset($_FILES['file']['name'])){
    $file_name = $_FILES['file']['name'];
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    
    //Checking the file extension
    if($ext == "xlsx"){

            $file_name = $_FILES['file']['tmp_name'];
            $inputFileName = $file_name;
            move_uploaded_file($_FILES['file']['tmp_name'],time()."_".$_FILES['file']['name']);
    /**********************PHPExcel Script to Read Excel File**********************/

        //  Read your Excel workbook
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName); //Identify the file
            $objReader = PHPExcel_IOFactory::createReader($inputFileType); //Creating the reader
            $objPHPExcel = $objReader->load($inputFileName); //Loading the file
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) 
            . '": ' . $e->getMessage());
        }

        //Table used to display the contents of the file
        echo '<center><table style="width:50%;" border=1>';

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);     //Selecting sheet 0
        $highestRow = $sheet->getHighestRow();     //Getting number of rows
        $highestColumn = $sheet->getHighestColumn();     //Getting number of columns

        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) {

            //  Read a row of data into an array

            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, 
            NULL, TRUE, FALSE);
            // This line works as $sheet->rangeToArray('A1:E1') that is selecting all the cells in that row from cell A to highest column cell

            echo "<tr>";

            //echoing every cell in the selected row for simplicity. You can save the data in database too.
            foreach($rowData[0] as $k=>$v)
                echo "<td>".$v."</td>";

            echo "</tr>";
        }

        echo '</table></center>';

    }

    else{
        echo '<p style="color:red;">Please upload file with xlsx extension only</p>'; 
    }   

}
?>