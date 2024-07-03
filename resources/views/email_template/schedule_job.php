<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Schedule Job</title>
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
                            <h2 style="font-size: 36px;font-weight: 400;color: #000;margin-top: 10px;margin-bottom: 5px;">Schedule Job</h2>
                        </td>
                      </tr>
                  </tbody>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;">
                  <tbody>
                    <tr>
                      <td colspan="2" style="border-collapse: collapse; padding: 20px 20px 20px;background:#5881cd;border-radius: 5px;">

                          <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
                              <tbody>
                              <tr style="border-collapse: collapse; padding:0;">
                                      <td colspan="2" style="border-collapse: collapse; padding:0;">
                                        <span style="margin: 0px 0px 10px;font-size: 24px;color: #fff;font-weight: 500;padding:0">
                                        <?php echo $data['property']->title. ' ('.
                                          $data['property']['client']['user']->first_name. ' '.
                                          $data['property']['client']['user']->middle_name. ' '.
                                          $data['property']['client']['user']->last_name. ')'; ?>
                                        </span>
                                      </td>
                                    </tr>

                                    <tr style="border-collapse: collapse; padding:0;">
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;width: 187px;border:none;padding:0;">Property Address</td>
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;border:none;padding:0;">
                                          <?php echo $data['property']->street1.' '.
                                              $data['property']->city.', '.
                                              $data['property']->state . ',' .
                                                  $data['property']->country .
                                                  ' ' .
                                                  $data['property']->zipcode; ?>
                                      </td>
                                    </tr>
                                    <?php if($data['property']['manager']){ ?>
                                    <tr style="border-collapse: collapse; padding:0;">
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;width: 187px;padding:0;">Property Manager</td>
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;padding:0;">
                                        <?php echo $data['property']['manager']->first_name. ' ' .
                                                  $data['property']['manager']->middle_name. ' ' .
                                                  $data['property']['manager']->last_name ?>
                                      </td>
                                    </tr>
                                    <?php } ?>
                                    <tr style="border-collapse: collapse; padding:0;">
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Unit #</td>
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;"><?php echo $data->apartment_number ?></td>
                                    </tr>
                                    <tr style="border-collapse: collapse; padding:0;">
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Apartment Size</td>
                                      <td style="border-collapse: collapse; font-size: 14px;color: #fff;font-weight: 600;"><?php echo isset($data['apartment_type'])? $data['apartment_type']->type : ""; ?></td>
                                    </tr>
                              </tbody>
                          </table>
                        </td>
                      </tr>
                  </tbody>
              </table>
              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0px;">
                <tbody>
                  <tr>
                    <td style="border-collapse: collapse;font-size: 18px;color: #00263e;font-weight: 500;padding: 20px 0px 10px;">
                    Hello <?php echo $data['property']['client']['user']->first_name. ' '.
                        $data['property']['client']['user']->middle_name. ' '.
                        $data['property']['client']['user']->last_name; ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="border-collapse: collapse;font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                    The following services have been scheduled . Please see details below.</td>
                  </tr>
                </tbody>
              </table>
              <?php foreach ($data['jobServices'] as $service) { ?>
                <table border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding: 0x;margin-top: 10px;">
                    <tbody>
                        <tr>
                            <td style="padding: 20px 20px 20px;background-color:#fff;border-radius:5px;">
                              <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
                                <tbody>
                                          <tr>
                                            <td style="border-collapse: collapse;font-size: 18px;color: #5881ce;font-weight: 700;"><?php echo $service['service']->category ?></td>
                                        </tr>
                                        <?php if ($service->anytime == 1) { ?>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #000000;font-weight: 600;padding-top: 5px;">
                                            Scheduled - <?php echo date('m/d/Y', strtotime($service->scheduled_date ) ); ?> @ Anytime
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php if ($service->anytime == 0) { ?>
                                        <tr>
                                            <td style="border-collapse: collapse;font-size: 14px;color: #000000;font-weight: 600;padding-top: 5px;">
                                            Scheduled - <?php echo date('m/d/Y', strtotime($service->scheduled_date ) ); ?> @
                                            <?php echo $service->scheduled_time?> </td>
                                        </tr>
                                        <?php } ?>
                                </tbody>
                              </table>

                            </td>
                          </tr>
                    </tbody>
                </table>
              <?php } ?>
              <?php include 'email_footer.php';?>
            </center>
            </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>

