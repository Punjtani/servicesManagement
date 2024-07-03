
<html>

<head>
    <meta charset="utf-8">
    <title>Estimate</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--    <link rel="preconnect" href="https://fonts.googleapis.com">-->
    <!--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
    <!--    <link href="https://fonts.googleapis.com/css2?family=Open Sans:wght@400;500;600;700&display=swap" rel="stylesheet">-->

    <style>
        /* @import 'https://fonts.googleapis.com/css2?family=Open Sans:wght@400;500;600;700&display=swap'; */
        /* @import 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap'; */


        q:before, q:after, blockquote:before, blockquote:after {
            content: "";
            content: none;
        }


        .past_due{
            position: relative;
            text-align: center;
            transform: rotate(-30deg);
            top: 350pt;
            color: red;
            opacity: 0.3;
            font-size:50px;
        }

        @page {
            size: auto;
            font-family: 'Open Sans', sans-serif;
        }

        @page noheader {
            margin: 0;
        }

        @page pageheader {
            margin: 0pt 0 0pt;
        }

        div.noheader {
            page-break-before: right;
            page: noheader;
            font-family: 'Open Sans', sans-serif;
        }

        div.page {
            page: pageheader;
            padding: 0 10pt;
            font-size: 8pt;
            font-family: 'Open Sans', sans-serif;
        }
        .page-break {
            page-break-after: always;
        }


        /* table.table_body tbody.body_tbody  {
        background: #fbfbfb;
        } */



    </style>
</head>

<body>


