<?php if (isset($response)) : ?>
    <div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
        <div class="toast text-white <?php echo ($error == 1) ? "bg-danger" : "bg-success"; ?> fade show" role="alert" aria-live="assertive" aria-atomic="true">
            <div style="position: absolute; left: 90%">
                <a href="" style="color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M6.4 19L5 17.6l5.6-5.6L5 6.4L6.4 5l5.6 5.6L17.6 5L19 6.4L13.4 12l5.6 5.6l-1.4 1.4l-5.6-5.6z" />
                    </svg>
                </a>
            </div>

            <div class="d-flex">

                <div class="toast-body">
                    <?php
                    // Check if $response ends with a period and remove it if present
                    $response = rtrim($response, '.');



                    // Echo the response
                    echo $response;
                    ?>. <?php echo ($error == 0) ? "Please wait..." : "" ?>

                </div>
            </div>
        </div>
    </div>
    <?php if ($error == 0) : ?>
        <script>
            // Select the toast element
            var toastElement = document.querySelector('.toast');

            setTimeout(function() {
                toastElement.classList.remove('show');
                toastElement.parentElement.remove();
                window.location.href = '<?php echo $current_url; ?>'

            }, 2000);
        </script>
    <?php endif; ?>

<?php endif; ?>

<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
    <div class="toast text-white bg-primary fade ToastContent" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <span id="ResponseMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>