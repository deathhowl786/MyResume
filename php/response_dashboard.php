<script src="https://kit.fontawesome.com/f373c67930.js" crossorigin="anonymous"></script>
<style>
    *{
        margin: 0;
        padding: 0;
    }
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        color: #333;
        margin: 20px auto;
        /* padding: 20px; */
    }

    h1 {
        text-align: center;
        color: #444;
    }
    
    .visible, .archive{
        min-width: 100vw;
        max-height: 100vh;
        position: absolute;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 20px;
    }

    .archive{
        min-height: 100vh;
        backdrop-filter: blur(10px); 
        display: none;
    }

    .responses {
        overflow: none;
        margin: 20px auto;
        max-width: 1000px;
        border-collapse: collapse;
        background-color: #fff;
        margin: 12px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #6c63ff;
        color: white;
        font-weight: bold;
    }

    td {
        color: #555;
        text-align: center;
        i{
            font-size: 22px;
        }
    }

    tr:hover {
        overflow: none;
        background-color: #f1f1f1;
    }

    td a {
        color: #6c63ff;
        /* Match table header color for consistency */
        text-decoration: none;
        font-weight: bold;
    }

    td a:hover {
        text-decoration: underline;
        color: #4a42d4;
        /* Slightly darker shade for hover effect */
    }


    #archive-btn{
        right: 0;
    }

    .button{
        font-size: 32px;
        background-color: #6c63ff;
        color: #f1f1f1;
        padding: 10px;
        border-radius: 10px;
        position: fixed;
        margin: 10px;
        z-index: 1;
        top: 0;
    }

    #save-changes{
        font-size: 18px;
        left: 0;
    }

    .delete-resp{
        i{

            color: red;
        }
    }

    td i:hover{
        scale: 1.25;
    }



</style>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Responses</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>

<body>

    <button class="button" id="save-changes">Save Changes</button>
    <button class="button" id="archive-btn"><i class="fa fa-box-archive"></i></button>
    <div class="visible">
    <h1>Contact Form Responses</h1>
     <?php

                function getRows($responses, $type, $class, $head)
                {   
                    echo "
                    <div class=\"$class\">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>".$head."</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody name=\"$head\"> ";

                    for ($i = 0; $i < count($responses); $i++) {
                        if (!($responses[$i]->is_archive == $type)) {
                            continue;
                        }
                        echo "
                            <tr id=\"".$responses[$i]->sr_no."\">
                                <td>" . $responses[$i]->name . "</td>
                                <td><a href=\"mailto:" . $responses[$i]->email . "\">" . $responses[$i]->email . "</a></td>
                                <td>" . $responses[$i]->message . "</td>
                                <td>" . date("D d M' Y", strtotime($responses[$i]->date_time)) . "</td>
                                <td>" . date("h:i A", strtotime($responses[$i]->date_time)) . "</td>
                                <td><i class=\"fa fa-box-archive archive-icon\" data=\"".$responses[$i]->sr_no."\"></i></td>
                                <td class=\"delete-resp\"><i class=\"fa fa-trash-can delete-icon\"></i></td>
                            </tr>
                        ";
                    }

                    echo "
                            </tbody>
                        </table>
                    </div>
                    ";
                }

                require "./Response.php";
                require "./resumeDB.php";
                $config = include("./config.php");

                if(empty($_POST['username']) || empty($_POST['password'])){
                    echo "<br><br><br><br><br>";
                    echo "<center><h3>Invalid User/Pass!</h3></center>";
                    die();
                }

                $username = $_POST['username'];
                $password = $_POST['password'];

                $cn = new mysqli(
                    $config['server_name'],
                    $config['server_username'],
                    $config['server_password'],
                    $config['db_name']
                );
                $result = $cn->query("SELECT * FROM  `admin` WHERE `username` = \"" . $username . "\" AND `password` = \"" . $password . "\" ;");
                if ($result->num_rows === 1) {
                    $my_db = new resumeDB(
                        $config['server_name'],
                        $config['server_username'],
                        $config['server_password'],
                        $config['db_name']
                    );
                    $responses = $my_db->getResponses();
                    getRows($responses, 0, "responses", "Archive");   
                }
    ?>
    </div>
    <div class="archive">
        <h1>Archived Responses</h1>
        <?php
            getRows($responses, 1, "responses", "Unarchive");
        ?>
    </div>


    <form id="save-changes-form" action="save_changes.php" method="post">
    </form>



</body>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

    $(document).ready(function(){

        var visible_ids = Array(); 
        var archive_ids = Array();
        // var deleted_ids = Array(); 


        $(".visible table tbody tr").each(function(){
            visible_ids.push($(this).attr("id"));
        });
        $(".archive table tbody tr").each(function(){
            archive_ids.push($(this).attr("id"));
        });

        // console.log(visible_ids);
        // console.log(archive_ids);

        $("#archive-btn").on("click", function(){
            if($(".archive").css('display') === 'flex'){
                $('.archive').css('display', "none");
            }else{
                $('.archive').css('display', "flex");   
            }
        }); 

        $(document).on("click", ".archive-icon", function(){

            var tr = $(this).parent().parent();
            var id = $(this).attr("data");
            
            if(tr.parent().attr("name") == "Archive"){
                visible_ids = visible_ids.filter(item => ![id].includes(item));
                archive_ids.push(id);
                $(".archive table tbody").append(tr.clone());
                // tr.remove();
                tr.toggle("1000");
            }else{
                archive_ids = archive_ids.filter(item => ![id].includes(item));
                visible_ids.push(id);
                $(".visible table tbody").append(tr.clone());
                // tr.remove();
                tr.toggle("1000");
            }
            // console.log(tr.parent().attr("name"));
        });


        $("#save-changes").on("click", function(){

            archive_ids.forEach(element => {
                var archive = `<input type="hidden" name="archive[${element}]" value="${element}">`;
                $("#save-changes-form").append(archive);
            });

            $("#save-changes-form").submit();

        })

        $(document).on("click", ".delete-icon", function(){
            var tr = $(this).parent().parent();
            var id = tr.attr("id");
            // deleted_ids.push(id);
            $("#save-changes-form").append(`<input type="hidden" name="deleted[${id}]" value="${id}">`);
            // console.log(deleted_ids);
            // console.log($("#save-changes-form").html());
            // tr.remove();
            tr.toggle("1000");
        })


    });
</script>
</html>

<?php
    $cn->close();
?>