<div class="card">
    <div class="card-header">
        Clients
    </div>
    <div class="card-body">
        <div class="table-responsive dt-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Client Name </th>
                        <th>Client ID</th>
                        <th>Email Address </th>
                        <th>Phone Number </th>
                        <th>Created By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM `_clients` WHERE ClientKeyID = '$ClientKeyID' AND isBranch IS NULL ";

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

                    $query .= " ORDER BY id DESC";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $n = 1;
                    while ($row = $stmt->fetchObject()) { ?>
                        <?php
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>

                            <td><?php echo $row->Name; ?></td>
                            <td><?php echo $row->_client_id; ?></td>
                            <td><?php echo $row->Email; ?></td>
                            <td><?php echo $row->Number; ?></td>
                            <td><?php echo $CreatedBy; ?></td>
                            <td><?php echo FormatDate($row->Date); ?></td>
                        </tr>
                    <?php } ?>


                </tbody>
            </table>
        </div>

        <?php if ($stmt->rowCount() == 0) : ?>
            <div class="alert alert-danger">
                No data found. Please try again with different search criteria.
            </div>
        <?php endif; ?>
    </div>
</div>