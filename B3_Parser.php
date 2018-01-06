<?php
function read_upload()
{
    // Read whole file into a string
    $filestring = file_get_contents(plugin_dir_path(__FILE__).'/uploads/file.csv');
    //Parse String, using | delimeter
    $filearray  = explode("|", $filestring);
    //Create objects out of parsed data
    for ($row = 13; $row<count($filearray); $row=$row+13) {
        // get business  name
        $bname = $filearray[$row];
        echo "Business Name: $bname <br>";
        $bcity = $filearray[$row+1];
        echo "Business City: $bcity <br>";
        $bstate = $filearray[$row+2];
        echo "Business State: $bstate <br>";
        $babout = $filearray[$row+3];
        echo "About Us: $babout <br>";
        $bcat1 = $filearray[$row+4];
        if ($bcat1!="") {
            echo "Business Category 1: $bcat1<br>";
        }
        $bcat2 = $filearray[$row+5];
        if ($bcat2!="") {
            echo "Business Category 2: $bcat2 <br>";
        }
        $bcat3 = $filearray[$row+6];
        if ($bcat3!="") {
            echo "Business Category 3: $bcat3 <br>";
        }
        $bcat4 = $filearray[$row+7];
        if ($bcat4!="") {
            echo "Business Category 4: $bcat4 <br>";
        }
        $bcat5 = $filearray[$row+8];
        if ($bcat5!="") {
            echo "Business Category 5: $bcat5 <br>";
        }
        $bcat6 = $filearray[$row+9];
        if ($bcat6!="") {
            echo "Business Category 6: $bcat6 <br>";
        }
        $busaproducts = $filearray[$row+10];
        echo "Products made in USA: $busaproducts <br>";
        $burl = $filearray[$row+11];
        echo "URL: $burl <br>";
        $bnotes = $filearray[$row+12];
        echo "Notes: $bnotes <br>";
        echo "<br>";
        echo "<br>";
    }
}
