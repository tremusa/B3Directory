<?php
defined('ABSPATH') or die("No script kiddies please");
require_once  __DIR__ . '/B3_Business_Post.php';
function read_upload()
{
    // Read whole file into a string
    $filestring = file_get_contents(plugin_dir_path(__FILE__).'/uploads/file.csv');
    //Parse String, using | delimeter
    $filearray  = explode("|", $filestring);
    //Create objects out of parsed data
    for ($row = 12; $row<count($filearray); $row=$row+12) {
        $thisbusiness = new Business_Post();
        // get business information from the file array
        $bname = $filearray[$row];
        $bname = wp_strip_all_tags(htmlspecialchars(trim($bname),ENT_QUOTES));
        echo "Business Name: $bname <br>";
        $thisbusiness->set_name($bname);
        $bcity = $filearray[$row+1];
        $bcity = wp_strip_all_tags(htmlspecialchars(trim($bcity),ENT_QUOTES));
        echo "Business City: $bcity <br>";
        $thisbusiness->set_city($bcity);
        $thisbusiness->add_tags($bcity);
        $bstate = $filearray[$row+2];
        $bstate = wp_strip_all_tags(htmlspecialchars(trim($bstate),ENT_QUOTES));
        echo "Business State: $bstate <br>";
        $thisbusiness->set_state($bstate);
        $thisbusiness->add_tags($bstate);
        $babout = $filearray[$row+3];
        $babout = wp_strip_all_tags(htmlspecialchars(trim($babout),ENT_QUOTES));
        echo "About Us: $babout <br>";
        $thisbusiness->set_about($babout);
        $bcat1 = $filearray[$row+4];
        if ($bcat1!="") {
          $bcat1 = wp_strip_all_tags(htmlspecialchars(trim($bcat1),ENT_QUOTES));
            echo "Business Category 1: $bcat1 <br>";
            if (get_cat_ID($bcat1)==0) {
                echo "Category 1 not found <br>";
                if (wp_create_category($bcat1) !=0) {
                    echo "Category $bcat1 Successfully Created <br>";
                }
            }
            $thisbusiness->add_cats($bcat1);
        }
        $bcat2 = $filearray[$row+5];
        if ($bcat2!="") {
          $bcat2 = wp_strip_all_tags(htmlspecialchars(trim($bcat2),ENT_QUOTES));
            echo "Business Category 2: $bcat2 <br>";
            if (get_cat_ID($bcat2)==0) {
                echo "Category 2 not found <br>";
                if (wp_create_category($bcat2) !=0) {
                    echo "Category $bcat2 Successfully Created <br>";
                }
            }
            $thisbusiness->add_cats($bcat2);
        }
        $bcat3 = $filearray[$row+6];
        $bcat3 = wp_strip_all_tags(htmlspecialchars(trim($bcat3),ENT_QUOTES));
        if ($bcat3!="") {
            echo "Business Category 3: $bcat3 <br>";
            if (get_cat_ID($bcat3)==0) {
                echo "Category 3 not found <br>";
                if (wp_create_category($bcat1) !=0) {
                    echo "Category $bcat3 Successfully Created <br>";
                }
            }
            $thisbusiness->add_cats($bcat3);
        }
        $bcat4 = $filearray[$row+7];
        if ($bcat4!="") {
           $bcat4 = wp_strip_all_tags(htmlspecialchars(trim($bcat4),ENT_QUOTES));
            echo "Business Category 4: $bcat4 <br>";
            if (get_cat_ID($bcat4)==0) {
                echo "Category 4 not found <br>";
                if (wp_create_category($bcat4) !=0) {
                    echo "Category $bcat4 Successfully Created <br>";
                    $thisbusiness->add_cats($bcat4);
                }
            }
            $thisbusiness->add_cats($bcat4);
        }
        $bcat5 = $filearray[$row+8];
        if ($bcat5!="") {
          $bcat5 = wp_strip_all_tags(htmlspecialchars(trim($bcat5),ENT_QUOTES));
            echo "Business Category 5: $bcat5 <br>";
            if (get_cat_ID($bcat5)==0) {
                echo "Category 5 not found <br>";
                if (wp_create_category($bcat5) !=0) {
                    echo "Category $bcat5 Successfully Created <br>";
                }
            }
            $thisbusiness->add_cats($bcat5);
        }
        $bcat6 = $filearray[$row+9];
        if ($bcat6!="") {
          $bcat6 = wp_strip_all_tags(htmlspecialchars(trim($bcat6),ENT_QUOTES));
            echo "Business Category 6: $bcat6 <br>";
            if (get_cat_ID($bcat6)==0) {
                echo "Category 6 not found <br>";
                if (wp_create_category($bcat6) !=0) {
                    echo "Category $bcat6 Successfully Created <br>";
                }
            }
            $thisbusiness->add_cats($bcat6);
        }
        $btag1 = $filearray[$row+10];
        $btag1 = wp_strip_all_tags(htmlspecialchars(trim($btag1),ENT_QUOTES));
        echo "Products made in USA: $btag1 <br>";
        $thisbusiness->add_tags($btag1);
        $thisbusiness->set_sma($btag1);
        $burl = $filearray[$row+11];
        $burl= wp_strip_all_tags(htmlspecialchars(trim($burl)));
        echo "URL: <a href='http://$burl'> $burl </a> <br>";
        $thisbusiness->set_url($burl);
        echo "<br>";
        echo "<br>";
        $thisbusiness->add_update();
      }
}
?>
