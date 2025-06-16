<?php if ($isTab == "Candidates") : ?>
    <div id="Candidates">

        <div class="row" style="margin-top: 10px;">
            <div class="col-12">
                <div class="card shadow-none border mb-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">

                            <div class="flex-grow-1 ">
                                <h6 class="mb-0">Candidates</h6>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="dropdown"><a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-dots-vertical f-18"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#AddCandidate">
                                            <span class="text-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                                </svg>
                                            </span>
                                            Add
                                        </a>
                                        <a class="dropdown-item" href="#" id="View">
                                            <span class="text-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                    <g fill="currentColor">
                                                        <path d="M6.5 12a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1zm0 3a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z" />
                                                        <path fill-rule="evenodd" d="M11.185 1H4.5A1.5 1.5 0 0 0 3 2.5v15A1.5 1.5 0 0 0 4.5 19h11a1.5 1.5 0 0 0 1.5-1.5V7.202a1.5 1.5 0 0 0-.395-1.014l-4.314-4.702A1.5 1.5 0 0 0 11.185 1M4 2.5a.5.5 0 0 1 .5-.5h6.685a.5.5 0 0 1 .369.162l4.314 4.702a.5.5 0 0 1 .132.338V17.5a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5z" clip-rule="evenodd" />
                                                        <path d="M11 7h5.5a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5v-6a.5.5 0 0 1 1 0z" />
                                                    </g>
                                                </svg>
                                            </span>
                                            View</a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                            <span class="text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                    <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                </svg>
                                            </span>
                                            Remove
                                        </a>
                                    </div>
                                </div>
                            </div>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone number</th>
                                        <th>Added By</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $n = 1;
                                    $query = "SELECT 
                                                vc.id, 
                                                c.Name AS CandidateName, 
                                                c.ProfileImage AS ProfileImage, 
                                                vc.CandidateID AS CandidateID, 
                                                c.Email, 
                                                c.Number, 
                                                u.Name AS CreatedByName, 
                                                vc.Date
                                            FROM 
                                                `__vacancy_candidates` vc
                                            INNER JOIN 
                                                `_candidates` c ON vc.CandidateID = c.CandidateID AND vc.ClientKeyID = c.ClientKeyID
                                            INNER JOIN 
                                                `users` u ON vc.CreatedBy = u.UserID
                                            WHERE 
                                                vc.ClientKeyID = :ClientKeyID AND vc.VacancyID = :VacancyID
                                            ORDER BY 
                                                vc.id DESC";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bindParam(':ClientKeyID', $ClientKeyID);
                                    $stmt->bindParam(':VacancyID', $VacancyID);
                                    $stmt->execute();
                                    $result = $stmt->fetchAll(PDO::FETCH_OBJ);

                                    foreach ($result as $row) { ?>
                                        <tr>
                                            <td><?php echo $n++; ?></td>
                                            <td> <input class="form-check-input checkbox-item" type="checkbox" value="<?php echo $row->CandidateID; ?>" id="flexCheckDefault<?php echo $row->id ?>"> </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0"><img width="40" height="40" style="object-fit: cover;" src="<?php echo $row->ProfileImage; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" alt="user image" class="img-radius wid-40"></div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0"><?php echo $row->CandidateName; ?></h6>
                                                    </div>
                                                </div>
                                                
                                            </td>
                                            <td><?php echo $row->Email; ?></td>
                                            <td><?php echo $row->Number; ?></td>
                                            <td><?php echo $row->CreatedByName; ?></td>
                                            <td><?php echo FormatDate($row->Date); ?></td>
                                        </tr>
                                    <?php } ?>


                                </tbody>
                            </table>
                            <?php if (count($result) == 0) : ?>
                                <div class="alert alert-danger">
                                    No candidates were found
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="AddCandidate" class="modal fade" tabindex="-1" aria-labelledby="AddCandidateLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AddCandidateLabel">Candidate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <select name="candidate" class="select-input" id="candidateSelect" onchange="populateCandidateDetails()">
                                        <option value=""></option>
                                        <?php
                                        $_query = "SELECT * FROM `_candidates` WHERE ClientKeyID = '$ClientKeyID' AND Status = 'Active'";
                                        $result = $conn->query($_query);
                                        while ($row = $result->fetchObject()) { ?>
                                            <option value="<?php echo $row->CandidateID; ?>" data-email="<?php echo $row->Email; ?>" data-number="<?php echo $row->Number; ?>"><?php echo $row->Name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" id="Email" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Phone number</label>
                                    <input type="text" class="form-control" id="Phone" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="AddCandidate" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
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
                    <p>Are you sure you want to remove candidate? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function populateCandidateDetails() {
            var candidateSelect = document.getElementById('candidateSelect');
            var selectedOption = candidateSelect.options[candidateSelect.selectedIndex];

            var email = selectedOption.getAttribute('data-email');
            var number = selectedOption.getAttribute('data-number');

            document.getElementById('Email').value = email;
            document.getElementById('Phone').value = number;
        }

        document.getElementById('selectAll').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.checkbox-item');
            let allChecked = this.classList.toggle('checked');

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = allChecked;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {


            $("#View").click(function() {
                let checkboxes = document.querySelectorAll('.checkbox-item:checked');
                // if no checkboxes are selected alert
                if (checkboxes.length == 0) {
                    ShowToast('Error 102: Something went wrong.');
                    return;
                } else {
                    let id = checkboxes[0].value;
                    window.location.href = "<?php echo $LINK; ?>/view_candidate/?ID=" + id
                }
            })

            document.getElementById('confirmDelete').addEventListener('click', function() {
                let checkboxes = document.querySelectorAll('.checkbox-item:checked');
                let ids = [];
                let successCount = 0;

                checkboxes.forEach(function(checkbox) {
                    ids.push({
                        id: checkbox.value,
                        name: checkbox.getAttribute('data-name')
                    });
                });

                if (ids.length > 0) {
                    $("#confirmDelete").text("Deleting...");

                    ids.forEach(function(item) {
                        $.ajax({
                            url: window.location.href,
                            type: 'POST',
                            data: {
                                ID: item.id,
                                RemoveCandidate: true
                            },
                            success: function(response) {
                                successCount++;
                                if (successCount === ids.length) {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log("Error: " + error);
                                // Optionally, handle the error case if needed
                            }
                        });
                    });

                } else {
                    ShowToast('Error 102: Something went wrong.');
                }
            });

        });
    </script>
<?php endif; ?>