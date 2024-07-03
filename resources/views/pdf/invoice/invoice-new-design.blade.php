
<html>

    <head>
        <meta charset="utf-8">
        <title>Invoice</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!--    <link rel="preconnect" href="https://fonts.googleapis.com">-->
    <!--    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
    <!--    <link href="https://fonts.googleapis.com/css2?family=Open Sans:wght@400;500;600;700&display=swap" rel="stylesheet">-->
    
        <style>
            /* @import 'https://fonts.googleapis.com/css2?family=Open Sans:wght@400;500;600;700&display=swap'; */
            /* @import 'https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap'; */
    
            @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap');
    
            html, body, div, span, applet, object, iframe,
    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
    a, abbr, acronym, address, big, cite, code,
    del, dfn, em, img, ins, kbd, q, s, samp,
    small, strike, strong, sub, sup, tt, var,
    b, u, i, center,
    dl, dt, dd, ol, ul, li,
    fieldset, form, label, legend,
    table, caption, tbody, tfoot, thead, tr, th, td,
    article, aside, canvas, details, embed,
    figure, figcaption, footer, header, hgroup,
    menu, nav, output, ruby, section, summary,
    time, mark, audio, video {
      margin: 0;
      padding: 0;
      border: 0;
      font: inherit;
      font-size: 100%;
      vertical-align: baseline;
    }
    
    html {
      line-height: 1;
    }
    
    ol, ul {
      list-style: none;
    }
    
    table {
      border-collapse: collapse;
      border-spacing: 0;
    }
    
    caption, th, td {
     
      font-weight: normal;
      vertical-align: top;
    }
    
    q, blockquote {
      quotes: none;
    }
    q:before, q:after, blockquote:before, blockquote:after {
      content: "";
      content: none;
    }
    
    a img {
      border: none;
    }
    
    article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
      display: block;
    }
    
    body {
    
    
      font-size: 12px;
      margin: 0;
      padding: 0;
      color: #555555;
    }
    body a {
      text-decoration: none;
      color: inherit;
    }
    body a:hover {
      color: inherit;
      opacity: 0.7;
    }
    
            body {
                color: #59595b;
                font-family: 'Open Sans', sans-serif;
                line-height: 1.5;
                font-size: 10pt;
            }
    
            @page {
                size: auto;
                font-family: 'Open Sans', sans-serif;
            }
    
            @page noheader {
                margin: 0;
            }
    
            @page pageheader {
                margin: 10pt 0 10pt;
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
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 10pt">
            <tbody>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td style="font-size:14pt;padding: 0 0 6pt 0">
                                @if (env('APP_URL') == 'http://localhost:8000')
                                    <img style="width: 150px" src="http://ress-qa.loebigink.com/images/stream_logo.png"  alt="">
                                @else
                                    <img style="width: 150px" src="{{asset('images/stream_logo.png')}}"  alt="">
                                @endif 
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;'>
                        <tbody>
                        <tr>
                            <td style="color: #1d252c;font-size:16pt;padding:1pt 0;font-family: 'Open Sans', sans-serif;">Real Estate Service Systems LLC</td>
                        </tr>
                        <tr>
                            <td style="color: #666;font-size:9pt;padding:1pt 0;font-family: 'Open Sans', sans-serif;">12774 Wisteria Drive, No. 1638 | Germantown, MD 20875</td>
                        </tr>
                        <tr>
                            <td style="color: #666;font-size:9pt;padding:1pt 0;font-family: 'Open Sans', sans-serif;">301-540-0312 | info@reservicesystems.com |</td>
                        </tr>
                        <tr>
                            <td style="color: #666;font-size:9pt;padding:1pt 0;font-family: 'Open Sans', sans-serif;">www.REserviceSystems.com</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <!-- =========== -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-top: 2px solid #e0e0e0;padding-top:20pt;">
            <tbody>
            <tr>
                <td width="50%">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;padding:10pt  10pt 0 0;'>
                        <tbody>
                        <tr>
                            <td style="font-size:9pt;padding:2pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;">RECIPIENT:</td>
                        </tr>
                        <tr>
                            <td style="font-size:12.046857pt;padding:1pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;">Comapny Name</td>
                        </tr>
                       
                        <tr>
                            <td style="font-size:10.046857pt;padding: 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left">
                            <?php echo
                            $data['job']['property']->billing_address;
                            ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:10.046857pt;padding: 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left"><?php echo $data['job']['property']->billing_city . ' ' . $data['job']['property']->billing_state .', '. $data['job']['property']->billing_zipcode ?> </td>
                        </tr>
    
                        <?php if($data['job']->po_number){?>
                        <tr>
                            <td style="font-size:10pt;padding: 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left">PO #: <?php echo $data['job']->po_number; ?></td>
                        </tr>
                        <?php } ?>
    
                        <tr>
                            <td style="font-size:12.046857pt;padding:6pt 0 1pt 0;color: #000;font-family: 'Open Sans', sans-serif;text-align:left;font-weight:bold;">Service Address</td>
                        </tr>
                        
                        <tr>
                            <td style="font-size:10.046857pt;padding:0 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left">
                            <?php echo $data['job']['property']->street1 ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:10.046857pt;padding:0 0;color: #666;font-family: 'Open Sans', sans-serif;text-align:left"> <?php echo $data['job']['property']->city . ' ' . $data['job']['property']->state .', ' . $data['job']['property']->zipcode ?></td>
                        </tr>
                    
                        </tbody>
                    </table>
                </td>
                <td width="50%">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style='text-align: right;padding:10pt 0 0 10pt ;'>
                            <thead style ="background-color: #4697c9;">
                                <tr>
                                    <th width="100%" colspan="2" style="font-size:14pt;padding: 4pt ;color: #fff;
                                    font-family: 'Open Sans', sans-serif; font-weight:bold;text-align:left">Invoice # <?php echo $data->id; ?></th>
                                </tr>
                            </thead>
                        <tbody>
                        <tr>
                            <td style="font-size:10pt;padding:2pt 4pt ;color: #000;font-family: 'Open Sans', sans-serif;text-align:left">Issued: </td>
                            <td style="font-size:10pt;padding:2pt 4pt ;color: #000;font-family: 'Open Sans', sans-serif;text-align:right"><?php echo date(
                                'd/m/Y',
                                strtotime($data->created_at)
                            ); ?></td>
                        </tr>
                        <tr>
                            <td style="font-size:10pt;padding:2pt 4pt ;color: #000;font-family: 'Open Sans', sans-serif;text-align:left">Due: </td>
                            <td style="font-size:10pt;padding:2pt 4pt ;color: #000;font-family: 'Open Sans', sans-serif;text-align:right"><?php echo date(
                                'm/d/Y',
                                strtotime($data->due_date)
                            ); ?></td>
                        </tr>
                        <tr style ="background-color: #4697c9;">
                            <td style="font-size:12pt;padding:2pt 4pt ;color: #fff;font-family: 'Open Sans', sans-serif;  font-weight:bold;text-align:left">Total: </td>
                            <td style="font-size:12pt;padding:2pt 4pt ;color: #fff;font-family: 'Open Sans', sans-serif; font-weight:bold; text-align:right">$ <?php echo $totalInvoice; ?>.00</td>
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
                <th width="10%" >
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
                <th width="10%" >
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
    
            <?php foreach ($data['job']['jobServices'] as $service) { ?>
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
                    <td style="border-bottom: 1px solid #e0e0e0;">
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
                                    $ <?php echo $requestService->base_price; ?>.00
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
                                    $ <?php echo $requestService->total_price; ?>.00
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
    
            <?php } ?>
            
                <tr>
                    <td style="border-bottom: 1px solid #e0e0e0;" colspan="5">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                                <?php foreach ($data['invoice_line_item'] as $item) { ?>
                                <tr>
                                    <td  style="padding:6pt 5pt;font-size: 9pt; color: #666; font-family: 'Open Sans', sans-serif;">
                                        <?php echo $item->text; ?>
                                    </td>
                                    
                                </tr>
                                 <?php } ?>
                            </tbody>
                        </table>
                    </td>  
                    <td style="border-bottom: 1px solid #e0e0e0;" >
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                            <tbody>
                                <?php foreach ($data['invoice_line_item'] as $item) { ?>
                                <tr>
                                    
                                    <td style=" padding:6pt 5pt;font-size:9pt; color: #666;   font-family: 'Open Sans', sans-serif;">
                                        $ <?php echo $item->price; ?>.00
                                    </td>
                                </tr>
                                 <?php } ?>
                            </tbody>
                        </table>
                    </td>  
                </tr>
           
            </tbody>
            <tfoot>
            <tr>
                <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td style="padding:6pt 5pt;color: #333;font-size: 9pt;text-transform: uppercase;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
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
                            <td style="padding:6pt 5pt;color: #333;font-size: 9pt;text-transform: uppercase;text-align: right;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                $ <?php echo $totalInvoice; ?>.00
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td style="padding:6pt 5pt;color: #333;font-size: 9pt;text-transform: uppercase;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                DC SalesTax ( 0% )
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
                <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align: right;">
                        <tbody>
                        <tr>
                            <td style="padding:6pt 5pt;color: #333;font-size: 9pt;text-transform: uppercase;text-align: right;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                $ 0.00
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #e0e0e0;" colspan="3">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td style="padding:6pt 5pt;color: #333;font-size: 9pt;text-transform: uppercase;text-align: left;font-family: 'Open Sans', sans-serif;font-weight:700;">
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
                            <td style="padding:6pt 5pt;color: #333;font-size: 9pt;;text-transform: uppercase;text-align: right;font-family: 'Open Sans', sans-serif;font-weight:700;">
                                $ <?php echo $totalInvoice; ?>.00
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tfoot>
        
        
        
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 20pt;margin-top: 20pt;text-align: right;">
            <tbody>
            <tr>
                <td style="padding:0;color: #000;font-size: 10.046857pt;text-align: right;font-family: 'Open Sans', sans-serif;">* Non-taxable</td>
            </tr>
            </tbody>
        </table>
        <!-- ============================ -->
        <!--<div class="page-break"></div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10pt;margin-top: 20pt;text-align: left;">
            <tbody>
            <tr>
                <td style="padding:0;color: #000;font-size: 10.046857pt;text-align: left;font-family: 'Open Sans', sans-serif;">Before Attachments</td>
            </tr>
            </tbody>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tbody>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                            <tbody>
                            <?php /* foreach($data['job']['jobServices'] as $service) {?>
                                <tr>
                                    <td><p style="padding:0;color: #000;font-size: 10.046857pt;text-align: left;font-family: 'Open Sans', sans-serif;"><?php echo $service->service->category ?></p></td>
                                </tr>
                                <tr>
                                    <?php
                                    $count = 0; 
                                    foreach($service->serviceJobProgresAttatchment as $beforeAttachment){
                                    if($count >= 4){
                                        echo "</tr><tr>";
                                        $count = 0;
                                    }
                                    $count++;
                                    ?>
                                     @if (env('APP_URL') == 'http://localhost:8000')
                                        <td><img width="150px" src="{{public_path($beforeAttachment->file_name)}}" alt=""></td>
                                    @else
                                        <td><img width="150px" src="{{asset($beforeAttachment->file_name)}}" alt=""></td>
                                    @endif
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10pt;margin-top: 20pt;text-align: left;">
            <tbody>
            <tr>
                <td style="padding:0;color: #000;font-size: 10.046857pt;text-align: left;font-family: 'Open Sans', sans-serif;">After Attachments</td>
            </tr>
            </tbody>
        </table>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
            <tbody>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tbody>
                            <?php foreach($data['job']['jobServices'] as $service) {?>
                                <tr>
                                    <td><p style="padding:0;color: #000;font-size: 10.046857pt;text-align: left;font-family: 'Open Sans', sans-serif;"><?php echo $service->service->category ?></p></td>
                                </tr>
                                <tr>
                                    <?php
                                    $count = 0; 
                                    foreach($service->serviceJobProgressAfterAttatchment as $afterAttachment){
                                    if($count >= 4){
                                        echo "</tr><tr>";
                                        $count = 0;
                                    }
                                    $count++;
                                    ?>
                                    @if (env('APP_URL') == 'http://localhost:8000')
                                        <td><img width="150px" src="{{public_path($afterAttachment->file_name)}}" alt=""></td>
                                    @else
                                        <td><img width="150px" src="{{asset($afterAttachment->file_name)}}" alt=""></td>
                                    @endif
                                    <?php } ?>
                                </tr>
                                <?php } */ ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>-->
    
        <!-- ============================= -->
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;padding-bottom: 30pt;">
            <tbody>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="text-align:center;">
                        <tbody>
                        <tr>
                            <td>
                                <p style="font-size:13.552714pt;color: #000;  font-family: 'Open Sans', sans-serif;text-transform: uppercase;">Your Occupancy is Our Business</p>
                                <p style="font-size:12.046857pt;color: #000;  font-family: 'Open Sans', sans-serif;">Thank You for Trusting Us to Serve You</p>
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
    