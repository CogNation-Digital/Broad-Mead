<?php
include "../includes/config.php";



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// // includes
// include('includes/header.php');
// include('includes/navbar.php');
// include('includes/scripts.php');
// include('includes/footer.php');


// Check if the form has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['email']) && isset($data['messages'])) {
        $name = $data['name'];
        $email = $data['email'];
        $phone = $data['tel'];
        $subject = $data['subject'];
        $message = $data['messages'];

        $sql = "INSERT INTO contacts (name, email, phone, subject, message, date) VALUES (:name, :email, :phone, :subject, :message, NOW())";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'There was an error sending your message. Please try again.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    }
    exit; // Prevent further output after JSON response
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $TITLE; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo $ICON; ?>">

    <!-- Custom CSS -->
    <link href="<?php echo $LINK; ?>/landing/assets/css/styles.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sendmessage').addEventListener('click', function(event) {
                event.preventDefault();

                let form = document.getElementById('contact-form');
                let formData = new FormData(form);

                // Check form validity
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                let data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                let jsonData = JSON.stringify(data);

                let xhr = new XMLHttpRequest();
                xhr.open('POST', window.location.href, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('Content-Type', 'application/json');

                xhr.onload = function() {
                    try {
                        let response = JSON.parse(xhr.responseText);
                        let responseSpan = form.querySelector('span');
                        if (xhr.status === 200 && response.status === 'success') {
                            responseSpan.textContent = response.message;
                            responseSpan.style.color = 'green';
                        } else {
                            responseSpan.textContent = response.message;
                            responseSpan.style.color = 'red';
                        }
                    } catch (e) {
                        console.error('Invalid JSON response:', xhr.responseText);
                        let responseSpan = form.querySelector('span');
                        responseSpan.textContent = 'An unexpected error occurred. Please try again.';
                        responseSpan.style.color = 'red';
                    }
                };

                xhr.onerror = function() {
                    let responseSpan = form.querySelector('span');
                    responseSpan.textContent = 'There was a network error. Please try again.';
                    responseSpan.style.color = 'red';
                };

                xhr.send(jsonData);
            });
        });
    </script>
</head>
<style>
    .primary-text {
        color: #845adf;
        font-weight: 700;
    }
</style>