<div class="page">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 0pt">
        <tbody>
        <tr>
            <td width=28%">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="font-size:14pt;padding: 6pt 0 0 0">
                            @if (env('APP_URL') == 'http://localhost:8000')
                                <img style="width: 160px" src="http://ress-qa.loebigink.com/images/stream_logo.png"  alt="">
                            @else
                                <img style="width: 160px" src="{{asset('images/stream_logo.png')}}"  alt="">
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td width="72%">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: left;'>
                    <tbody>
                    <tr>
                        <td style="color: #1d252c;font-size:18pt;padding:0 0 1pt 0;font-family: 'Open Sans', sans-serif;font-weight:700;">Real Estate Service Systems LLC</td>
                    </tr>
                    <tr>
                        <td style="color: #666;font-size:10pt;padding: 0;font-family: 'Open Sans', sans-serif;">12774 Wisteria Drive, No. 1638 | Germantown, MD 20875</td>
                    </tr>
                    <tr>
                        <td style="color: #666;font-size:10pt;padding: 0;font-family: 'Open Sans', sans-serif;">301-540-0312 | info@reservicesystems.com |</td>
                    </tr>
                    <tr>
                        <td style="color: #666;font-size:10pt;padding: 0;font-family: 'Open Sans', sans-serif;">www.REserviceSystems.com</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <!-- =========== -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top: 2px solid #fff;padding-top:20pt;">
        <tbody>
        <tr>
            <td width="50%">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;padding:10pt  10pt 0 0;'>
                    <tbody>
                    <tr>
                        <td style="font-size:9pt;padding:2pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;">RECIPIENT:</td>
                    </tr>
                    <tr>
                        <!--<td style="font-size:12.046857pt;padding:1pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;text-transform: capitalize;"><?php echo $job['property']->title ." ( ". $job['property']['client']['user']->first_name . ' ' . $job['property']['client']['user']->middle_name .' '. $job['property']['client']['user']->last_name ." )" ?> </td>-->
                        <!--<td style="font-size:12.046857pt;padding:1pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;text-transform: capitalize;"><?php echo $job['property']->title; ?> </td>-->
                        <td style="font-size:12.046857pt;padding:1pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;text-transform: capitalize;"><?php echo $job['property']->title ." (". $job['property']['client']->company.")" ?> </td>
                    </tr>

                    <tr>
                        <td style="font-size:10.046857pt;padding: 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left">
                            <?php echo
                            $job['property']->billing_address;
                            ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:10.046857pt;padding: 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left">
                            <?php echo
                            $job['property']->billing_address_2;
                            ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:10.046857pt;padding: 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left"><?php echo  $job['property']->billing_city.', '.$job['property']->billing_state .' '. $job['property']->billing_zipcode ?> </td>
                    </tr>
                    <tr>
                        <td style="font-size:12.046857pt;padding:6pt 0 1pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;">Service Address</td>
                    </tr>

                    <tr>
                        <td style="font-size:10.046857pt;padding:0 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left">
                            <?php echo $job['property']->street1 ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:10.046857pt;padding:0 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left">
                            <?php echo $job['property']->street2 ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:10.046857pt;padding:0 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left"> <?php echo $job['property']->city.', '.$job['property']->state .' ' . $job['property']->zipcode ?></td>
                    </tr>

                    </tbody>
                </table>
            </td>
            <td width="50%">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;padding:10pt 0 0 10pt ;'>
                    <thead style ="background-color: #4697c9;">
                    <tr>
                        <th width="100%" colspan="2" style="font-size:14pt;padding: 4pt ;color: #fff;
                                    font-family: 'Open Sans', sans-serif; font-weight:bold;text-align:left">Estimate # <?php echo $job->id; ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr style="background-color: #f2f2f2;">
                        <td style="font-size:10pt;padding:2pt 4pt ;color: #666666;font-family: 'Open Sans', sans-serif;text-align:left">Sent on: </td>
                        <td style="font-size:10pt;padding:2pt 4pt ;color: #666666;font-family: 'Open Sans', sans-serif;text-align:right"><?php echo date(
                                'm/d/Y',
                                strtotime($job->created_at)
                            ); ?></td>
                    </tr>

                    <?php if($job->po_number){?>
                    <tr style="background-color: #f2f2f2;">
                        <td style="font-size:10pt;padding:2pt 4pt ;color: #666666;font-family: 'Open Sans', sans-serif;text-align:left">PO #: </td>
                        <td style="font-size:10pt;padding:2pt 4pt ;color: #666666;font-family: 'Open Sans', sans-serif;text-align:right"><?php echo $job->po_number; ?></td>
                    </tr>
                    <?php } ?>
                    <tr style ="background-color: #4697c9;">
                        <td style="font-size:12pt;padding:2pt 4pt ;color: #fff;font-family: 'Open Sans', sans-serif;  font-weight:bold;text-align:left">Total: </td>
                        <td style="font-size:12pt;padding:2pt 4pt ;color: #fff;font-family: 'Open Sans', sans-serif; font-weight:bold; text-align:right">$<?php echo number_format($totalInvoice + $totalTax,2); ?></td>
                    </tr>
                    </tbody>
                </table>


            </td>
        </tr>
        </tbody>
    </table>


    <!-- =========== -->
    <table class="table_body" width="100%"   style="margin-top: 10pt;vertical-align: top;">
        <thead style ="background-color: #4697c9;">

        <tr>
            <th width="30%" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <th style="padding:6pt 5pt;color: #fff;font-size: 9pt;text-transform: uppercase;text-align: left;font-family: 'Open Sans', sans-serif; font-weight:700;">
                            <strong>PRODUCT / SERVICE </strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            <!-- <th width="20%" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:6pt 5pt;color: #fff;font-size: 9pt;text-transform: uppercase;text-align: left;font-family: 'Open Sans', sans-serif; font-weight:700;">
                            <strong>Service</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th> -->
            <th width="35%" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:6pt 5pt;color: #fff;font-size: 9pt;text-transform: uppercase;text-align: left;font-family: 'Open Sans', sans-serif; font-weight:700;">
                            <strong>Description</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            <th width="5%" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:6pt 5pt;color: #fff;font-size: 9pt;text-transform: uppercase;text-align: center;font-family: 'Open Sans', sans-serif; font-weight:700;">
                            <strong>QTY</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            <th width="15%" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:6pt 5pt;color: #fff;font-size: 9pt;text-transform: uppercase;text-align: center;font-family: 'Open Sans', sans-serif; font-weight:700;">
                            <strong>UNIT COST</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
            <th width="15%" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="">
                    <tbody>
                    <tr>
                        <th style="padding:6pt 5pt;color: #fff;font-size: 9pt;text-transform: uppercase;text-align: right;font-family: 'Open Sans', sans-serif; font-weight:700;">
                            <strong>Total</strong>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </th>
        </tr>
        </thead>

        <tbody class="body_tbody">
        <tr class="tr_body_tbody">
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <tr>
                        <td style="padding:6pt 5pt;font-size: 9pt; color: #666;  font-family: 'Open Sans', sans-serif;">
                            UNIT
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: left;">
                    <tbody>
                    <tr>
                        <td style="padding:6pt 5pt;font-size:9pt; color: #666;   font-family: 'Open Sans', sans-serif;">
                            <?php echo $job->apartment_number." - ".(isset($job['apartment_type']) ? $job['apartment_type']->type : '-')." - ".$job->apartment_status;?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: center;">
                    <tbody>
                    <tr>
                        <td style="padding:6pt 5pt;font-size: 9pt; color: #666;  font-family: 'Open Sans', sans-serif;"></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: center;">
                    <tbody>
                    <tr>
                        <td style="padding:6pt 5pt;font-size:9pt; color: #666;  font-family: 'Open Sans', sans-serif;">

                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td style="border-bottom: 1px solid #e0e0e0;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                    <tbody>
                    <tr>
                        <td style="padding:6pt 5pt;font-size: 9pt; color: #666; font-family: 'Open Sans', sans-serif;">

                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <?php foreach ($job['jobServices'] as $service) { ?>
        <?php if($service['service']) { ?>

        <tr class="tr_body_tbody">
            <!-- <td style="border-bottom: 1px solid #e0e0e0;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr>
                                <td style="padding:6pt 5pt;font-size: 9pt; color: #666;   font-family: 'Open Sans', sans-serif;">
                                    <?php echo $service->end_date ? date('m/d/Y',strtotime($service->end_date))  : '--/--/--'; ?>
                </td>
            </tr>
            </tbody>
        </table>
    </td> -->
            <td style="border-bottom: 1px solid #e0e0e0; "  colspan="5" >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>
                    <?php foreach ($service['requestedJobSubService'] as $requestService) { ?>
                    <tr>
                        <td width="30%" style="padding:6pt 5pt;font-size: 9pt; color: #666;  font-family: 'Open Sans', sans-serif;">
                            <?php echo $requestService['subService']->name ?>
                        </td>
                        <td width="35%" style="padding:6pt 5pt;font-size:9pt; color: #666;   font-family: 'Open Sans', sans-serif;">
                            <?php echo $requestService->description; ?>
                        </td>
                        <td width="5%" style="padding:6pt 5pt;font-size: 9pt; color: #666;  font-family: 'Open Sans', sans-serif;text-align: center;">
                            <?php echo $requestService->quantity; ?>
                        </td>
                        <td width="15%" style="padding:6pt 5pt;font-size:9pt; color: #666;  font-family: 'Open Sans', sans-serif; text-align: center;">
                            $<?php echo number_format($requestService->base_price,2); ?>
                        </td>
                        <td width="15%" style="padding:6pt 5pt;font-size: 9pt; color: #666; font-family: 'Open Sans', sans-serif;position: relative;text-align: right   ;">
                            $<?php echo number_format($requestService->total_price,2); ?> <?php if((!$service['service']->is_taxable)){ ?> <sup style="position: absolute;top: 0;right:0;color:red">*</sup> <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </td>

            <!-- <td style="border-bottom: 1px solid #e0e0e0;">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                            <?php foreach ($service['requestedJobSubService'] as $requestService) { ?>
                <tr>
                    <td style="padding:6pt 5pt;font-size: 9pt; color: #666;  font-family: 'Open Sans', sans-serif;">
<?php echo $requestService['subService']->name ?>
                </td>
            </tr>
<?php } ?>
                </tbody>
            </table>
        </td>
        <td style="border-bottom: 1px solid #e0e0e0;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: left;">
                <tbody>
<?php foreach ($service['requestedJobSubService'] as $requestService) { ?>
                <tr>
                    <td style="padding:6pt 5pt;font-size:9pt; color: #666;   font-family: 'Open Sans', sans-serif;">
<?php echo $requestService->description; ?>
                </td>
            </tr>
<?php } ?>
                </tbody>
            </table>
        </td>
        <td style="border-bottom: 1px solid #e0e0e0;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: center;">
                <tbody>
<?php foreach ($service['requestedJobSubService'] as $requestService) { ?>
                <tr>
                    <td style="padding:6pt 5pt;font-size: 9pt; color: #666;  font-family: 'Open Sans', sans-serif;">
<?php echo $requestService->quantity; ?>
                </td>
            </tr>
<?php } ?>
                </tbody>
            </table>
        </td>
        <td style="border-bottom: 1px solid #e0e0e0;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: center;">
                <tbody>
<?php foreach ($service['requestedJobSubService'] as $requestService) { ?>
                <tr>
                    <td style="padding:6pt 5pt;font-size:9pt; color: #666;  font-family: 'Open Sans', sans-serif;">
                        $<?php echo $requestService->base_price; ?>.00
                                </td>
                            </tr>
                            <?php } ?>
                </tbody>
            </table>
        </td>
        <td style="border-bottom: 1px solid #e0e0e0;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                <tbody>
<?php foreach ($service['requestedJobSubService'] as $requestService) { ?>
                <tr>
                    <td style="padding:6pt 5pt;font-size: 9pt; color: #666; font-family: 'Open Sans', sans-serif;">
                        $<?php echo $requestService->total_price; ?>.00 <?php if(($service['service'] &&!$service['service']->is_taxable)){ ?> <sup style="color:red">*</sup> <?php } ?>
                </td>
            </tr>
<?php } ?>
                </tbody>
            </table>
        </td> -->
        </tr>

        <?php } } ?>
        </tbody>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 70pt;">
        <tbody>
        <tr>
            <td  width="60%" style="padding:0;color: #000;font-size: 10.046857pt;text-align: right;font-family: 'Open Sans', sans-serif;margin-top: 20pt;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 20pt;">
                    <tbody>
                    <tr>
                        <td style="padding:0;color: #666;font-size: 10.046857pt;font-family: 'Open Sans', sans-serif;text-align:left;">

                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0;color: #666;font-size: 10.046857pt;font-family: 'Open Sans', sans-serif;text-align:left;">
                            <p style="font-size:10pt;color: #666;  font-family: 'Open Sans', sans-serif;text-transform: capitalize;margin-bottom: 20pt;text-align:left;"></p>
                            <p style="font-size:10pt;color: #666;  font-family: 'Open Sans', sans-serif;text-align:left;">This quote is valid for the next 30 days, after which values may be subject
                                to change.</p>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </td>
            <td  width="30%" style="padding:0;color: #000;font-size: 10.046857pt;text-align: right;font-family: 'Open Sans', sans-serif;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 20pt;margin-top: 20pt;padding-bottom: 30pt;">
                    <tbody>
                    <tr>
                        <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td style="padding:6pt 5pt;color: #666;font-size: 9pt;text-transform: capitalize;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                        Subtotal
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                                <tbody>
                                <tr>
                                    <td style="padding:6pt 5pt;color: #666;font-size: 9pt;text-transform: capitalize;text-align: right;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                        $<?php echo number_format($totalInvoice,2); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php if(isset($job['property']) && isset($job['property']['taxType']) && $job['property']['taxType'] && $totalTax) { ?>
                    <tr>
                        <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td style="padding:6pt 5pt;color: #666;font-size: 9pt;text-transform: capitalize;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                        <?php echo $job['property']['taxType']->name .' ( '. $job['property']['taxType']->rate .'% )';?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                                <tbody>
                                <tr>
                                    <td style="padding:6pt 5pt;color: #666;font-size: 9pt;text-transform: capitalize;text-align: right;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                        $<?php echo number_format($totalTax,2); ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td style="padding:6pt 5pt;color: #000;font-size: 9pt;text-transform: capitalize;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                        Total
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                                <tbody>
                                <tr>
                                    <td style="padding:6pt 5pt;color: #000;font-size: 9pt;;text-transform: capitalize;text-align: right;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                        $<?php echo number_format($totalInvoice + $totalTax,2); ?>
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
        </tbody>
    </table>
    <table width="50%" border="0" cellspacing="0" cellpadding="0">
        <tbody>
        <tr>
            <td  width="60%" style="padding:0;color: #000;font-size: 10.046857pt;text-align: right;font-family: 'Open Sans', sans-serif;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tbody>

                    <tr>
                        <td style="padding:0;color: #666;font-size: 10.046857pt;font-family: 'Open Sans', sans-serif;text-align:left;">
                        <p style="padding:6pt 5pt;color: #000;font-size: 9pt;text-transform: capitalize;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
                            Signature____________________
                        </p>

                        </td>
                        <td style="padding:0;color: #666;font-size: 10.046857pt;font-family: 'Open Sans', sans-serif;text-align:left;">
                        <p style="padding:6pt 5pt;color: #000;font-size: 9pt;text-transform: capitalize;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
                            Date____________________
                        </p>

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
