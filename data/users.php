<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname1 = 'broadmead';
$dbname2 = 'broadmead_v3';
$dsn1 = 'mysql:host=' . $host . ';dbname=' . $dbname1;
$dsn2 = 'mysql:host=' . $host . ';dbname=' . $dbname2;


try {
    $db_1 = new PDO($dsn1, $user, $password);
    $db_1->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_2 = new PDO($dsn2, $user, $password);
    $db_2->setAttribute(PDO::ATTR_DEFAULT_STR_PARAM, PDO::PARAM_STR);
    $db_2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<b>Error: </b> " . $e->getMessage();
}

$ClientKeyID = "5WEMfHw2aD2C35j8VsVmSQkpzZ2BI2dpqe8wLfqTmQYHPbnrBh";
function generateUUID($length = 50)
{
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($charset);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $charset[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
$KeyID = generateUUID();
$Pass = '12345';
$CreatedBy = "nbSz9MjsZiCkBK0hrIlH13T87mcaFARuQe4ExPL6dX2fw5pNDV";
$Date = date("Y-m-d H:i:s");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
</head>
<style>
    .row {
        margin-bottom: 20px;
    }

    .button.button-small {
        height: 30px;
        line-height: 30px;
        padding: 0px 10px;
    }

    td input[type=text],
    td select {
        width: 100%;
        height: 30px;
        margin: 0;
        padding: 2px 8px;
    }

    th:last-child {
        text-align: right;
    }

    td:last-child {
        text-align: right;
    }

    td:last-child .button {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 0px;
        margin-bottom: 0px;
        margin-right: 5px;
        background-color: #FFF;
    }

    td:last-child .button .fa {
        line-height: 30px;
        width: 30px;
    }
</style>

<body>
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <br>
                <button class="btn btn-default pull-right add-row"><i class="fa fa-plus"></i>&nbsp;&nbsp; Add Row</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="editableTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Dep</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q = $db_1->query("SELECT * FROM `users` ");
                        while ($row = $q->fetch(PDO::FETCH_OBJ)) { ?>
                            <?php
                            $name = $row->first_name . ' ' . $row->last_name;
                            $email = $row->email;
                            $password = $row->password;
                            $department = $row->department;
                            $UserID = generateUUID();
                            $CreatedBy = "nbSz9MjsZiCkBK0hrIlH13T87mcaFARuQe4ExPL6dX2fw5pNDV";
                            $Date = date("Y-m-d H:i:s");

                            // Prepare the insert statement for db_2
                            $query = "INSERT INTO `users`(`ClientKeyID`, `UserID`, `Name`, `Email`, `Password`, `Department`, `CreatedBy`, `Date`) 
                                         VALUES (:clientKeyID, :userID, :name, :email, :password, :department, :createdBy, :date)";

                            $stmt = $db_2->prepare($query);
                            $stmt->bindParam(':clientKeyID', $ClientKeyID, PDO::PARAM_STR);
                            $stmt->bindParam(':userID', $UserID, PDO::PARAM_STR);
                            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                            $stmt->bindParam(':department', $department, PDO::PARAM_STR);
                            $stmt->bindParam(':createdBy', $CreatedBy, PDO::PARAM_STR);
                            $stmt->bindParam(':date', $Date, PDO::PARAM_STR);

                            // Execute the insert statement
                           // $stmt->execute();
                            ?>
                            <tr data-id="1">
                                <td data-field="name"><?php echo $name; ?></td>
                                <td data-field="birthday"><?php echo $email; ?></td>
                                <td data-field="age"><?php echo $password; ?></td>
                                <td data-field="sex"><?php echo $department; ?></td>
                            </tr>
                        <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>