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


function generateUUIDFromID($id)
{
    // Get the MD5 hash of the ID
    $hash = md5($id);

    // Reformat the hash to match UUID format
    $uuid = sprintf(
        '%08s-%04s-%04s-%04s-%12s',
        substr($hash, 0, 8),
        substr($hash, 8, 4),
        substr($hash, 12, 4),
        substr($hash, 16, 4),
        substr($hash, 20, 12)
    );

    return $uuid;
}
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
                            <th>Candidate ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Number</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Postcode</th>
                            <th>CreatedBy</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $nnn = 0;
                        $n = 1;
                        $q = $db_1->query("SELECT * FROM `candidates` ");
                        while ($row = $q->fetch(PDO::FETCH_OBJ)) { ?>
                            <?php
                            $name = $row->first_name . ' ' . $row->last_name;
                            $status = $row->status;
                            $email = strtolower($row->email);
                            $phonenumber = $row->mobilenumber;
                            $Address = $row->address;
                            $City = $row->country;
                            $Postcode = $row->postcode;
                            $dob = $row->dob;
                            $gender = $row->gender;
                            $IdentificationNumber = str_pad($row->id, 5, '0', STR_PAD_LEFT);

                            $CandidateID = generateUUIDFromID($row->id);
                            $CreatedBy = "8OWiQKZGYkFqAMbI4LqSvntGXgbIJ0bzRgeQPQRCyYPUrOw9rU";

                            $createdByMapping = [
                                "1" => "8OWiQKZGYkFqAMbI4LqSvntGXgbIJ0bzRgeQPQRCyYPUrOw9rU",
                                "10" => "Yt8zjCNNhWg3VEbLwTMmP9oyzCYxqbQxf0d5cSt6KR0qN7cpu1",
                                "11" => "dQnFHpYJd1sUaucC7hvBkC7mZk0xsTfFkXZeHAaV84ISSqfamJ",
                                "13" => "fXEugcUM3HO4S2AY61kvPmL0FNUpiAYtkW0avk6JIPryXYt5KG",
                                "15" => "S4wSQqK0OnYbj6opKOx7mjjTDqpwRT3fzSRbNVt5VVOdAmYw90",
                                "2" => "S4wSQqK0OnYbj6opKOx7mjjTDqpwRT3fzSRbNVt5VVOdAmYw90",
                                "9" => "ED2cSUuTtXuUInQ8WOZLUW9NR6214S6zl2GgKUwqki3G4YLy0C"
                            ];

                            if (array_key_exists($row->createdBy, $createdByMapping)) {
                                $CreatedBy = $createdByMapping[$row->createdBy];
                            }

                            $getfile = $db_1->query("SELECT * FROM `candidatesfiles` WHERE candidateid = '{$row->id}'");

                            while ($docs = $getfile->fetchObject()) {
                                $name = $docs->name;
                                $type = $docs->type;
                                $file = $docs->file; // file example is "files/1694182728.pdf"
                                $expirydate = $docs->expirydate;
                                $createdOn = $docs->createdOn; // Example: "8th September 2023"

                                // Create a DateTime object from the given date
                                $date = DateTime::createFromFormat('jS F Y', $createdOn);

                                // Check if the date was successfully parsed
                                if ($date) {
                                    // Format the date to "YYYY-MM-DD"
                                    $formattedDate = $date->format('Y-m-d');
                                } else {
                                    // Handle the error if the date could not be parsed
                                    $formattedDate = null; // Or handle as needed
                                }

                                $issuedDate = $formattedDate;

                                // Extract the file name from the path
                                $fileName = basename($file); // Example: "1694182728.pdf"

                                // Construct the file URL
                                $fileURL = "https://broad-mead.com/CandidatesDocuments/" . $fileName;

                                // Prepare the query to insert data
                                $stmt = $db_2->prepare("INSERT INTO `_candidates_documents` 
                                    ( `ClientKeyID`, `CandidateID`, `Type`, `Name`, `Path`, `IssuedDate`, `ExpiryDate`, `CreatedBy`, `Date`) 
                                    VALUES (:clientKeyID, :candidateID, :type, :name, :path, :issuedDate, :expiryDate, :createdBy, :date)");

                                // Bind parameters
                                $stmt->bindParam(':clientKeyID', $ClientKeyID); // Assuming $ClientKeyID is defined
                                $stmt->bindParam(':candidateID', $CandidateID); // Candidate ID from previous query
                                $stmt->bindParam(':type', $type);
                                $stmt->bindParam(':name', $name);
                                $stmt->bindParam(':path', $fileURL);
                                $stmt->bindParam(':issuedDate', $issuedDate);
                                $stmt->bindParam(':expiryDate', $expirydate);
                                $stmt->bindParam(':createdBy', $CreatedBy); // Assuming $USERID is defined
                                $stmt->bindParam(':date', $Date);

                                // Execute the query
                                // if ($stmt->execute()) {
                                //     echo "Document for candidate ID {$row->id} inserted successfully.<br>";
                                // } else {
                                //     echo "Error inserting document: " . $stmt->errorInfo()[2] . "<br>";
                                // }
                            }

                            ?>
                            <tr data-id="1">
                                <td><?php echo $n++; ?></td>
                                <td><?php echo $CandidateID; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $email; ?></td>
                                <td><?php echo $phonenumber; ?></td>
                                <td><?php echo $Address; ?></td>
                                <td><?php echo $City; ?></td>
                                <td><?php echo $Postcode; ?></td>
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