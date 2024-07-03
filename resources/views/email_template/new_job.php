<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Job</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <style>
      #outlook a {
        padding: 0;
      }
    </style>
  </head>
  <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style=" width: 100%!important;margin: 0;padding: 0;font-family: 'Montserrat', sans-serif;background-color: #f1f5f8;">
    <center>
      <?php include 'email_header.php';?>
      
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
    <tbody>
        <tr>
        <td>
            <h2 style="font-size: 36px;font-weight: 400;color: #000;margin-top: 24px;margin-bottom: 27px;">New Job</h2>
        </td>
        </tr>
    </tbody>
    </table>
      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding: 26px 30px 29px;background:#5881cd;border-radius: 5px;">
        <tbody>
          <tr>
            <td colspan="2" style="">
              <h3 style="margin: 0px 0px 22px;font-size: 24px;color: #fff;font-weight: 500;">
              <?php echo $data['property']->title. ' ('.
                $data['property']['client']['user']->first_name. ' '.
                $data['property']['client']['user']->middle_name. ' '.
                $data['property']['client']['user']->last_name. ')'; ?>
              </h3>
            </td>
          </tr>
          <tr>
            <td style="font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Property Address</td>
            <td style="font-size: 14px;color: #fff;font-weight: 600;">
            <?php echo $data['property']->street1.' '.
                $data['property']->city.', '.
                $data['property']->state . ',' .
                    $data['property']->country .
                    ' ' .
                    $data['property']->zipcode; ?>
            </td>
          </tr>
          <tr>
            <td style="font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Unit #</td>
            <td style="font-size: 14px;color: #fff;font-weight: 600;"><?php echo $data->apartment_number ?></td>
          </tr>
          <tr>
            <td style="font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Apartment Size</td>
            <td style="font-size: 14px;color: #fff;font-weight: 600;"><?php echo $data['apartment_type']->type ?></td>
          </tr>
        </tbody>
      </table>
      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0px;">
        <tbody>
          <tr>
            <td style="font-size: 18px;color: #00263e;font-weight: 500;padding: 52px 0px 35px;">
            Hello <?php echo $data['property']['client']['user']->first_name. ' '.
                $data['property']['client']['user']->middle_name. ' '.
                $data['property']['client']['user']->last_name; ?>
            </td>
          </tr>
          <tr>
            <td style="font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 24px;">
            A new job have been created with following details.</td>
          </tr>
          <!-- <tr>
            <td style="font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit.</td>
          </tr> -->
        </tbody>
      </table>
      <?php foreach ($data['jobServices'] as $service) { ?>
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding: 27px 30px 27px;background-color:#fff;border-radius:5px;margin-top: 20px;">
            <tbody>
            <tr>
                <td style="font-size: 18px;color: #5881ce;font-weight: 700;"><?php echo $service['service']->category ?></td>
            </tr>
            
            </tbody>
        </table>
      <?php } ?>
      <?php include 'email_footer.php';?>
    </center>
  </body>
</html>