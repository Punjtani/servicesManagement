<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unpaid Invoice</title>
     <!-- <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900" rel="stylesheet"> -->
     <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
      #outlook a {
        padding: 0;
      }
    </style>
  </head>
  <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style=" width: 100%!important;margin: 0;padding: 0;font-family: 'Montserrat', sans-serif;background-color: #f1f5f8;">
    <center>
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
        <tbody>
            <tr style="border-bottom: 5px solid #d8e0e5;">
            <td>
              <h1 style="margin: 29px 0px 22px;"><a href="#"><img style="width: 160px;" src="http://ress-qa.loebigink.com/images/stream_logo.png" alt=""></a></h1>
            </td>
            <td align="right" style="font-size: 15px;font-weight: 500;color: #000;"> <?php echo date("l jS \of F Y"); ?> </td>
            </tr>
        </tbody>
        </table>
      
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
    <tbody>
        <tr>
        <td>
            <h2 style="font-size: 36px;font-weight: 400;color: #000;margin-top: 10px;margin-bottom: 10px;">Unpaid Invoice</h2>
        </td>
        </tr>
    </tbody>
    </table>

      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0px;">
        <tbody>
          <tr>
            <td style="font-size: 18px;color: #00263e;font-weight: 500;padding: 20px 0px 10px;">
            Hello <?php echo $data['client']->first_name. ' '.
                $data['client']->middle_name. ' '.
                $data['client']->last_name; ?>
            </td>
          </tr>
          <tr>
            <td style="font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
            You have following unpaid invoice.</td>
          </tr>
          <tr>
            <td style="font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                <p> <b>invoice #: </b>{{$data['invoice_detail']['invoice_no']}}</p>
                <p><b>Job PO #: </b>{{$data['invoice_detail']['job']}}</p>
                <p><b>Property: </b>{{$data['invoice_detail']['property']}}</p>
                <p><b>Company: </b>{{$data['invoice_detail']['company']}}</p>
                <p><b>Due Date #: </b>{{$data['invoice_detail']['due_date']}}</p>
             </td>
          </tr>
        </tbody>
      </table>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0;margin-top: 10px;">
    <tbody>
        <tr>
        <td style="font-size: 20px;color: #5881ce;font-weight: 600;padding-bottom: 12px;">Real Estate Service Systems LLC</td>
        </tr>
        <tr>
        <td style="font-size: 12px;color: #333333;font-weight: 500;padding-bottom: 12px;">12774 Wisteria Drive, No. 1638 | Germantown, MD 20875 </td>
        </tr>
        <tr>
        <td style="font-size: 12px;color: #333333;font-weight: 500;padding-bottom: 12px;"><a href="tel:3015400312" style="color: #333333;border-right: 2px solid #333333;padding-right: 17px;text-decoration: none;">301-540-0312</a><a href="mailto:info@reservicesystems.com " style="
    color: #333333;border-right: 2px solid #333333;padding-left: 17px;padding-right: 17px;text-decoration: none;">info@reservicesystems.com</a><a href="www.REserviceSystems.com" target="_blank" style="color: #333333;text-decoration: none;padding-left: 14px;">www.REserviceSystems.com</a></td>
        </tr>
        <tr>
        <td style="font-size: 12px;color: #333333;font-weight: 500;padding-bottom: 30px;">Copyright © <?php echo now()->year; ?> streamLINK - Services Management Portal . All rights reserved.</td>
        </tr>
    </tbody>
    </table>
        
    </center>
  </body>
</html>