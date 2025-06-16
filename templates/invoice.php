<?php
include "../includes/config.php";
$InvoiceID = $_GET['ID'];

$query = "SELECT * FROM `_invoices` WHERE InvoiceID = '$InvoiceID' ";
$stmt = $conn->query($query);
$InvoiceData = $stmt->fetch(PDO::FETCH_OBJ);


$ClientKeyID = $InvoiceData->ClientKeyID;
$ClientID = empty($InvoiceData->hasBranchID) ? $InvoiceData->hasClientID : $InvoiceData->hasBranchID;
$fetch_client_stmt = $conn->prepare("SELECT * FROM _clients WHERE ClientID = :ClientID");
$fetch_client_stmt->bindParam(':ClientID', $ClientID, PDO::PARAM_STR);
$fetch_client_stmt->execute();
$ClientData = $fetch_client_stmt->fetchObject();



$BANKDATA = $conn->query("SELECT * FROM `bankaccounts` WHERE ClientKeyID = '$ClientKeyID'")->fetchObject();
$_CLIENT_DATA = $conn->query("SELECT * FROM organizations WHERE ClientKeyID = '$ClientKeyID'")->fetchObject();
  
 
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> </title>
<meta name="robots" content="noindex,nofollow" />
<meta name="viewport" content="width=device-width; initial-scale=1.0;" />
<style type="text/css">
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);

    body {
        margin: 0;
        padding: 0;
        background: #e1e1e1;
    }

    div,
    p,
    a,
    li,
    td {
        -webkit-text-size-adjust: none;
    }

    .ReadMsgBody {
        width: 100%;
        background-color: #ffffff;
    }

    .ExternalClass {
        width: 100%;
        background-color: #ffffff;
    }

    body {
        width: 100%;
        height: 100%;
        background-color: #e1e1e1;
        margin: 0;
        padding: 0;
        -webkit-font-smoothing: antialiased;
    }

    html {
        width: 100%;
    }

    p {
        padding: 0 !important;
        margin-top: 0 !important;
        margin-right: 0 !important;
        margin-bottom: 0 !important;
        margin-left: 0 !important;
    }

    .visibleMobile {
        display: none;
    }

    .hiddenMobile {
        display: block;
    }

    @media only screen and (max-width: 600px) {
        body {
            width: auto !important;
        }

        table[class=fullTable] {
            width: 96% !important;
            clear: both;
        }

        table[class=fullPadding] {
            width: 85% !important;
            clear: both;
        }

        table[class=col] {
            width: 45% !important;
        }

        .erase {
            display: none;
        }
    }

    @media only screen and (max-width: 420px) {
        table[class=fullTable] {
            width: 100% !important;
            clear: both;
        }

        table[class=fullPadding] {
            width: 85% !important;
            clear: both;
        }

        table[class=col] {
            width: 100% !important;
            clear: both;
        }

        table[class=col] td {
            text-align: left !important;
        }

        .erase {
            display: none;
            font-size: 0;
            max-height: 0;
            line-height: 0;
            padding: 0;
        }

        .visibleMobile {
            display: block !important;
        }

        .hiddenMobile {
            display: none !important;
        }
    }
</style>


<!-- Header -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tr>
        <td height="20"></td>
    </tr>
    <tr>
        <td>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 10px 10px 0 0;">
                <tr class="hiddenMobile">
                    <td height="40"></td>
                </tr>
                <tr class="visibleMobile">
                    <td height="30"></td>
                </tr>

                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                                <tr>
                                    <td>
                                        <table width="220" border="0" cellpadding="0" cellspacing="0" align="left" class="col">
                                            <tbody>
                                                <tr>
                                                    <td align="left"> <img src="<?php echo $_CLIENT_DATA->ProfileImage; ?>" height="50" alt="logo" border="0" /></td>
                                                </tr>
                                                <tr class="hiddenMobile">
                                                    <td height="40"></td>
                                                </tr>
                                                <tr class="visibleMobile">
                                                    <td height="20"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 12px; color: #5b5b5b; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top; text-align: left;">
                                                        <b>{message}</b>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table width="220" border="0" cellpadding="0" cellspacing="0" align="right" class="col">
                                            <tbody>
                                                <tr class="visibleMobile">
                                                    <td height="20"></td>
                                                </tr>
                                                <tr>
                                                    <td height="5"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 21px; color: black; letter-spacing: -1px; font-family: 'Open Sans', sans-serif; line-height: 1; vertical-align: top; text-align: right;">
                                                        Invoice
                                                    </td>
                                                </tr>
                                                <tr>
                                                <tr class="hiddenMobile">
                                                    <td height="50"></td>
                                                </tr>
                                                <tr class="visibleMobile">
                                                    <td height="20"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 12px; color: #5b5b5b; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top; text-align: right;">
                                                        <small>INV #</small><?php echo $InvoiceData->Number; ?> <br />
                                                        <small>Invoice Date: </small><?php echo FormatDate($InvoiceData->InvoiceDate); ?> <br />
                                                        <small>Due Date: </small><?php echo FormatDate($InvoiceData->DueDate); ?> <br />
                                                        <small>Status: </small><?php echo $InvoiceData->Status; ?> <br />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- /Header -->
