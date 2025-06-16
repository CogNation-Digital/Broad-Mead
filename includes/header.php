 <header class="pc-header" style="background-color: transparent !important;">
    <div class="header-wrapper"><!-- [Mobile Media Block] start -->
        <div class="me-auto pc-mob-drp" style="width: 100%;">
            <ul class="list-unstyled"><!-- ======= Menu collapse Icon ===== -->
                <li class="pc-h-item d-none d-md-inline-flex">

                    <form class="form-search">
                        <i class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="3em" height="3em" viewBox="0 0 24 24">
                                <path fill="currentColor" d="m19.6 21l-6.3-6.3q-.75.6-1.725.95T9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l6.3 6.3zM9.5 14q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14" />
                            </svg>
                        </i>
                        <input type="search" id="searchInput" class="form-control" style="width: 500px; padding-left: 50px;" placeholder="Search Broad-Mead">
                    </form>
                </li>
            </ul>
        </div><!-- [Mobile Media Block end] -->
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                    <a data-bs-toggle="modal" data-bs-target="#SetDataRange" class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M7 11h2v2H7zm14-6v14c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h1V1h2v2h8V1h2v2h1a2 2 0 0 1 2 2M5 7h14V5H5zm14 12V9H5v10zm-4-6v-2h2v2zm-4 0v-2h2v2zm-4 2h2v2H7zm8 2v-2h2v2zm-4 0v-2h2v2z"></path>
                        </svg>
                    </a>
                </li>
                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="m3.55 19.09l1.41 1.41l1.8-1.79l-1.42-1.42M12 6c-3.31 0-6 2.69-6 6s2.69 6 6 6s6-2.69 6-6c0-3.32-2.69-6-6-6m8 7h3v-2h-3m-2.76 7.71l1.8 1.79l1.41-1.41l-1.79-1.8M20.45 5l-1.41-1.4l-1.8 1.79l1.42 1.42M13 1h-2v3h2M6.76 5.39L4.96 3.6L3.55 5l1.79 1.81zM1 13h3v-2H1m12 9h-2v3h2"></path>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end pc-h-dropdown">
                        <a href="#!" class="dropdown-item" onclick="layout_change('dark')">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-moon"></use>
                            </svg>
                            <span>Dark</span>
                        </a>
                        <a href="#!" class="dropdown-item" onclick="layout_change('light')">
                            <svg class="pc-icon">
                                <use xlink:href="#custom-sun-1"></use>
                            </svg>
                            <span>Light</span>
                        </a>
                    </div>
                </li>

                <li class="dropdown pc-h-item">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M10 21h4c0 1.1-.9 2-2 2s-2-.9-2-2m11-2v1H3v-1l2-2v-6c0-3.1 2-5.8 5-6.7V4c0-1.1.9-2 2-2s2 .9 2 2v.3c3 .9 5 3.6 5 6.7v6zm-4-8c0-2.8-2.2-5-5-5s-5 2.2-5 5v7h10z" />
                        </svg>
                        <span class="badge bg-danger pc-h-badge" style="border-radius: 7px; color: white !important"><?php echo ($NOTIFICATION_COUNT > 100) ? '100+' : $NOTIFICATION_COUNT; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-notification dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header d-flex align-items-center justify-content-between">
                            <h5 class="m-0">Notifications</h5>
                        </div>
                        <div class="dropdown-body text-wrap header-notification-scroll position-relative simplebar-scrollable-y" style="max-height: calc(100vh - 215px)" data-simplebar="init">
                            <div class="simplebar-wrapper" style="margin: -16px -20px;">
                                <div class="simplebar-height-auto-observer-wrapper">
                                    <div class="simplebar-height-auto-observer"></div>
                                </div>
                                <div class="simplebar-mask">
                                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                        <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden scroll;">
                                            <div class="simplebar-content" style="padding: 16px 20px;">
                                                <p class="text-span">Today</p>

                                                <?php
                                                $NOTIFICATION_QUERY = "SELECT * FROM `notifications` WHERE ClientKeyID = :ClientKeyID AND DATE(Date) = :Date";

                                                if (!IsCheckPermission($USERID, "IS_RECEIVE_ALL_NOTIFICATIONS")) {
                                                    $NOTIFICATION_QUERY .= " AND hasUseID = :UserID";
                                                }
                                                $NOTIFICATION_QUERY .= " ORDER BY id DESC";

                                                $NOTIFICATION_QUERY_STMT = $conn->prepare($NOTIFICATION_QUERY);
                                                $NOTIFICATION_QUERY_STMT->bindParam(':ClientKeyID', $ClientKeyID);
                                                $NOTIFICATION_QUERY_STMT->bindParam(':Date', $DATE);

                                                if (!IsCheckPermission($USERID, "IS_RECEIVE_ALL_NOTIFICATIONS")) {
                                                    $NOTIFICATION_QUERY_STMT->bindParam(':UserID', $USERID);
                                                }

                                                $NOTIFICATION_QUERY_STMT->execute();

                                                while ($row = $NOTIFICATION_QUERY_STMT->fetch(PDO::FETCH_OBJ)) { ?>
                                                    <?php
                                                    $_user_data = $conn->query("SELECT * FROM users WHERE UserID = '{$row->hasUseID}'")->fetchObject();
                                                    ?>
                                                    <div class="card mb-2">
                                                        <div class="card-body">
                                                            <div class="d-flex mb-1">
                                                                <div class="flex-shrink-0"><img src="<?php echo $_user_data->ProfileImage; ?>" width="40" height="40" style="object-fit: cover;" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'"  alt="user-image" class="user-avtar wid-35"></div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <div style="display: flex;">
                                                                        <h6 class="mb-1" style="font-size: 15px;"><b><?php echo $_user_data->Name; ?></b> </h6> <span style="margin-left: 7px; color: gray !important"> <?php echo TimeAgo($row->Date); ?></span>

                                                                    </div>
                                                                    <span>
                                                                        <?php echo $row->Notification; ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php  } ?>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="simplebar-placeholder" style="width: 434px; height: 894px;"></div>
                            </div>
                            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                            </div>
                            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                <div class="simplebar-scrollbar" style="height: 25px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
                            </div>
                        </div>
                        <div class="text-center py-2"><a href="<?php echo $LINK; ?>/notifications" class="link-danger">See Notifications</a></div>
                    </div>
                </li>
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0"  href="<?php echo $LINK; ?>/profile" ><img src="<?php echo $ProfilePicture; ?>" onerror="this.onerror=null;this.src='<?php echo $ProfilePlaceholder; ?>'" width="40" height="40" style="object-fit: cover; border-radius: 50%;" alt="user-image" class="useravtar"></a>
                    
                </li>
            </ul>
        </div>
    </div>
</header>
<div id="SetDataRange" class="modal fade" tabindex="-1" aria-labelledby="SetDataRangeLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="dateRangeForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">Set Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="input-daterange input-group" id="datepicker_range">
                            <input type="text" class="form-control text-left" name="dateRange" id="dateRangeInput" value="<?php echo $FromDate . ' to ' . $ToDate; ?>">
                        </div>
                        <div class="d-flex flex-wrap gap-2" style="padding-top: 10px;">
                            <button type="button" class="btn btn-secondary d-inline-flex" id="PreviousWeek">Previous</button>
                            <button type="button" class="btn btn-secondary d-inline-flex" id="CurrentWeek">Current</button>
                            <button type="button" class="btn btn-secondary d-inline-flex" id="NextWeek">Next</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="submitDateRange">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>