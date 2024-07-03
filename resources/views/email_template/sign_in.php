<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In Information</title>
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
      <?php include 'email_header.php';?>
      
    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
    <tbody>
        <tr>
        <td>
            <h2 style="font-size: 36px;font-weight: 400;color: #000;margin-top: 10px;margin-bottom: 10px;">Sign In Information</h2>
        </td>
        </tr>
    </tbody>
    </table>

      <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding:0px;">
        <tbody>
          <tr>
            <td style="font-size: 18px;color: #00263e;font-weight: 500;padding: 20px 0px 10px;">
            Hello <?php echo $data->first_name. ' '.
                $data->middle_name. ' '.
                $data->last_name; ?>
            </td>
          </tr>
          <tr>
            <td style="font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
            Here are the Sign in credentials, you can use to access Ress Management Portal .</td>
          </tr>
        </tbody>
      </table>
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="640" style="height:100%!important;margin:0;padding: 20px 20px 20px;background-color:#fff;border-radius:5px;margin-top: 10px;">
            <tbody>
            <tr>
                <td style="font-size: 18px;color: #666666;font-weight: 700;"><span style="color: #f7a33d;">Email: </span><?php echo $data->email ?></td>
            </tr>
           
            <tr>
                <td style="font-size: 14px;color: #666666;font-weight: 500;padding-top: 11px;"><span style="font-size: 14px;color: #f7a33d;font-weight: 700;">Password: </span><?php echo $data->password ?></td>
            </tr>

            </tbody>
        </table>
      <?php include 'email_footer.php';?>
    </center>
  </body>
</html>