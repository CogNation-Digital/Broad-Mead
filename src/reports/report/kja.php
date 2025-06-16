<div class="card">
    <div class="card-header">
        Key Job Area
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client Type </th>
                    <th>Location</th>
                    <th>Job Role</th>
                    <th>Pay Rate</th>
                    <th>Created By</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM `_keyjobarea` WHERE ClientKeyID = '$ClientKeyID'";

                if (isset($_GET['q'])) {
                    $SearchID = $_GET['q'];
                    $qu = $conn->query("SELECT * FROM `search_queries` WHERE SearchID = '$SearchID'");
                    while ($r = $qu->fetchObject()) {
                        $column = $r->column;
                        $value = $r->value;
                        $query .= " AND " . $column . " LIKE '%$value%'";
                    }
                }
                $query .= " AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'";

                $stmt = $conn->prepare($query);
                $stmt->execute();
                $n = 1;
                while ($row = $stmt->fetchObject()) { ?>
                    <?php
                    $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                    ?>
                    <tr>
                        <td><?php echo $n++; ?></td>
                        <td><?php echo TruncateText($row->ServiceUser, 30); ?></td>
                        <td><?php echo TruncateText($row->Location, 30); ?></td>
                        <td><?php echo TruncateText($row->Specification, 30); ?></td>
                        <td><?php echo $row->PayRate; ?></td>
                        <td><?php echo $CreatedBy; ?></td>
                        <td><?php echo FormatDate($row->Date); ?></td>
                    </tr>
                <?php } ?>


            </tbody>
        </table>
        <?php if ($stmt->rowCount() == 0): ?>
            <div class="alert alert-danger">
                No data found. Please try again with different search criteria.
            </div>
        <?php endif; ?>
    </div>
</div>