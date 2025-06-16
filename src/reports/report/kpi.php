<div style="padding-bottom: 5px;">
    <?php
    $_kpis_query = "SELECT * FROM `_kpis` WHERE ClientKeyID = '$ClientKeyID' $HAS_CREATEDBY ORDER BY id DESC";
    $_kpis_query = $conn->query($_kpis_query);
    while ($row = $_kpis_query->fetchObject()) { ?>
        <?php
        $UserID = $row->UserID;
        $UserData = $conn->query("SELECT * FROM `users` WHERE UserID = '$UserID'")->fetchObject();
        ?>
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 pt-2">
                    <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="analytics-tab-1" data-bs-toggle="tab" data-bs-target="#analytics-tab-1-pane-<?php echo $row->KpiID ?>" type="button" role="tab" aria-controls="analytics-tab-1-pane-<?php echo $row->KpiID ?>" aria-selected="true">Overview</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="analytics-tab-2" data-bs-toggle="tab" data-bs-target="#analytics-tab-2-pane-<?php echo $row->KpiID ?>" type="button" role="tab" aria-controls="analytics-tab-2-pane-<?php echo $row->KpiID ?>" aria-selected="false" tabindex="-1">List</button></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $UserData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40"></div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?php echo $UserData->Name; ?></h6>
                            </div>
                        </div>
                        <div class="col-lg-7 col-xl-8">
                            <ul class="list-inline mb-3 d-flex align-items-center justify-content-end">
                                <li class="list-inline-item">
                                    <div class="d-flex"><button type="button" class="btn btn-sm me-1 btn-secondary">This week</button> </div>
                                </li>
                                <li class="list-inline-item"><a href="<?php echo $LINK; ?>/weekly_kpis" class="avtar avtar-s btn-link-secondary border border-secondary"><i class="ti ti-external-link f-18"></i></a></li>

                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="analytics-tab-1-pane-<?php echo $row->KpiID ?>" role="tabpanel" aria-labelledby="analytics-tab-1" tabindex="0">
                                    <div id="id<?php echo md5($row->KpiID) ?>" style="min-height: 265px;">

                                    </div>
                                </div>
                                <div class="tab-pane fade" id="analytics-tab-2-pane-<?php echo $row->KpiID ?>" role="tabpanel" aria-labelledby="analytics-tab-2" tabindex="0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>KPI</th>
                                                    <th>Target</th>
                                                    <th>Achieved</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $_query_list_1 = $conn->query("SELECT * FROM `_kpis` WHERE UserID ='{$row->UserID}'");
                                                while ($_row_list_1 = $_query_list_1->fetchObject()) { ?>
                                                    <?php
                                                    $n = 1;

                                                    $q = $conn->query("SELECT * FROM `_kpis_targets` WHERE _kpi_id = '{$_row_list_1->KpiID}' ");
                                                    while ($r = $q->fetchObject()) { ?>
                                                        <?php
                                                        $Achieved = $conn->query("SELECT SUM(Achieved)  FROM `_kpis_achieved` WHERE KpiID = '{$r->KpiID}' AND Date BETWEEN '$FromDate' AND '$ToDate' ")->fetchColumn();
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $n++; ?></td>
                                                            <td><?php echo $r->Name; ?></td>
                                                            <td><?php echo $r->Target; ?></td>
                                                            <td><?php echo number_format($Achieved); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-xl-4">
                            <ul class="list-group list-group-flush" style="overflow-y: scroll; height: 400px;">
                                <?php
                                $_query_list = $conn->query("SELECT * FROM `_kpis` WHERE UserID ='{$row->UserID}'");
                                while ($_row_list = $_query_list->fetchObject()) { ?>
                                    <?php
                                    $_query_list_kpis = $conn->query("SELECT * FROM `_kpis_targets` WHERE _kpi_id = '{$_row_list->KpiID}'");
                                    while ($_row_list_kpis = $_query_list_kpis->fetchObject()) { ?>
                                        <?php
                                        $target = floatval($_row_list_kpis->Target);
                                        $achieved = $conn->query("SELECT COALESCE(SUM(Achieved), 0) AS Achieved FROM `_kpis_achieved` WHERE KpiID = '{$_row_list_kpis->KpiID}' AND DATE(Date) BETWEEN '$FromDate' AND '$ToDate'")->fetchColumn();
                                        $achieved = floatval($achieved);

                                        // Calculate percentage
                                        $percentage = ($target > 0) ? ($achieved / $target) * 100 : 0;

                                        // Determine CSS class and icon based on percentage
                                        if ($percentage >= 80) {
                                            $cssClass = 'text-success'; // High percentage
                                            $icon = 'ti ti-arrow-up-right'; // Example: up-right arrow
                                        } elseif ($percentage >= 50) {
                                            $cssClass = 'text-warning'; // Medium percentage
                                            $icon = 'ti ti-arrow-right'; // Example: right arrow
                                        } else {
                                            $cssClass = 'text-danger'; // Low percentage
                                            $icon = 'ti ti-arrow-down-left'; // Example: down-left arrow
                                        }
                                        ?>
                                        <li class="list-group-item">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="row g-1">
                                                        <div class="col-8">
                                                            <p class="text-muted mb-1" title="<?php echo $_row_list_kpis->Name; ?>"><?php echo TruncateText($_row_list_kpis->Name, 30); ?></p>
                                                            <h6 class="mb-0">Target: <?php echo $_row_list_kpis->Target; ?></h6>
                                                        </div>
                                                        <div class="col-4 text-end">
                                                            <h6 class="mb-1 <?php echo $cssClass; ?>"><?php echo number_format($achieved, 2); ?></h6>
                                                            <p class="<?php echo $cssClass; ?> mb-0">
                                                                <i class="<?php echo $icon; ?>"></i> <?php echo number_format($percentage, 2); ?>%
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php } ?>

                                <?php } ?>



                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $_kpis_list = $conn->query("SELECT 
                            _kpis_targets.Name AS NAME, 
                            _kpis_targets.Target AS TARGET, 
                            _kpis.UserID as UserID,
                            COALESCE(SUM(date_filtered.Achieved), 0) AS ACHIEVED
                        FROM 
                            `_kpis_targets`
                        LEFT JOIN (
                            SELECT KpiID, Achieved
                            FROM `_kpis_achieved`
                            WHERE DATE(Date) BETWEEN '$FromDate' AND '$ToDate'
                        ) AS date_filtered
                        ON _kpis_targets.KpiID = date_filtered.KpiID
                        LEFT JOIN 
                            `_kpis` ON _kpis_targets._kpi_id = _kpis.KpiID
                        WHERE 
                            _kpis_targets.ClientKeyID = '$ClientKeyID'
                            AND _kpis.UserID = '{$row->UserID}'
                        GROUP BY 
                            _kpis_targets.Name, _kpis_targets.Target
                        ");

        $names = [];
        $targets = [];
        $achieved = [];

        while ($chart_row = $_kpis_list->fetch(PDO::FETCH_ASSOC)) {
            $names[] = TruncateText($chart_row['NAME'], 30);
            $targets[] = floatval($chart_row['TARGET']);
            $achieved[] = floatval($chart_row['ACHIEVED']);
        }
        ?>



        <script>
            var options = {
                series: [{
                    name: 'Target',
                    data: <?php echo json_encode($targets); ?>
                }, {
                    name: 'Achieved',
                    data: <?php echo json_encode($achieved); ?>
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: <?php echo json_encode($names); ?>,
                },
                yaxis: {
                    title: {
                        text: 'Target and Achieved'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#id<?php echo md5($row->KpiID) ?>"), options);
            chart.render();
        </script>

    <?php } ?>

    <?php if ($_kpis_query->rowCount() == 0) : ?>
        <div class="alert alert-danger">
            No data found. Please try again with different search criteria.
        </div>
    <?php endif; ?>
</div>