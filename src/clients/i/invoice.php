<?php if ($isTab == "Invoices") : ?>
    <div class="card-body table-border-style">
        <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Invoices</h5>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>
                            <span id="selectAll" style="cursor: pointer;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="m9.55 17.308l-4.97-4.97l.714-.713l4.256 4.256l9.156-9.156l.713.714z" />
                                </svg>
                            </span>
                        </th>
                        <th>Invoice Number</th>
                        <th>Invoice Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Sub Total</th>
                        <th>Taxes</th>
                        <th>Grand Total</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $query = "SELECT * FROM `_invoices` WHERE hasClientID = '$ClientID' OR hasBranchID =  '$ClientID' ";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    while ($row = $stmt->fetchObject()) { ?>
                        <?php
                        $CreatedBy = $conn->query("SELECT Name FROM `users` WHERE UserID = '{$row->CreatedBy}' ")->fetchColumn();
                        $subtotal = $conn->query("SELECT SUM(Hours * Rate) FROM `_invoice_` WHERE InvoiceID = '{$row->InvoiceID}' ")->fetchColumn();
                        $taxes = $subtotal * $BANKDATA->VatPercent / 100;
                        ?>
                        <tr>
                            <td><?php echo $n++; ?></td>
                            <td><input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->TimesheetID; ?>" Number="<?php echo $row->Number; ?>" id="flexCheckDefault<?php echo $row->id; ?>"></td>
                            <td><?php echo $row->Number; ?></td>
                            <td><?php echo FormatDate($row->InvoiceDate); ?></td>
                            <td><?php echo FormatDate($row->DueDate); ?></td>
                            <td>
                                <?php if ($row->Status == "Paid"): ?>
                                    <div class="badge bg-success">Paid</div>
                                <?php else: ?>
                                    <div class="badge bg-danger"><?php echo $row->Status; ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $Currency; ?> <?php echo number_format($subtotal, 2); ?></td>
                            <td><?php echo $Currency; ?> <?php echo number_format($taxes, 2); ?></td>
                            <td><?php echo $Currency; ?> <?php echo number_format($subtotal + $taxes, 2); ?></td>
                            <td><?php echo $CreatedBy; ?></td>
                            <td><?php echo FormatDate($row->Date); ?></td>
                            <td>
                                <div class="dropdown">
                                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="<?php echo $LINK ?>/generate_timesheet/?ID=<?php echo $row->TimesheetID; ?>&isTab=Invoice">
                                            <span class="text-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                    <g fill="currentColor">
                                                        <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                        <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                        <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                    </g>
                                                </svg>
                                            </span>
                                            View
                                        </a>

                                    </div>
                                </div>  
                            </td>

                        </tr>

                    <?php } ?>
                </tbody>
            </table>
            <?php if ($stmt->rowCount() == 0) : ?>
                <div class="alert alert-danger">No invoices found</div>
            <?php endif; ?>

        </div>
    </div>


    <div id="DeleteModal" class="modal fade" tabindex="-1" aria-labelledby="DeleteModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="ConfirmDeleteLog">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <div id="CommunicationLogModal" class="modal fade" tabindex="-1" aria-labelledby="CommunicationLogModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Communication Log</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="Details"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>