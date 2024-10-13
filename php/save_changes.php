<?php
    require "./resumeDB.php";
    $config = include("./config.php");

    //Creating query for update
    $archive_ids = $_POST['archive'];
    $archive_query = "UPDATE `contact_form_responses` SET `is_archive`= 1 WHERE `sr_no` IN ";
    $visible_query = "UPDATE `contact_form_responses` SET `is_archive`= 0 WHERE `sr_no` NOT IN ";
    $ids = "(";
    foreach ($archive_ids as $id) {
        $ids = $ids. " $id,";
    }

    $ids = substr_replace($ids, " );", strlen($ids)-1, strlen($ids));
    $archive_query = $archive_query . $ids;
    $visible_query = $visible_query .$ids;


    //Creating query for delete
    $flag = false;
    if(!empty($_POST['deleted'])){
        $flag = true;
        $deleted_ids = $_POST['deleted'];
        $delete_query = "DELETE FROM `contact_form_responses` WHERE `sr_no` IN ";
        $ids = "(";
        foreach ($deleted_ids as $id) {
            $ids = $ids. " $id,";
        }

        $ids = substr_replace($ids, " );", strlen($ids)-1, strlen($ids));
        $delete_query = $delete_query .$ids;

        // echo $delete_query;
    }


    $my_db = new resumeDB($config['server_name'], 
                        $config['server_username'], 
                        $config['server_password'], 
                        $config['db_name']);


    if($my_db->executeQuery($archive_query)){
        if($my_db->executeQuery($visible_query)){
            echo "Changes have been made successfully!<br>";
            if($flag){
                if($my_db->executeQuery($delete_query)){
                    echo "Response(s) Deleted Successfully!<br>";
                }else{
                    echo "Couldn't Delete Response(s)!<br>";
                }
            }      
        }else{
            echo "Changes couldn't be made!<br>";
        }
    }else{
        echo "Changes couldn't be made!<br>";
    }

?>

<a href="./response_dashboard.php">Go back to Panel</a>