<body>

    <div id="main-wrapper">
        <!-- Start Navigation -->
        <div class="header header-transparent dark">
            <div class="container">
                <nav id="navigation" class="navigation navigation-landscape">
                    <div class="nav-header">
                        <a class="nav-brand" href="#"><img src="<?php echo $LOGO; ?>" style="width: 100px;" class="logo" alt=""></a>
                        <div class="nav-toggle"></div>

                    </div>
                    <div class="nav-menus-wrapper" style="transition-property: none;">
                        <ul class="nav-menu">

                            <li><a href="#home">Home<span class="submenu-indicator"></span></a></li>
                            <li><a href="#about">About<span class="submenu-indicator"></span></a></li>
                            <li><a href="#features">Features<span class="submenu-indicator"></span></a></li>
                            <li><a href="#contact">Contact Us<span class="submenu-indicator"></span></a></li>

                        </ul>

                        <ul class="nav-menu nav-menu-social align-to-right">
                            <li class="list-buttons ms-2">
                                <a href="<?php echo $LINK; ?>/login" class="bg-primary"><i class="fa-solid fa-user me-2"></i>Sign In / Sign Up</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- End Navigation -->

        <!-- Side Canvas -->

        <!-- ============================================================== -->
        <!-- Top header  -->
        <!-- ============================================================== -->


        <!-- ============================ Hero Banner  Start================================== -->
        <div class="image-cover hero-header bg-white">
            <div class="container">

                <div class="row justify-content-between align-items-center">
                    <div class="col-xl-7 col-lg-6 col-md-6 col-sm-12">
                        <div class="text-purple d-inline-flex align-items-center text-sm-muted py-2 px-2 rounded-5 border"><span class="label bg-purple text-light rounded-5 me-2">New version</span> Broadmead 3.0</div>
                        <h1 class="mb-4">Recruitment <span class="primary-text">Simplified!</span></h1>
                        <p class="fs-5 fw-light fs-mob">
                            Broadmead is an Applicant Tracking and Customer Relationship Management System designed for recruitment firms. It replaces tedious spreadsheets with a smart, cloud-based solution, allowing recruitment teams to automate processes. At Broadmead, we believe in keeping the recruitment process 'Simplified'.
                        </p>
                        <div class="features-groupss mt-4 mb-2">
                            <ul class="row gx-3 gy-4 p-0">
                                <li class="font--medium col-xl-6 col-lg-6 col-6"><span class="square--30 circle d-inline-flex align-items-center justify-content-center text-info bg-light-info me-2"><i class="fa-solid fa-check"></i></span>Simplified</li>
                                <li class="font--medium col-xl-6 col-lg-6 col-6"><span class="square--30 circle d-inline-flex align-items-center justify-content-center text-info bg-light-info me-2"><i class="fa-solid fa-check"></i></span>Friendly User interface</li>
                            </ul>
                        </div>
                        <div class="btn-goups py-4 mt-5 mx-3">
                            <a href="#contact" class="btn btn-primary h-auto shadow my-1">
                                <div class="d-inline-flex align-items-center justify-content-start py-1">
                                    <div class="btn-icons">
                                        <span class="svg-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="m9.55 18l-5.7-5.7l1.425-1.425L9.55 15.15l9.175-9.175L20.15 7.4z" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ps-3 text-start">
                                        <h6 class="m-0 text-light fw-medium">Get Started</h6>
                                    </div>
                                </div>
                            </a>
                            <a href="<?php echo $LINK; ?>/login" class="btn btn-dark h-auto shadow ms-md-3 my-1">
                                <div class="d-inline-flex align-items-center justify-content-start py-1">
                                    <div class="btn-icons">
                                        <span class="svg-icon text-light">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ps-3 text-start">
                                        <h6 class="m-0 text-light fw-medium">Sign In</h6>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-xl-5 col-lg-6 col-md-6 col-sm-12">
                        <div class="position-relative mt-md-0 mt-4">
                            <img src="<?php echo $LINK; ?>/assets/images/file-searching.svg" class="img-fluid">
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <section>
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-xl-6 col-lg-10 col-md-12 col-sm-12 mb-3">
                        <div class="sec-heading center">
                            <div class="d-inline-flex px-4 py-1 rounded-5 text-sm-muted text-success bg-light-success mb-2"><span>Why Broadmead</span></div>
                            <h2>Best Way To Simplify Recruitment</h2>
                        </div>
                    </div>
                </div>

                <div class="row g-xl-4 g-3  justify-content-center">

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                        <div class="card border rounded-3 px-3 py-4 d-flex flex-column justify-content-center">
                            <div class="d-block mb-3">
                                <span class="svg-icon text-success svg-icon-2hx">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M13.07 10.41a5 5 0 0 0 0-5.82A3.4 3.4 0 0 1 15 4a3.5 3.5 0 0 1 0 7a3.4 3.4 0 0 1-1.93-.59M5.5 7.5A3.5 3.5 0 1 1 9 11a3.5 3.5 0 0 1-3.5-3.5m2 0A1.5 1.5 0 1 0 9 6a1.5 1.5 0 0 0-1.5 1.5M16 17v2H2v-2s0-4 7-4s7 4 7 4m-2 0c-.14-.78-1.33-2-5-2s-4.93 1.31-5 2m11.95-4A5.32 5.32 0 0 1 18 17v2h4v-2s0-3.63-6.06-4Z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="explio-text">
                                <h5 class="lh-base">Manage Candidates Easily</h5>
                                <p class="m-0">
                                    Create detailed candidate profiles and effortlessly manage their shifts, timesheets, emails, payouts, and interviews.
                                <p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                        <div class="card border rounded-3 px-3 py-4 d-flex flex-column justify-content-center">
                            <div class="d-block mb-3">
                                <span class="svg-icon text-warning svg-icon-2hx">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M18 15h-2v2h2m0-6h-2v2h2m2 6h-8v-2h2v-2h-2v-2h2v-2h-2V9h8M10 7H8V5h2m0 6H8V9h2m0 6H8v-2h2m0 6H8v-2h2M6 7H4V5h2m0 6H4V9h2m0 6H4v-2h2m0 6H4v-2h2m6-10V3H2v18h20V7z" />
                                    </svg>
                                </span>
                            </div>
                            <div class="explio-text">
                                <h5 class="lh-base">Efficient Client Management </h5>
                                <p class="m-0">
                                    Manage your clients, interviews, sending emails, and creating vacancies, shifts, timesheet and invoices.
                                <p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                        <div class="card border rounded-3 px-3 py-4 d-flex flex-column justify-content-center">
                            <div class="d-block mb-3">
                                <span class="svg-icon text-info svg-icon-2hx">
                                    <svg width="50" height="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.6 5.59998L20.9 10.9C21.3 11.3 21.3 11.9 20.9 12.3L17.6 15.6L11.6 9.59998L15.6 5.59998ZM2.3 12.3L7.59999 17.6L11.6 13.6L5.59999 7.59998L2.3 10.9C1.9 11.3 1.9 11.9 2.3 12.3Z" fill="currentColor" />
                                        <path opacity="0.3" d="M17.6 15.6L12.3 20.9C11.9 21.3 11.3 21.3 10.9 20.9L7.59998 17.6L13.6 11.6L17.6 15.6ZM10.9 2.3L5.59998 7.6L9.59998 11.6L15.6 5.6L12.3 2.3C11.9 1.9 11.3 1.9 10.9 2.3Z" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                            <div class="explio-text">
                                <h5 class="lh-base">Reports & Dashboard </h5>
                                <p class="m-0">
                                    Simplified Dashboards Report and Weekly Overall Reports to have insights into every aspect of your business
                                <p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- ============================ Our Features Start ================================== -->


        <!-- ============================ Varity Of Dental Start ================================== -->
        <section class="pt-0">
            <div class="container">
                <div class="row align-items-center justify-content-between">


                    <div class="col-xl-5 col-lg-6 col-md-6">
                        <div class="position-relative animated fadeInRight">
                            <img src="<?php echo $LINK; ?>/landing/assets/img/custom-img/cr-2.png" class="img-fluid" alt="">
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6" id="features">
                        <div class="d-block position-relative">
                            <h2 class="lh-base animated fadeInLeft">More Features</h2>
                            <div class="position-relative features-slec mt-4">
                                <div class="position-relative mt-4">
                                    <div class="d-block mb-4">
                                        <div style="display: flex;">
                                            <div class="d-inline-flex label bg-purple text-light rounded-5 fs-6 py-2 px-3 mb-2"><strong>01</strong> </div>
                                            <h3 style="margin-left: 10px;">Bulk Email</h3>
                                        </div>
                                        <p>
                                            Easily send bulk emails to clients and candidates. Create and manage email templates and lists from client and candidate data for seamless communication.
                                        </p>
                                    </div>
                                    <div class="d-block mb-4">
                                        <div style="display: flex;">
                                            <div class="d-inline-flex label bg-success text-light rounded-5 fs-6 py-2 px-3 mb-2"><strong>02</strong> </div>
                                            <h3 style="margin-left: 10px;">Vacancy Management </h3>
                                        </div>
                                        <p>
                                            Save and manage unlimited vacancies from clients. Email candidates about vacancies that match their qualifications and experience.
                                        </p>
                                    </div>
                                    <div class="d-block mb-4">
                                        <div style="display: flex;">
                                            <div class="d-inline-flex label bg-danger text-light rounded-5 fs-6 py-2 px-3 mb-2"><strong>03</strong> </div>
                                            <h3 style="margin-left: 10px;">Shifts & Timesheets </h3>
                                        </div>
                                        <p>
                                            Easily manage and track candidate shifts and timesheets. Automate client billing, candidate payouts, and margin calculations.
                                        </p>
                                    </div>

                                    <div class="d-block mb-4">
                                        <div style="display: flex;">
                                            <div class="d-inline-flex label bg-warning text-light rounded-5 fs-6 py-2 px-3 mb-2"><strong>04</strong> </div>
                                            <h3 style="margin-left: 10px;">Task and Events Management </h3>
                                        </div>
                                        <p>
                                            Quickly create and manage tasks and events for candidates, clients, and consultants. Small but highly effective!
                                        </p>
                                    </div>

                                    <div class="d-block mb-4">
                                        <div style="display: flex;">
                                            <div class="d-inline-flex label bg-primary text-light rounded-5 fs-6 py-2 px-3 mb-2"><strong>04</strong> </div>
                                            <h3 style="margin-left: 10px;">Generate & Track Invoices </h3>
                                        </div>
                                        <p>
                                            Easily generate and track client invoices, including unpaid ones, all in one place. Use the automated email template to easily send invoices to your clients.
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section>
            <div class="container" id="contact">

                <div class="row justify-content-between">

                    <div class="col-lg-4 pe-xl-5 pe-lg-4">
                        <h1 class="display-2 font--bold">Contacts</h1>
                        <p class="fs-5 pb-4 mb-0 mb-sm-2">Get in touch with us by droping messages or call us now</p>
                    </div>
                    <div class="col-lg-8 col-xl-7 offset-xl-1">
                        <form id="contact-form" class="row g-4" novalidate="true">
                            <div class="col-sm-6">
                                <label class="form-label">Name</label>
                                <input class="form-control" type="text" name="name" placeholder="Your name" required="">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" placeholder="Your Email" required="">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Phone</label>
                                <input class="form-control" type="tel" name="tel" placeholder="Your Phone" required="">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Subject</label>
                                <input class="form-control" type="text" name="subject" placeholder="Subject" required="">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Message</label>
                                <textarea class="form-control" rows="4" name="messages" placeholder="Type your message here..." required=""></textarea>
                            </div>
                            <span>
                                <!-- message response goes here -->
                            </span>
                            <div class="col-12">
                                <button class="btn btn-primary px-lg-4" type="submit" id="sendmessage">Send your Message</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </section>

        <section>
            <div class="container">
                <div class="row justify-content-center g-4">

                    <div class="col-lg-4 col-xl-4 col-md-12">
                        <div class="card border-0 p-4 rounded-3">
                            <h2 class="h4 text-dark font--bold mb-4">United Kingdom</h2>
                            <ul class="p-0 m-0">
                                <li class="d-flex mb-3"><i class="fa-solid fa-phone text-primary fs-5 me-2"></i><a class="text-muted" href="tel:++44 7886 027990">+44 7886 027990</a></li>
                                <li class="d-flex mb-3"><i class="fa-solid fa-envelope fs-5 text-primary me-2"></i><a class="text-muted" href="mailto:themezhub@support.com">info@broad-mead.com</a></li>
                                <li class="d-flex mb-3"><i class="fa-solid fa-location-pin text-primary fs-5 me-2"></i><span class="text-muted">71-75 Shelton Street, WC2H 9JQ, London, <br> United Kingdom</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-8 col-xl-8 col-md-12">
                        <div class="card border-0 p-4 rounded-3">
                            <h2 class="h4 text-dark font--bold mb-4">Google Map</h2>
                            <ul class="p-0 m-0">
                                <div class="card-body p-0"> <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2479.742526262064!2d0.1471645965697983!3d51.5729534229877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xaabbeb2ee6d25c77%3A0x7a5ce8afee000179!2sNocturnal%20Recruitment!5e0!3m2!1sen!2szm!4v1704226368882!5m2!1sen!2szm" width="800" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> </div>

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <footer class="footer skin-dark-footer" id="about">


            <div class="footer-bottom">
                <div class="container">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-xl-4 col-lg-5 col-md-5" style="margin-top: 20px;">
                            <p class="mb-0">© <?php echo date("Y"); ?> Broadmead 3.0 Developed by Cog-Nation.</p>
                        </div>


                    </div>
                </div>
            </div>
        </footer>
        <!-- ============================ Footer End ================================== -->

        <!-- Log In Modal -->

        <!-- End Modal -->


        <a id="back2Top" class="top-scroll" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>


    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->


    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="<?php echo $LINK; ?>/landing/assets/js/jquery.min.js"></script>


</body>

</html>