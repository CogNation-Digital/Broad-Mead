<?php
include "../includes/config.php";
$TemplateID = $_GET['TemplateID'];

$query = "SELECT * FROM `_email_templates` WHERE TemplateID = '$TemplateID' ";
$stmt = $conn->query($query);
$TemplateData = $stmt->fetchObject();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $TemplateData->Title; ?></title>
    <link rel="icon" href="<?php echo $ICON; ?>" type="image/x-icon">

</head>
<body>
    <?php echo $TemplateData->Template; ?>
</body>
</html>