<?php
$page = $conn->query("SELECT PageName FROM logs WHERE UserID = '$USERID' ORDER BY id DESC LIMIT 1")->fetchColumn();
?>
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <?php
                    $pages = $conn->query("SELECT * FROM (
                    SELECT * FROM `logs`
                    WHERE UserID  = '$USERID' 
                    ORDER BY `id` DESC
                    LIMIT 3
                    ) AS subquery
                    ORDER BY `id` ASC");
                    while ($row = $pages->fetchObject()) { ?>
                        <li class="breadcrumb-item"><a href="<?php echo $row->PageUrl; ?>"><?php echo $row->PageName; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0"><?php echo $page; ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>