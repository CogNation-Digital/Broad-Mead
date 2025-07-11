<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo $LINK; ?>/assets/js/plugins/clipboard.min.js"></script>
<script src="<?php echo $LINK; ?>/assets/js/component.js"></script>
<script src="<?php echo $LINK; ?>/assets/js/plugins/popper.min.js"></script>
<script src="<?php echo $LINK; ?>/assets/js/plugins/simplebar.min.js"></script>
<script src="<?php echo $LINK; ?>/assets/js/plugins/bootstrap.min.js"></script>
<script src="<?php echo $LINK; ?>/assets/js/fonts/custom-font.js"></script>
<script src="<?php echo $LINK; ?>/assets/js/plugins/feather.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2/dist/js/tom-select.complete.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/lib/moment.min.js"></script>
<script src="https://fullcalendar.io/releases/fullcalendar/3.10.0/fullcalendar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>
    flatpickr("#weekendingDate", {
        dateFormat: "Y-m-d",
        disable: [
            // Disable all days EXCEPT Sunday (day 0)
            function(date) {
                return (date.getDay() !== 0); // 0 = Sunday
            }
        ],
        minDate: "today" // Optional: Disable past dates
    });

    function layout_change(theme) {


        $.ajax({
            url: window.location.href,
            method: "POST",
            data: {
                theme: theme
            },
            success: function(data) {
                window.location.href = "<?php echo $PageURL; ?>";
            }
        })
    }

    <?php if (isset($_COOKIE['USERID'])): ?>


        document.addEventListener('DOMContentLoaded', function() {

            const dateRangeInput = document.querySelector('#datepicker_range input');
            const previousButton = document.querySelector('#PreviousWeek');
            const currentButton = document.querySelector('#CurrentWeek');
            const nextButton = document.querySelector('#NextWeek');

            const flatpickrInstance = flatpickr("#datepicker_range input", {
                mode: "range",
                dateFormat: "Y-m-d",
                onClose: function(selectedDates, dateStr, instance) {
                    const [from, to] = dateStr.split(" to ").map(dateStr => new Date(dateStr));
                    fromDate = from;
                    toDate = to;
                }
            });

            let fromDate = null;
            let toDate = null;

            const updateDateRangeInput = () => {
                if (fromDate && toDate) {
                    dateRangeInput.value = `${fromDate.toISOString().slice(0, 10)} to ${toDate.toISOString().slice(0, 10)}`;
                    flatpickrInstance.setDate([fromDate, toDate]);
                }
            };

            const getMonday = (date) => {
                const day = date.getDay();
                const diff = date.getDate() - day + (day === 0 ? -6 : 1); // adjust when day is Sunday
                return new Date(date.setDate(diff));
            };

            const getSunday = (monday) => {
                return new Date(monday.getFullYear(), monday.getMonth(), monday.getDate() + 6);
            };

            previousButton.addEventListener('click', () => {
                if (!fromDate || !toDate) {
                    return;
                }
                fromDate.setDate(fromDate.getDate() - 7);
                toDate.setDate(toDate.getDate() - 7);
                updateDateRangeInput();
            });

            currentButton.addEventListener('click', () => {
                const now = new Date();
                fromDate = getMonday(now);
                toDate = getSunday(fromDate);
                updateDateRangeInput();
            });

            nextButton.addEventListener('click', () => {
                if (!fromDate || !toDate) {
                    return;
                }
                fromDate.setDate(fromDate.getDate() + 7);
                toDate.setDate(toDate.getDate() + 7);
                updateDateRangeInput();
            });

        });


    <?php endif; ?>

    function ShowToast(message) {

        var toastElement = document.querySelector('.ToastContent');
        toastElement.classList.add('show');
        document.querySelector('#ResponseMessage').textContent = message


        setTimeout(function() {
            toastElement.classList.remove('show');
        }, 3000);
    }


    const searchInput = document.getElementById("#searchInput");
    document.getElementById('searchInput').addEventListener('keyup', function() {
        var filter = this.value.toUpperCase();
        var table = document.querySelector('.table tbody');
        var tr = table.getElementsByTagName('tr');
        var found = false;

        for (var i = 0; i < tr.length; i++) {
            var tdClientType = tr[i].getElementsByTagName('td')[2];
            var tdLocation = tr[i].getElementsByTagName('td')[3];
            var tdJobRole = tr[i].getElementsByTagName('td')[4];

            if (tdClientType || tdLocation || tdJobRole) {
                var clientTypeText = tdClientType.textContent || tdClientType.innerText;
                var locationText = tdLocation.textContent || tdLocation.innerText;
                var jobRoleText = tdJobRole.textContent || tdJobRole.innerText;

                if (clientTypeText.toUpperCase().indexOf(filter) > -1 ||
                    locationText.toUpperCase().indexOf(filter) > -1 ||
                    jobRoleText.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = '';
                    found = true;
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }

        var existingAlert = document.getElementById('searchAlert');
        if (existingAlert) {
            existingAlert.parentNode.removeChild(existingAlert);
        }

        if (!found) {
            var alertDiv = document.createElement('div');
            alertDiv.id = 'searchAlert';
            alertDiv.className = 'alert alert-danger';
            alertDiv.textContent = 'No results found.';

            var container = document.querySelector('.table-responsive');
            container.appendChild(alertDiv);
        }
    });




    document.addEventListener('DOMContentLoaded', function() {
        // Select all elements with class .select-input
        var selectInputs = document.querySelectorAll('.select-input');

        // Initialize TomSelect for each select input
        selectInputs.forEach(function(selectInput) {
            new TomSelect(selectInput, {
                create: false, // Disable creation of new items
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        });
    });


    flatpickr('#PeriodRange', {
        mode: 'range',
        dateFormat: 'd F Y',
        onClose: function(selectedDates, dateStr, instance) {
            var fromDate = selectedDates[0];
            var toDate = selectedDates[1];
            if (fromDate && toDate) {
                document.getElementById('PeriodRange').value = formatDate(fromDate) + ' to ' + formatDate(toDate);
            }
        }
    });

    function formatDate(date) {
        var options = {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        };
        return date.toLocaleDateString('en-GB', options);
    }

    function FormatDate(dateStr) {
        return dateStr;
    }

    $(document).ready(function() {
        $('.delete-entry, .select-entry-row').on('click', function() {

            $(".checkbox-item").prop('checked', false);
            var row = $(this).closest('tr');
            var checkbox = row.find('.checkbox-item');
            checkbox.prop('checked', true);
        });
    });
    let inactivityTime = 20 * 60 * 1000;
    let inactivityTimer;

    function sendLogoutRequest() {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                logout: true,
                hasLoggedOut: true
            },
            success: function(response) {

                window.location.href = "/login";
            },
            error: function(xhr, status, error) {
                console.error("Logout failed: ", status, error);
            }
        });
    }

    // Function to log the user out
    function logoutUser() {
        sendLogoutRequest(); // Send the AJAX request to log out
    }

    // Function to reset the inactivity timer
    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        inactivityTimer = setTimeout(logoutUser, inactivityTime);
    }

    // Listen for any activity (mouse movement, key press, etc.)
    window.onload = resetInactivityTimer;
    document.onmousemove = resetInactivityTimer;
    document.onkeypress = resetInactivityTimer;
    document.ontouchstart = resetInactivityTimer; // For mobile devices
</script>