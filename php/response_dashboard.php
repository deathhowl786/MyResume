
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #444;
}

.responses {
    margin: 20px auto;
    max-width: 1000px;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
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
}

tr:hover {
    background-color: #f1f1f1;
}

td a {
    color: #6c63ff; /* Match table header color for consistency */
    text-decoration: none;
    font-weight: bold;
}

td a:hover {
    text-decoration: underline;
    color: #4a42d4; /* Slightly darker shade for hover effect */
}

</style>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Responses</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Contact Form Responses</h1>
    <div class="responses">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>

<?php

require "./Response.php";
require "./resumeDB.php";
$config = include("./config.php");


if($_POST['username']==="admin" && $_POST['password']==="1414@creator"){
    $my_db = new resumeDB($config['server_name'], 
                                      $config['server_username'], 
                                      $config['server_password'], 
                                      $config['db_name']);
    $responses = $my_db->getResponses();

    for($i = 0; $i < count($responses); $i++){
        echo "
        <tr>
            <td>".$responses[$i]->name."</td>
            <td><a href=\"mailto:".$responses[$i]->email."\">".$responses[$i]->email."</a></td>
            <td>".$responses[$i]->message."</td>
            <td>".date("D d M' Y", strtotime($responses[$i]->date_time))."</td>
            <td>".date("h:i A", strtotime($responses[$i]->date_time))."</td>
        </tr>
        ";
}
}else{
    echo"
        <tr>
            <td>Your</td>
            <td>are</td>
            <td>not</td>
            <td>the</td>
            <td>Admin !!</td>
        </tr>
    ";

}
?>
            </tbody>
        </table>
    </div>
</body>
</html>
