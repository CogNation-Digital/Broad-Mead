<head>
    <title><?php echo $TITLE; ?></title><!-- [Meta] -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo $TITLE; ?>">
    <meta name="author" content="Michael Musenege">
    <link rel="stylesheet" href="<?php echo $LINK; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo $LINK; ?>/assets/fonts/inter/inter.css" id="main-font-link">
    <link rel="stylesheet" href="<?php echo $LINK; ?>/assets/css/style-preset.css">
    <link rel="icon" href="<?php echo $ICON; ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo $LINK; ?>/assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="<?php echo $LINK; ?>/assets/fonts/feather.css">
    <link rel="stylesheet" href="<?php echo $LINK; ?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $LINK; ?>/assets/fonts/material.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fullcalendar.io/releases/fullcalendar/3.10.0/fullcalendar.min.css">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.15.6/apexcharts.min.js'></script>
    <script src='https://code.highcharts.com/highcharts.js'></script>
    <script src='https://code.highcharts.com/highcharts-more.js'></script>
    <script src='https://code.highcharts.com/modules/exporting.js'></script>
    <script src='https://code.highcharts.com/modules/accessibility.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Include Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

</head>
<style>
    body {
        user-select: none;
    }

    .table-responsive {
        height: 600px;
        overflow-y: scroll;
    }

    .ts-control {
        padding: 13px;
        border-radius: 10px;
        background-color: <?php echo ($theme == "dark") ? '#1c1c1c' : ''; ?> !important;
        border: 1px solid <?php echo ($theme == "dark") ? '#1c1c1c' : '#bec8d0'; ?>;
    }

    .ts-control input,
    .item {
        color: <?php echo ($theme == "dark") ? 'white' : 'black'; ?>;

    }

    .fc-prev-button,
    .fc-next-button {
        position: relative;
    }

    .fc-prev-button::before,
    .fc-next-button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .fc-prev-button::before {
        content: '<';
    }

    .fc-next-button::before {
        content: '>';
    }

    .fc-next-button {
        margin-left: 10px !important;
    }

    /* Apply to all scrollbars */
    ::-webkit-scrollbar {
        height: 5px;
        width: 5px;
        margin-bottom: 10px;
        /* width of the scrollbar */
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: transparent !important;
        border-radius: 10px;
        /* round corners */
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: var(--bs-primary);
        /* color of the scrollbar handle */
        border-radius: 10px;
        /* round corners */
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
        /* color of the scrollbar handle on hover */
    }

    <?php if (isset($_COOKIE['USERID'])) : ?>body {
        zoom: 90%;
    }

    <?php endif; ?>
</style>