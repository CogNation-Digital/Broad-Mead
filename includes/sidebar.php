<?php
$dashboard_pages = ["home", "report", "expired_documents"];
$key_job_area_pages = ["key_job_area", "create_key_job_area", "view_key_job_area", "edit_key_job_area"];
$kpis_pages = ["weekly_kpis", "create_weekly_kpis", "edit_weekly_kpis", "view_weekly_kpis"];
$calender_pages = ['calendar'];
$clients_page = ['clients', 'create_client', 'edit_client', 'view_client'];
$candidates_page = ['candidates', 'create_candidate', 'edit_candidate', 'view_candidate'];
$interview_page = ['interviews', 'create_interview'];
$vacancy_pages = ['vacancies', 'create_vacancy', 'edit_vacancy', 'view_vacancy'];
$shifts_pages = ['shifts', 'create_shifts', 'edit_shifts'];
$timesheets_pages = ['timesheets', 'create_timesheets', 'generate_timesheet'];
$invoices_pages = ['invoices', 'create_invoices'];
?>
<nav class="pc-sidebar">
   <div class="navbar-wrapper">
      <div class="m-header">
         <a href="" class="b-brand text-primary">
            <center>
               <img src="<?php echo $ICON; ?>" style="width: 50px;" class="img-fluid logo-lg" alt="logo">
               <span class="badge bg-light-success rounded-pill ms-2 theme-version">v3.0 beta</span>
            </center>
         </a>
      </div>
      <div class="navbar-content pc-trigger simplebar-scrollable-y" data-simplebar="init">
         <div class="simplebar-wrapper" style="margin: -10px 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
               <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
               <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                  <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                     <div class="simplebar-content" style="padding: 10px 0px;">
                        <div class="card pc-user-card">
                           <div class="card-body">
                              <div class="d-flex align-items-center" style="cursor: pointer;" data-bs-toggle="collapse" href="#pc_sidebar_userlink">
                                 <div class="flex-shrink-0">
                                    <img src="<?php echo $ProfilePicture; ?>" width="50" height="50" style="object-fit: cover;" alt="user-image" class="user-avtar rounded-circle">
                                 </div>
                                 <div class="flex-grow-1 ms-3 me-2">
                                    <h6 class="mb-0" style="font-size: 12px;"><?php echo TruncateText($NAME, 20); ?></h6>
                                    <span class="mb-0" style="font-size: 10px;"><?php echo TruncateText($Position, 20); ?></span>
                                 </div>

                              </div>
                              <div class="collapse pc-user-links" id="pc_sidebar_userlink">
                                 <div class="pt-3">
                                    <a href="<?php echo $LINK; ?>/profile">
                                       <i class="ti ti-settings"></i>
                                       <span>Profile</span>
                                    </a>
                                    <a href="<?php echo $LINK; ?>/notifications">
                                       <i class="ti ti-bell"></i>
                                       <span>Notifications <span class="badge bg-danger"><?php echo $NOTIFICATION_COUNT; ?></span></span>
                                    </a>

                                    <a href="<?php echo $LINK; ?>/emails">
                                       <i class="ti ti-mail"></i>
                                       <span>Emails</span>
                                    </a>
                                    <a href="<?php echo $LINK; ?>/activity_log">
                                       <i class="ti ti-history"></i>
                                       <span>Activity Log</span>
                                    </a>
                                    <a href="<?php echo $LINK; ?>/users">
                                       <i class="ti ti-users"></i>
                                       <span>Users</span>
                                    </a>
                                    <a href="<?php echo $LINK; ?>/home?logout=true">
                                       <i class="ti ti-power"></i>
                                       <span>Logout</span>
                                    </a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <ul class="pc-navbar" style="display: block;">
                           <li class="pc-item pc-caption"><label>Navigation</label></li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $dashboard_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/home" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="m12 3l8 6v12h-5v-7H9v7H4V9z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Dashboard</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                              <ul class="pc-submenu" style="display: <?php echo in_array($current_page, $dashboard_pages) ? 'block' : 'none'; ?> ;">
                                 <li class="pc-item"><a class="pc-link" href="<?php echo $LINK; ?>/home">Home</a></li>
                                 <li class="pc-item"><a class="pc-link" href="<?php echo $LINK; ?>/report">Reports</a></li>
                                 <li class="pc-item"><a class="pc-link" href="<?php echo $LINK; ?>/expired_documents">Expired Documents</a></li>
                              </ul>
                           </li>
                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $key_job_area_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/key_job_area" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="m17 21l-2.75-3l1.16-1.16L17 18.43l3.59-3.59l1.16 1.41M12.8 21H5c-1.11 0-2-.89-2-2V5c0-1.11.89-2 2-2h14c1.11 0 2 .89 2 2v7.8c-.61-.35-1.28-.6-2-.72V5H5v14h7.08c.12.72.37 1.39.72 2m-.8-4H7v-2h5m2.68-2H7v-2h10v1.08c-.85.14-1.63.46-2.32.92M17 9H7V7h10" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Key Job Areas</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>
                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $kpis_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/weekly_kpis" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="M15.5 12c2.5 0 4.5 2 4.5 4.5c0 .88-.25 1.71-.69 2.4l3.08 3.1L21 23.39l-3.12-3.07c-.69.43-1.51.68-2.38.68c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5m0 2a2.5 2.5 0 0 0-2.5 2.5a2.5 2.5 0 0 0 2.5 2.5a2.5 2.5 0 0 0 2.5-2.5a2.5 2.5 0 0 0-2.5-2.5M5 3h14c1.11 0 2 .89 2 2v8.03c-.5-.8-1.19-1.49-2-2.03V5H5v14h4.5c.31.75.76 1.42 1.31 2H5c-1.11 0-2-.89-2-2V5c0-1.11.89-2 2-2m2 4h10v2H7zm0 4h5.03c-.8.5-1.49 1.19-2.03 2H7zm0 4h2.17c-.11.5-.17 1-.17 1.5v.5H7z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Weekly KPIs</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $calender_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/calendar" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="M7 11h2v2H7zm14-6v14c0 1.11-.89 2-2 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h1V1h2v2h8V1h2v2h1a2 2 0 0 1 2 2M5 7h14V5H5zm14 12V9H5v10zm-4-6v-2h2v2zm-4 0v-2h2v2zm-4 2h2v2H7zm8 2v-2h2v2zm-4 0v-2h2v2z"></path>
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Calendar</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $clients_page) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/clients" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="M16 17v2H2v-2s0-4 7-4s7 4 7 4m-3.5-9.5A3.5 3.5 0 1 0 9 11a3.5 3.5 0 0 0 3.5-3.5m3.44 5.5A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4M15 4a3.4 3.4 0 0 0-1.93.59a5 5 0 0 1 0 5.82A3.4 3.4 0 0 0 15 11a3.5 3.5 0 0 0 0-7" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Clients</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $candidates_page) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/candidates" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="M13.07 10.41a5 5 0 0 0 0-5.82A3.4 3.4 0 0 1 15 4a3.5 3.5 0 0 1 0 7a3.4 3.4 0 0 1-1.93-.59M5.5 7.5A3.5 3.5 0 1 1 9 11a3.5 3.5 0 0 1-3.5-3.5m2 0A1.5 1.5 0 1 0 9 6a1.5 1.5 0 0 0-1.5 1.5M16 17v2H2v-2s0-4 7-4s7 4 7 4m-2 0c-.14-.78-1.33-2-5-2s-4.93 1.31-5 2m11.95-4A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4Z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Candidates</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $interview_page) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/interviews" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zm4 18H6V4h7v5h5z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Interview</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $vacancy_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/vacancies" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="m19.31 18.9l3.08 3.1L21 23.39l-3.12-3.07c-.69.43-1.51.68-2.38.68c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5s4.5 2 4.5 4.5c0 .88-.25 1.71-.69 2.4m-3.81.1a2.5 2.5 0 0 0 0-5a2.5 2.5 0 0 0 0 5M21 4v2H3V4zM3 16v-2h6v2zm0-5V9h18v2h-2.03c-1.01-.63-2.2-1-3.47-1s-2.46.37-3.47 1z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Vacancy</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $shifts_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/shifts" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="M8 14q-.425 0-.712-.288T7 13t.288-.712T8 12t.713.288T9 13t-.288.713T8 14m4 0q-.425 0-.712-.288T11 13t.288-.712T12 12t.713.288T13 13t-.288.713T12 14m4 0q-.425 0-.712-.288T15 13t.288-.712T16 12t.713.288T17 13t-.288.713T16 14M5 22q-.825 0-1.412-.587T3 20V6q0-.825.588-1.412T5 4h1V3q0-.425.288-.712T7 2t.713.288T8 3v1h8V3q0-.425.288-.712T17 2t.713.288T18 3v1h1q.825 0 1.413.588T21 6v14q0 .825-.587 1.413T19 22zm0-2h14V10H5z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Shifts</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $timesheets_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/timesheets" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="M5 3c-1.11 0-2 .89-2 2v14c0 1.11.89 2 2 2h14c1.11 0 2-.89 2-2V5c0-1.11-.89-2-2-2zm0 2h14v14H5zm2 2v2h10V7zm0 4v2h10v-2zm0 4v2h7v-2z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Timesheets</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>

                           <li class="pc-item pc-hasmenu <?php echo in_array($current_page, $invoices_pages) ? 'active' : ''; ?>">
                              <a href="<?php echo $LINK; ?>/invoices" class="pc-link">
                                 <span class="pc-micon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                       <path fill="currentColor" d="m12 20l1.3.86c-.2-.58-.3-1.21-.3-1.86c0-.24 0-.5.04-.71L12 17.6l-3 2l-3-2l-1 .66V5h14v8c.7 0 1.37.12 2 .34V3H3v19l3-2l3 2zm5-11V7H7v2zm-2 4v-2H7v2zm.5 6l2.75 3L23 17.23l-1.16-1.41l-3.59 3.59l-1.59-1.59z" />
                                    </svg>
                                 </span>
                                 <span class="pc-mtext">Invoices</span>
                                 <span class="pc-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                       <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                 </span>
                              </a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div class="simplebar-placeholder" style="width: 280px; height: 2870px;"></div>
         </div>
         <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
         </div>
         <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar" style="height: 25px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
         </div>
      </div>
   </div>
</nav>