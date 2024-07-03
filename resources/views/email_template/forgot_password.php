<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <style>
      #outlook a {
        padding: 0;
      }
    </style>
  </head>
  <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style=" width: 100%!important;margin: 0;padding: 0;font-family: 'Montserrat', sans-serif;">

  <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;background-color: #f1f5f8;">
    <tbody>
        <tr>
            <td  style="padding:10px;">
              <center>
                <?php include 'email_header.php';?>
      
                <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
                  <tbody>
                      <tr>
                        <td style="border-collapse: collapse; ">
                            <h2 style="font-size: 36px;font-weight: 400;color: #000;margin-top: 10px;margin-bottom: 5px;">Reset Password</h2>
                        </td>
                      </tr>
                  </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding: 0x;margin-top: 10px;">
                    <tbody>
                        <tr>
                            <td style="padding: 20px 20px 20px;background-color:#fff;border-radius:5px;">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
                                    <tbody>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 18px;color: #00263e;font-weight: 500;padding: 20px 0px 10px;">
                                            Hello!
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                                            You are receiving this email because we received a password reset request for your account.
                                            </td>                        
                                        </tr>
                                        <tr>
                                            <td style="text-align:center; border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                                                <a href="<?php echo $link; ?>" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; border-radius: 4px; color: #fff; display: inline-block; overflow: hidden; text-decoration: none; background-color: #2d3748; border-bottom: 8px solid #2d3748; border-left: 18px solid #2d3748; border-right: 18px solid #2d3748; border-top: 8px solid #2d3748;">Reset Password</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                                                This password reset link will expire in 1200 minutes
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                                                Regards,
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                                                RESS
                                            </td>
                                        </tr>
                                        <tr>&nbsp;</tr>
                                        <tr><td><hr></td></tr>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">                            
                                                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">                            
                                                <?php echo $link; ?>
                                            </td>
                                        </tr> 
                                    </tbody>
                                </table>                                
                            </td>
                        </tr>
                                              
                    </tbody>
                </table>
              <?php include 'email_footer.php';?>
            </center>
            </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>

