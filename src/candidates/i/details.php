<?php if ($isTab == "Details") : ?>
    <div class="card-body">
        <div class="card shadow-none border">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div>
                            <img class="" width="100" height="100" style="border-radius: 50%; object-fit: cover; border: 2px solid #48f542; padding: 2px" src="<?php echo $CandidateData->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'">
                        </div>
                    </div>
                    <div class="flex-grow-1 mx-3">
                        <h6 class="mb-0"><?php echo $CandidateData->Name; ?></h6>
                        <p class="mb-0"><?php echo $CandidateData->IdentificationNumber; ?>
                            <span data-bs-toggle="modal" data-bs-target=".bd-example-modal-sm" style="cursor: pointer;">
                                <?php if ($CandidateData->Status == "Active") : ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else : ?>
                                    <?php if ($CandidateData->Status == "Archived") : ?>
                                        <span class="badge bg-warning">Archived</span>

                                    <?php else : ?>
                                        <?php if ($CandidateData->Status == "Inactive") : ?>
                                            <span class="badge bg-danger"><?php echo $CandidateData->Status; ?></span>
                                        <?php endif; ?>
                                        <?php if ($CandidateData->Status == "Pending Compliance") : ?>
                                            <span class="badge bg-info"><?php echo $CandidateData->Status; ?></span>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>

                        </p>
                      
                    </div>
                    <a href="<?php echo $LINK; ?>/edit_candidate/?CandidateID=<?php echo $CandidateID; ?>">
                        <div class="flex-shrink-0"><button class="btn btn-sm btn-light-secondary"><i class="ti ti-edit"></i> Edit</button></div>

                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="card shadow-none border mb-0 h-100">
                            <div class="card-body">
                                <h6 class="mb-2">Details</h6>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="mb-3"><label class="form-label">Email address</label>
                                            <a href="mailto:<?php echo $CandidateData->Email; ?>">
                                                <p> <?php echo $CandidateData->Email; ?></p>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3"><label class="form-label">Phone number</label>
                                            <a href="tel:<?php echo $CandidateData->number; ?>">
                                                <p> <?php echo $CandidateData->Number; ?></p>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-3"><label class="form-label">Gender</label>
                                            <p> <?php echo $CandidateData->Gender; ?></p>

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3"><label class="form-label">Date of Birth</label>
                                            <p> <?php echo FormatDate($CandidateData->BirthDate); ?></p>

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3"><label class="form-label">Job Title</label>
                                            <p> <?php echo $CandidateData->JobTitle; ?></p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-none border mb-0 h-100">
                            <div class="card-body">
                                <h6 class="mb-2">Address</h6>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-3"><label class="form-label">Address</label>
                                            <p> <?php echo $CandidateData->Address; ?></p>

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3"><label class="form-label">Postcode</label>
                                            <p> <?php echo $CandidateData->Postcode; ?></p>

                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-3"><label class="form-label">City</label>
                                            <p> <?php echo $CandidateData->City; ?></p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<script>
    function UpdateStatus() {
        // Get all radio buttons with the name 'Status'
        let radios = document.getElementsByName('Status');
        // Iterate over them to find the checked one
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                ShowToast('Status updated to ' + radios[i].value);
                $.ajax({
                    url: window.location.href,
                    type: "POST",
                    data: {
                        UpdateStatus: true,
                        Status: radios[i].value
                    },
                    success: function(response) {
                        setTimeout(() => {
                            // Update the status in the page
                            window.location.reload();
                        }, 1000);
                    }
                })
                break;
            }
        }
    }
</script>