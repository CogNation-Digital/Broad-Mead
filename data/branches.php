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
    <div class="contsainer">
 

        <div class="srow">
            <div class="col-md-12">
                <table class="table table-bordered" id="editableTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>ClientID</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Postcode</th>
                            <th>RegistrationNumber</th>
                            <th>VatNo</th>
                            <th>HasBranches</th>
                            <th>CreatedBy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $n = 1;
                        $q = $db_1->query("SELECT * FROM `clients` ");
                        while ($row = $q->fetch(PDO::FETCH_OBJ)) { ?>
                            <?php
                            $name = $row->client_name;
                            $email = strtolower($row->email);
                            $phonenumber = $row->phonenumber;
                            $Address = $row->address;
                            $City = $row->city;
                            $Postcode = $row->postcode;
                            $client_id = $row->client_id;
                            $ClientID = md5($client_id);
                            $Type = $row->type;
                            $UserID = generateUUID();
                            $RegistrationNumber = $row->company_no;
                            $VatNo = $row->vat_no;
                            $CreatedBy = (empty($row->createdBy)) ? '8OWiQKZGYkFqAMbI4LqSvntGXgbIJ0bzRgeQPQRCyYPUrOw9rU' : $row->createdBy;
                            $Date = date("Y-m-d H:i:s");

                            // Check if the client has branches
                            $HasBranches = ($db_1->query("SELECT COUNT(*) FROM `branches` WHERE client = '{$row->client_id}'")->fetchColumn() > 0) ? 'true' : 'false';

                            // Prepare the INSERT query
                            $query_2 = "INSERT INTO `_clients` (`id`, `ClientKeyID`, `ClientID`, `_client_id`, `ClientType`, `Name`, `Email`, `Number`, `Address`, `City`, `Postcode`, `RegistrationNumber`, `VatNo`, `HasBranch`, `CreatedBy`, `Date`) 
                                      VALUES (NULL, :ClientKeyID, :ClientID, :_client_id, :ClientType, :Name, :Email, :Number, :Address, :City, :Postcode, :RegistrationNumber, :VatNo, :HasBranch, :CreatedBy, :Date)";
                            $stmt_2 = $db_2->prepare($query_2);

                            // Bind parameters
                            $stmt_2->bindParam(':ClientKeyID', $ClientKeyID);
                            $stmt_2->bindParam(':ClientID', $ClientID);
                            $stmt_2->bindParam(':_client_id', $client_id);  
                            $stmt_2->bindParam(':ClientType', $Type);  
                            $stmt_2->bindParam(':Name', $name);
                            $stmt_2->bindParam(':Email', $email);
                            $stmt_2->bindParam(':Number', $phonenumber);
                            $stmt_2->bindParam(':Address', $Address);
                            $stmt_2->bindParam(':City', $City);
                            $stmt_2->bindParam(':Postcode', $Postcode);
                            $stmt_2->bindParam(':RegistrationNumber', $RegistrationNumber);
                            $stmt_2->bindParam(':VatNo', $VatNo);
                            $stmt_2->bindParam(':HasBranch', $HasBranches);
                            $stmt_2->bindParam(':CreatedBy', $CreatedBy);
                            $stmt_2->bindParam(':Date', $Date);

                          


                            ?>
                            <tr data-id="1">
                                <td><?php echo $n++; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $phonenumber; ?></td>
                                <td><?php echo md5($client_id); ?></td>
                                <td><?php echo $Address; ?></td>
                                <td><?php echo $City; ?></td>
                                <td><?php echo $Postcode; ?></td>
                                <td><?php echo $RegistrationNumber; ?></td>
                                <td><?php echo $VatNo; ?></td>
                                <td><?php echo $HasBranches; ?></td>
                                <td><?php echo $CreatedBy; ?></td>
                            </tr>
                        <?php } ?>



                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>