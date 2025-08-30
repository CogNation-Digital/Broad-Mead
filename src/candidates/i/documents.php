<?php

if (!isset($CandidateID) && isset($_GET['ID'])) {
    $CandidateID = $_GET['ID'];
}
if (is_numeric($CandidateID)) {
    try {
        $stmt = $conn->prepare("SELECT CandidateID FROM _candidates WHERE id = ? LIMIT 1");
        $stmt->execute([$CandidateID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['CandidateID'])) {
            $CandidateID = $row['CandidateID'];
        }
    } catch (Exception $e) {
   
    }
}

echo '<div style="background:#ffeeba;padding:10px;margin-bottom:10px;">';
echo '<strong>Debug:</strong> CandidateID = ' . htmlspecialchars($CandidateID) . '<br>';
$docFolders = [
    $_SERVER['DOCUMENT_ROOT'] . '/CandidatesDocuments/',
    $_SERVER['DOCUMENT_ROOT'] . '/CandidatesDocuments/CandidatesDocuments/'
];
$webFolders = [
    $LINK . '/CandidatesDocuments/',
    $LINK . '/CandidatesDocuments/CandidatesDocuments/'
];
foreach ($docFolders as $i => $docFolder) {
    $candidateFiles = glob($docFolder . $CandidateID . '.*');
    echo 'Checking folder: ' . $docFolder . '<br>';
    if ($candidateFiles) {
        echo 'Found files:<ul>';
        foreach ($candidateFiles as $filePath) {
            echo '<li>' . htmlspecialchars($filePath) . '</li>';
        }
        echo '</ul>';
    } else {
        echo 'No files found.<br>';
    }
}
echo '</div>';
?>
    <div class="card-body table-border-style">
        <div style="margin-bottom: 20px;" class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Compliance Checklist</h5>
            <?php
            $hasPermission = IsCheckPermission($USERID, "CREATE_CANDIDATE_DOCUMENTS") ||
                IsCheckPermission($USERID, "EDIT_CANDIDATE_DOCUMENTS") ||
                IsCheckPermission($USERID, "DELETE_CANDIDATE_DOCUMENTS");

            if ($hasPermission) : ?>
                <div class="dropdown">
                    <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical f-18"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <?php if (IsCheckPermission($USERID, "CREATE_CANDIDATE_DOCUMENTS")) : ?>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#CreateModal">
                                <span class="text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" color="currentColor" />
                                    </svg>
                                </span>
                                Create
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="display: none;">Select</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>File</th>
                        <th>Expiry Date</th>
                        <th>Created By</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $hasDbDocs = false;
                    $query = null;
                    try {
                        $query  = $conn->query("SELECT * FROM `_candidates_documents` WHERE CandidateID = '$CandidateID'");
                    } catch (Exception $e) {
                        $query = null;
                    }
                    if ($query) {
                        while ($row = $query->fetchObject()) {
                            $hasDbDocs = true;
                            echo '<tr>';
                            echo '<td>' . $n++ . '</td>';
                            echo '<td style="display: none;"><input class="form-check-input checkbox-item" type="checkbox" value="' . $row->id . '" data-name="' . $row->Name . '" data-type="' . $row->Type . '" data-expiry="' . $row->ExpiryDate . '" data-issueddate="' . $row->IssuedDate . '" id="flexCheckDefault' . $row->id . '"></td>';
                            echo '<td>' . $row->Type . '</td>';
                            echo '<td>' . $row->Name . '</td>';
                            echo '<td><div class="flex-shrink-0"><a href="' . $row->Path . '" target="_blank" class="btn btn-sm btn-light-secondary">'
                                .'<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" /></svg> Download</a></div></td>';
                            $currentDate = new DateTime();
                            $expiryDate = DateTime::createFromFormat('Y-m-d', $row->ExpiryDate);
                            $isExpired = $expiryDate && $expiryDate < $currentDate;
                            echo '<td>' . (empty($row->ExpiryDate) ? 'No expiry date' : FormatDate($row->ExpiryDate));
                            if ($isExpired) {
                                echo ' <span class="badge bg-danger">Expired</span>';
                            }
                            echo '</td>';
                            echo '<td>' . CreatedBy($row->CreatedBy) . '</td>';
                            echo '<td>' . FormatDate($row->Date) . '</td>';
                            // Action column
                            $hasPermission = IsCheckPermission($USERID, "CREATE_CANDIDATE_DOCUMENTS") ||
                                IsCheckPermission($USERID, "EDIT_CANDIDATE_DOCUMENTS") ||
                                IsCheckPermission($USERID, "DELETE_CANDIDATE_DOCUMENTS");
                            echo '<td>';
                            if ($hasPermission) {
                                echo '<div class="dropdown">';
                                echo '<a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                echo '<i class="ti ti-dots-vertical f-18"></i>';
                                echo '</a>';
                                echo '<div class="dropdown-menu dropdown-menu-end">';
                                if (IsCheckPermission($USERID, "EDIT_CANDIDATE_DOCUMENTS")) {
                                    echo '<a class="dropdown-item select-entry-row edit" href="javascript:void(0)"><span class="text-info"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" /></svg></span>Edit</a>';
                                }
                                if (IsCheckPermission($USERID, "DELETE_CANDIDATE_DOCUMENTS")) {
                                    echo '<a class="dropdown-item select-entry-row" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal"><span class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20"><path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" /></svg></span>Delete</a>';
                                }
                                echo '</div></div>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                    }

                    // Fallback: If no DB docs, show all files in CandidatesDocuments folders
                    if (!$hasDbDocs) {
                        $docFolders = [
                            $_SERVER['DOCUMENT_ROOT'] . '/CandidatesDocuments/',
                            $_SERVER['DOCUMENT_ROOT'] . '/CandidatesDocuments/CandidatesDocuments/'
                        ];
                        $webFolders = [
                            $LINK . '/CandidatesDocuments/',
                            $LINK . '/CandidatesDocuments/CandidatesDocuments/'
                        ];
                        $foundFiles = false;
                        foreach ($docFolders as $i => $docFolder) {
                            $allFiles = glob($docFolder . '*.{pdf,doc,docx,jpg,jpeg,png}', GLOB_BRACE);
                            if ($allFiles) {
                                $foundFiles = true;
                                foreach ($allFiles as $filePath) {
                                    $fileName = basename($filePath);
                                    $fileUrl = $webFolders[$i] . $fileName;
                                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                    echo '<tr>';
                                    echo '<td>' . $n++ . '</td>';
                                    echo '<td style="display: none;"></td>';
                                    echo '<td>Document</td>';
                                    echo '<td>' . htmlspecialchars($fileName) . '</td>';
                                    echo '<td><div class="flex-shrink-0"><a href="' . $fileUrl . '" target="_blank" class="btn btn-sm btn-light-secondary">'
                                        .'<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" /></svg> Download</a></div></td>';
                                    echo '<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>';
                                    echo '</tr>';
                                }
                            }
                        }
                        if (!$foundFiles) {
                            echo '<tr><td colspan="9" class="text-center">No documents found for this candidate.</td></tr>';
                        }
                    }
                    ?>
                </tbody>
                                                    Edit
                                                </a>
                                            <?php // endif removed after restructuring ?>
                                            <?php if (IsCheckPermission($USERID, "DELETE_CANDIDATE_DOCUMENTS")) : ?>
                                                <a class="dropdown-item select-entry-row" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal">
                                                    <span class="text-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20">
                                                            <path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" />
                                                        </svg>
                                                    </span>
                                                    Delete
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                
                            </td>




                        </tr>
                    

                </tbody>

            </table>
            <?php 
            if (!isset($query)) {
                $query = null;
            }
            if ($query && $query->rowCount() == 0) : ?>
                <div class="alert alert-danger">
                    No data found.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="CreateModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="CreateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreateModalLabel">Upload Document</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php if (IsCheckPermission($USERID, "CREATE_CANDIDATE_DOCUMENTS")) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="display: none;">Select</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>File</th>
                                    <th>Expiry Date</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($isTab == "Documents") {
                                    $n = 1;
                                    $hasDbDocs = false;
                                    $query = null;
                                    try {
                                        $query  = $conn->query("SELECT * FROM `_candidates_documents` WHERE CandidateID = '$CandidateID'");
                                    } catch (Exception $e) {
                                        $query = null;
                                    }
                                    if ($query) {
                                        while ($row = $query->fetchObject()) {
                                            $hasDbDocs = true;
                                            echo '<tr>';
                                            echo '<td>' . $n++ . '</td>';
                                            echo '<td style="display: none;"><input class="form-check-input checkbox-item" type="checkbox" value="' . $row->id . '" data-name="' . $row->Name . '" data-type="' . $row->Type . '" data-expiry="' . $row->ExpiryDate . '" data-issueddate="' . $row->IssuedDate . '" id="flexCheckDefault' . $row->id . '"></td>';
                                            echo '<td>' . $row->Type . '</td>';
                                            echo '<td>' . $row->Name . '</td>';
                                            echo '<td><div class="flex-shrink-0"><a href="' . $row->Path . '" target="_blank" class="btn btn-sm btn-light-secondary">'
                                                .'<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" /></svg> Download</a></div></td>';
                                            $currentDate = new DateTime();
                                            $expiryDate = DateTime::createFromFormat('Y-m-d', $row->ExpiryDate);
                                            $isExpired = $expiryDate && $expiryDate < $currentDate;
                                            echo '<td>' . (empty($row->ExpiryDate) ? 'No expiry date' : FormatDate($row->ExpiryDate));
                                            if ($isExpired) {
                                                echo ' <span class="badge bg-danger">Expired</span>';
                                            }
                                            echo '</td>';
                                            echo '<td>' . CreatedBy($row->CreatedBy) . '</td>';
                                            echo '<td>' . FormatDate($row->Date) . '</td>';
                                            // Action column
                                            $hasPermission = IsCheckPermission($USERID, "CREATE_CANDIDATE_DOCUMENTS") ||
                                                IsCheckPermission($USERID, "EDIT_CANDIDATE_DOCUMENTS") ||
                                                IsCheckPermission($USERID, "DELETE_CANDIDATE_DOCUMENTS");
                                            echo '<td>';
                                            if ($hasPermission) {
                                                echo '<div class="dropdown">';
                                                echo '<a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                                echo '<i class="ti ti-dots-vertical f-18"></i>';
                                                echo '</a>';
                                                echo '<div class="dropdown-menu dropdown-menu-end">';
                                                if (IsCheckPermission($USERID, "EDIT_CANDIDATE_DOCUMENTS")) {
                                                    echo '<a class="dropdown-item select-entry-row edit" href="javascript:void(0)"><span class="text-info"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 20v-2.52L17.18 4.288q.155-.137.34-.212T17.907 4t.39.064q.19.063.35.228l1.067 1.074q.165.159.226.35q.06.19.06.38q0 .204-.068.39q-.069.185-.218.339L6.519 20zM17.504 7.589L19 6.111L17.889 5l-1.477 1.496z" /></svg></span>Edit</a>';
                                                }
                                                if (IsCheckPermission($USERID, "DELETE_CANDIDATE_DOCUMENTS")) {
                                                    echo '<a class="dropdown-item select-entry-row" href="#" data-bs-toggle="modal" data-bs-target="#DeleteModal"><span class="text-danger"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20"><path fill="currentColor" d="M8.5 4h3a1.5 1.5 0 0 0-3 0m-1 0a2.5 2.5 0 0 1 5 0h5a.5.5 0 0 1 0 1h-1.054l-1.194 10.344A3 3 0 0 1 12.272 18H7.728a3 3 0 0 1-2.98-2.656L3.554 5H2.5a.5.5 0 0 1 0-1zM9 8a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0zm2.5-.5a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V8a.5.5 0 0 0-.5-.5" /></svg></span>Delete</a>';
                                                }
                                                echo '</div></div>';
                                            }
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    // Fallback: If no DB docs, check CandidatesDocuments folder for files matching candidate ID
                                    if (!$hasDbDocs) {
                                        $docFolders = [
                                            $_SERVER['DOCUMENT_ROOT'] . '/CandidatesDocuments/',
                                            $_SERVER['DOCUMENT_ROOT'] . '/CandidatesDocuments/CandidatesDocuments/'
                                        ];
                                        $webFolders = [
                                            $LINK . '/CandidatesDocuments/',
                                            $LINK . '/CandidatesDocuments/CandidatesDocuments/'
                                        ];
                                        $foundFiles = false;
                                        foreach ($docFolders as $i => $docFolder) {
                                            $candidateFiles = glob($docFolder . $CandidateID . '.*');
                                            if ($candidateFiles) {
                                                $foundFiles = true;
                                                foreach ($candidateFiles as $filePath) {
                                                    $fileName = basename($filePath);
                                                    $fileUrl = $webFolders[$i] . $fileName;
                                                    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                                                    echo '<tr>';
                                                    echo '<td>' . $n++ . '</td>';
                                                    echo '<td style="display: none;"></td>';
                                                    echo '<td>Document</td>';
                                                    echo '<td>' . htmlspecialchars($fileName) . '</td>';
                                                    echo '<td><div class="flex-shrink-0"><a href="' . $fileUrl . '" target="_blank" class="btn btn-sm btn-light-secondary">'
                                                        .'<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.92 12.08L12 20l-7.92-7.92l1.42-1.41l5.5 5.5V2h2v14.17l5.5-5.51zM12 20H2v2h20v-2z" /></svg> Download</a></div></td>';
                                                    echo '<td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>';
                                                    echo '</tr>';
                                                }
                                            }
                                        }
                                        if (!$foundFiles) {
                                            echo '<tr><td colspan="9" class="text-center">No documents found for this candidate.</td></tr>';
                                        }
                                    }
                                } else {
                                    // For other tabs, just show the table structure and no data row
                                    echo '<tr><td colspan="9" class="text-center">No documents found for this candidate.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php 
                        if (!isset($query)) {
                            $query = null;
                        }
                        if ($isTab == "Documents" && $query && $query->rowCount() == 0) { ?>
                            <div class="alert alert-danger">
                                No data found.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>