<!-- Order Details -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
        <tr>
            <td>
                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                    <tbody>
                        <tr>
                        <tr class="hiddenMobile">
                            <td height="60"></td>
                        </tr>
                        <tr class="visibleMobile">
                            <td height="40"></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                                    <thead style="border: 1px solid red !important;">
                                        <tr>
                                            <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 10px 7px 0;" width="52%" align="left">
                                                Description
                                            </th>

                                            <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="center">
                                                Rate
                                            </th>
                                            <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #1e2b33; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="right">
                                                Hours
                                            </th>
                                            <th style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #1e2b33; font-weight: normal; line-height: 1; vertical-align: top; padding: 0 0 7px;" align="right">
                                                Total Amount
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $CandidateName = "";
                                        $n = 1;
                                        $subtotal = 0;
                                        $_invoice_query = "SELECT * FROM `_invoice_` WHERE InvoiceID = :InvoiceID";
                                        $_invoice_stmt = $conn->prepare($_invoice_query);
                                        $_invoice_stmt->bindParam(':InvoiceID', $InvoiceID);
                                        $_invoice_stmt->execute();
                                        while ($row = $_invoice_stmt->fetchObject()) {
                                            $total_amount = $row->Hours * $row->Rate;
                                            $subtotal += $total_amount;
                                            $CandidateName = $row->Name;
                                        ?>
                                            <tr>
                                                <td style="border-top: 1px solid lightgray;  font-size: 12px; font-family: 'Open Sans', sans-serif; color: black;  line-height: 18px;  vertical-align: top; padding:10px 0;" class="article">

                                                    <?php echo htmlspecialchars($row->Description); ?>
                                                </td>
                                                <td style="border-top: 1px solid lightgray;  font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="center"><?php echo $Currency; ?><?php echo number_format($row->Rate, 2); ?>/hr</td>
                                                <td style="border-top: 1px solid lightgray;  font-size: 12px; font-family: 'Open Sans', sans-serif; color: #1e2b33;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="right"><?php echo $row->Hours; ?></td>
                                                <td style="border-top: 1px solid lightgray;  font-size: 12px; font-family: 'Open Sans', sans-serif; color: #1e2b33;  line-height: 18px;  vertical-align: top; padding:10px 0;" align="right"><?php echo $Currency; ?><?php echo number_format($total_amount, 2); ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php
                                        $vat_rate = $BANKDATA->VatPercent / 100;
                                        $vat = $subtotal * $vat_rate;
                                        $grand_total = $subtotal + $vat;
                                        ?>
                                        <tr>
                                            <td style="border-top: 1px solid lightgray;"></td>
                                            <td style="border-top: 1px solid lightgray;"></td>
                                            <td style="border-top: 1px solid lightgray;"></td>
                                            <td style="border-top: 1px solid lightgray;"></td>
                                            <td style="border-top: 1px solid lightgray;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<!-- /Order Details -->
<!-- Total -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
        <tr>
            <td>
                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                    <tbody>
                        <tr>
                            <td>

                                <!-- Table Total -->
                                <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                                    <tbody>
                                        <tr>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                Subtotal
                                            </td>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; white-space:nowrap;" width="80">
                                                <?php echo $Currency; ?><?php echo number_format($subtotal, 2); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                Vat Percent
                                            </td>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #646a6e; line-height: 22px; vertical-align: top; text-align:right; ">
                                                <?php echo $BANKDATA->VatPercent; ?>%
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                <strong>Grand Total (Incl.Tax)</strong>
                                            </td>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #000; line-height: 22px; vertical-align: top; text-align:right; ">
                                                <strong><?php echo $Currency; ?><?php echo number_format($grand_total,2); ?></strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #b0b0b0; line-height: 22px; vertical-align: top; text-align:right; "><small>TAX</small></td>
                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #b0b0b0; line-height: 22px; vertical-align: top; text-align:right; ">
                                                <small><?php echo $Currency; ?><?php echo number_format($vat, 2); ?></small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- /Table Total -->

                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<!-- /Total -->
<!-- Information -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">
    <tbody>
        <tr>
            <td>
                <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff">
                    <tbody>
                        <tr>
                        <tr class="hiddenMobile">
                            <td height="60"></td>
                        </tr>
                        <tr class="visibleMobile">
                            <td height="40"></td>
                        </tr>
                        <tr>
                            <td>
                                <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table width="220" border="0" cellpadding="0" cellspacing="0" align="left" class="col">

                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size: 11px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                                                                <strong>TO:</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" height="10"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 20px; vertical-align: top; ">
                                                            <?php echo $ClientData->Name; ?><br>  <?php echo $ClientData->Address; ?>, <?php echo $ClientData->Postcode; ?>, <?php echo $ClientData->City; ?><br> <?php echo $ClientData->Number; ?><br> <?php echo $ClientData->Email; ?><br>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <table width="220" border="0" cellpadding="0" cellspacing="0" align="right" class="col">
                                                    <tbody>
                                                        <tr class="visibleMobile">
                                                            <td height="20"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 11px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                                                                <strong>BANK DETAILS</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" height="10"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 20px; vertical-align: top; ">
                                                            <?php echo $BANKDATA->Name; ?><br> <?php echo $BANKDATA->Bank; ?><br> <?php echo $BANKDATA->Branch; ?> <br> <a href="#" style="color: black; text-decoration:underline;"><?php echo $BANKDATA->Number; ?></a><br>
                                                                <a href="#" style="color:#b0b0b0;"></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <table width="220" border="0" cellpadding="0" cellspacing="0" align="left" class="col">
                                                    <tbody>
                                                        <tr class="hiddenMobile">
                                                            <td height="35"></td>
                                                        </tr>
                                                        <tr class="visibleMobile">
                                                            <td height="20"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 11px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                                                                <strong>FROM:</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" height="10"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 20px; vertical-align: top; ">
                                                            <?php echo $_CLIENT_DATA->Name; ?><br> <?php echo $_CLIENT_DATA->Address; ?>, <?php echo $_CLIENT_DATA->Postcode; ?><br> <?php echo $_CLIENT_DATA->Number; ?><br> <?php echo $_CLIENT_DATA->Email; ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <table width="220" border="0" cellpadding="0" cellspacing="0" align="right" class="col">
                                                    <tbody>
                                                        <tr class="hiddenMobile">
                                                            <td height="35"></td>
                                                        </tr>
                                                        <tr class="visibleMobile">
                                                            <td height="20"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 11px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 1; vertical-align: top; ">
                                                                <strong>CANDIDATE</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100%" height="10"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 12px; font-family: 'Open Sans', sans-serif; color: #5b5b5b; line-height: 20px; vertical-align: top; ">
                                                                <?php echo $CandidateName; ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr class="hiddenMobile">
                            <td height="60"></td>
                        </tr>
                        <tr class="visibleMobile">
                            <td height="30"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
<!-- /Information -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#e1e1e1">

    <tr>
        <td>
            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="fullTable" bgcolor="#ffffff" style="border-radius: 0 0 10px 10px;">
                <tr>
                    <td>
                        <table width="480" border="0" cellpadding="0" cellspacing="0" align="center" class="fullPadding">
                            <tbody>
                                <tr>
                                    <td style="font-size: 12px; color: #5b5b5b; font-family: 'Open Sans', sans-serif; line-height: 18px; vertical-align: top; text-align: left;">
                                    <?php echo $InvoiceData->Note; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr class="spacer">
                    <td height="50"></td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td height="20"></td>
    </tr>
</table>