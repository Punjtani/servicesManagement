
<html>

<head>
    <meta charset="utf-8">
    <title>cvdfddfdf</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--    <link rel="preconnect" href="https://fonts.googleapis.com">-->
<!--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
<!--    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">-->

    <style>
        @import 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap';
        body {
            color: #59595b;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.5;
            font-size: 10.541pt;
        }

        @page {
            size: auto;
            font-family: 'Montserrat', sans-serif;
        }

        @page noheader {
            margin: 0;
        }

        @page pageheader {
            margin: 50pt 0 50pt;
        }

        div.noheader {
            page-break-before: right;
            page: noheader;
        }

        div.page {
            page: pageheader;
            padding: 0 20pt;
            font-size: 8pt;
        }
    </style>
</head>

<body>
<div class="page">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 30pt">
        <tbody>
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="font-size:14pt;font-weight: bold;padding: 0 0 6pt 0">
                            <img style="width: 300px" src="  <?php echo asset(
                                'images/logo.jpg'
                            ); ?> " alt="">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;'>
                    <tbody>
                    <tr>
                        <td style="color: #1d252c;font-size:18.070286pt;padding:2pt 0;padding-bottom: 15pt;font-weight:500;font-family: 'Montserrat', sans-serif;">Real Estate Service Systems LLC</td>
                    </tr>
                    <tr>
                        <td style="color: #666;font-size:10.541pt;padding:2pt 0;font-weight:500;font-family: 'Montserrat', sans-serif;">12774 Wisteria Drive, No. 1638 | Germantown, MD 20875 301-540-0312</td>
                    </tr>
                    <tr>
                        <td style="color: #666;font-size:10.541pt;padding:2pt 0;font-weight:500;font-family: 'Montserrat', sans-serif;">info@reservicesystems.com | www.REserviceSystems.com</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- =========== -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="    margin-top: 25pt;border-top: 5px solid #00263e;padding-top: 25pt;">
        <tbody>
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="font-size:45.175714pt;font-weight: bold;padding: 0 0 6pt 0;color: #5881ce;font-family: 'Montserrat', sans-serif;">
                            <h3 style="margin:0">Invoice</h3>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:22.587857pt;padding: 0 0 6pt 0;font-weight: 600;color: #000001;font-family: 'Montserrat', sans-serif;">
                            <p># IN - <?php echo $data->id; ?> </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;'>
                    <tbody>
                    <tr>
                        <td style="font-size:18.070286pt;padding:2pt 0;color: #000;font-weight:500;font-family: 'Montserrat', sans-serif;">Issued: <?php echo date(
                            'd/m/Y',
                            strtotime($data->created_at)
                        ); ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:18.070286pt;padding:2pt 0;color: #000;font-weight:500;font-family: 'Montserrat', sans-serif;">Due: <?php echo date(
                            'd/m/Y',
                            strtotime($data->due_date)
                        ); ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:18.070286pt;padding:2pt 0;color: #000;font-weight:500;font-family: 'Montserrat', sans-serif;">Total: $ <?php echo $totalInvoice; ?>.00</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- =========== -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 30pt;padding: 30px;background-color: #5881ce;    border-radius: 10px;">
        <tbody>
        <tr>
            <td style="vertical-align: top;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="font-size:15.058571pt;padding:2pt 0;color: #fff;font-weight:600;font-family: 'Montserrat', sans-serif;">RECIPIENT</td>
                    </tr>
                    <tr>
                        <td style="font-size:12.046857pt;padding:2pt 0;color: #fff;font-weight:600;font-family: 'Montserrat', sans-serif;"><?php $data[
                            'job'
                        ]['property']['client']->contact_title; ?> <?php $data[
     'job'
 ]['property']['client']->contact_name; ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:12.046857pt;padding:2pt 0;color: #fff;font-weight:400;font-family: 'Montserrat', sans-serif;">Total: $ <?php echo $totalInvoice; ?>.00</td>
                    </tr>
                    <tr>
                        <td style="font-size:12.046857pt;padding:2pt 0;color: #fff;font-weight:400;font-family: 'Montserrat', sans-serif;"><?php
                        $data['job']['property']['client']->apartment_name;
                        $data['job']['property']['client']->street_address;
                        ?></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="vertical-align: top;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;'>
                    <tbody>
                    <tr>
                        <td style="font-size:15.058571pt;padding:2pt 0;color: #fff;font-weight:500;font-family: 'Montserrat', sans-serif;">SERVICE ADDRESS</td>
                    </tr>
                    <tr>
                        <td style="font-size:12.046857pt;padding:2pt 0;color: #fff;font-weight:400;font-family: 'Montserrat', sans-serif;"><?php echo $data[
                            'job'
                        ]['property']->title; ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:12.046857pt;padding:2pt 0;color: #fff;font-weight:400;font-family: 'Montserrat', sans-serif;"><?php echo $data[
                            'job'
                        ]['property']->state .
                            ',' .
                            $data['job']['property']->country .
                            ' ' .
                            $data['job']['property']->zipcode; ?></td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <!--<table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 30pt;margin-top: 20pt;text-align: left;">
        <tbody>
        <tr>
            <td style="padding:0;color: #f7a33d;font-size: 13.552714pt;font-weight: 600;text-align: left;font-family: 'Montserrat', sans-serif;padding-right:20pt">UNIT</td>
            <td style="padding:0;color: #00263e;font-size:13.552714pt;font-weight: 600;text-align: left;font-family: 'Montserrat', sans-serif;"><?php echo $data[
                'job'
            ]->apartment_number .
                ' ,' .
                $data['job']->apartment_status; ?></td>
        </tr>
        </tbody>
    </table>-->
    <!-- =========== -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size:8pt; color:#000;margin-top: 30pt;display:table;border-collapse:separate;border-spacing:0">
        <thead>
        <tr>
            <th width="15%" style="border-top: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: left;font-family: 'Montserrat', sans-serif;">
                            <strong>job Date </strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            <th width="25%" style="border-top: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: left;font-family: 'Montserrat', sans-serif;">
                            <strong>Service</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            <th width="45%" style="border-top: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: left;font-family: 'Montserrat', sans-serif;">
                            <strong>Service Date</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            <th width="15%" style="border-top: 1px solid #e0e0e0;border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: right;font-family: 'Montserrat', sans-serif;">
                            <strong>Total</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
        </tr>
        </thead>
        <tbody>

       <?php foreach ($data['job']['jobServices'] as $service) { ?>
        <tr>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;font-size: 10.541pt; color: #000000;font-weight:500;   font-family: 'Montserrat', sans-serif;">
                            <?php echo date(
                                'd/m/Y',
                                strtotime($service->scheduled_date)
                            ); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;font-size: 10.541pt; color: #000000;font-weight:500;   font-family: 'Montserrat', sans-serif;">
                          <?php if ($service['subService']) {
                              echo $service['subService']->name;
                          } else {
                              echo $service->category;
                          } ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;font-size: 10.541pt; color: #000000;font-weight:500;   font-family: 'Montserrat', sans-serif;">
                            <?php echo date(
                                'd/m/Y',
                                strtotime($service->end_date)
                            ); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;font-size: 10.541pt; color: #000000;font-weight:500;   font-family: 'Montserrat', sans-serif;">
                            $ <?php echo $service->base_price; ?>.00
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <?php } ?>

        </tbody>
        <tfoot>
        <tr>
            <td style="border-bottom: 1px solid #e0e0e0;" colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: left;font-family: 'Montserrat', sans-serif;">
                            Subtotal
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;" colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: right;font-family: 'Montserrat', sans-serif;">
                            $ <?php echo $totalInvoice; ?>.00
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e0e0e0;" colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: left;font-family: 'Montserrat', sans-serif;">
                            DC SalesTax ( 0% )
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;" colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: right;font-family: 'Montserrat', sans-serif;">
                            $ 0.00
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e0e0e0;" colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: left;font-family: 'Montserrat', sans-serif;">
                            Total
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;" colspan="2">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                    <tbody>
                    <tr>
                        <td style="padding:15pt 10pt;color: #5881ce;font-size: 12.046857pt;font-weight: 700;text-transform: uppercase;text-align: right;font-family: 'Montserrat', sans-serif;">
                            $ <?php echo $totalInvoice; ?>.00
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tfoot>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 90pt;margin-top: 20pt;text-align: right;">
        <tbody>
        <tr>
            <td style="padding:0;color: #000;font-size: 10.046857pt;font-weight: 700;text-align: right;font-family: 'Montserrat', sans-serif;">* Non-taxable</td>
        </tr>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;padding-bottom: 30pt;">
        <tbody>
        <tr>
            <td>
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;">
                    <tbody>
                    <tr>
                        <td>
                            <p style="font-size:13.552714pt;color: #000;font-weight:600;   font-family: 'Montserrat', sans-serif;text-transform: uppercase;">Your Occupancy is Our Business</p>
                            <p style="font-size:12.046857pt;color: #000;font-weight:500;   font-family: 'Montserrat', sans-serif;">Thank You for Trusting Us to Serve You</p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>

</html>
