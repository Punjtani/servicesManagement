<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complete Job</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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

                    <table border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
                      <tbody>
                          <tr>
                            <td>
                                <h2 style="font-size: 36px;font-weight: 400;color: #000;margin-top: 10px;margin-bottom: 5px;">Completed Job</h2>
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
                                    <tr>
                                        <td colspan="2" style="">
                                          <h3 style="margin: 0px 0px 10px;font-size: 24px;color: #fff;font-weight: 500;">
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
                                      <?php if($data['property']['manager']){ ?>
                                      <tr>
                                        <td style="font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Property Manager</td>
                                        <td style="font-size: 14px;color: #fff;font-weight: 600;">
                                          <?php echo $data['property']['manager']->first_name. ' ' .
                                                    $data['property']['manager']->middle_name. ' ' .
                                                    $data['property']['manager']->last_name ?>
                                        </td>
                                      </tr>
                                      <?php } ?>
                                      <tr>
                                        <td style="font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Unit #</td>
                                        <td style="font-size: 14px;color: #fff;font-weight: 600;"><?php echo $data->apartment_number ?></td>
                                      </tr>
                                      <tr>
                                        <td style="font-size: 14px;color: #fff;font-weight: 600;width: 187px;">Apartment Size</td>
                                        <td style="font-size: 14px;color: #fff;font-weight: 600;"><?php echo isset($data['apartment_type']) ? $data['apartment_type']->type  : ""; ?></td>
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
                            <td style="font-size: 18px;color: #00263e;font-weight: 500;padding: 20px 0px 10px;">
                            Hello <?php echo $data['property']['client']['user']->first_name. ' '.
                                $data['property']['client']['user']->middle_name. ' '.
                                $data['property']['client']['user']->last_name; ?>
                            </td>
                          </tr>
                          <tr>
                            <td style="font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;padding-bottom: 10px;">
                            A job services have been completed with following details.</td>
                          </tr>
                          <!-- <tr>
                            <td style="font-size: 14px;color: #00263e;font-weight: 500;line-height: 1.7;">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit.</td>
                          </tr> -->
                        </tbody>
                      </table>
                        <?php foreach ($data['jobServices'] as $service) { ?>
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding: 0;margin-top: 20px;">
                              <tbody>
                              <tr>
                                  <td style="padding: 20px 20px 20px;background-color:#fff;border-radius:5px;">
                                      <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="600" style="height:100%!important;margin:0;padding:0;border-collapse: collapse;">
                                        <tbody>
                                                <tr>
                                                    <td style="font-size: 18px;color: #5881ce;font-weight: 700;"><?php echo $service['service']->category ?></td>
                                                </tr>
                                                <?php if ($service->assigned_to_type == 'individual') { ?>
                                                <tr>
                                                    <td style="font-size: 14px;color: #000000;font-weight: 500;padding-top: 6px;"><strong style="width: 105px;font-weight: 600;display: inline-block;">Employee</strong>
                                                    <?php echo $service['employee']['user']->first_name.' '.
                                                    $service['employee']['user']->middle_name.' '.
                                                    $service['employee']['user']->last_name?></td>
                                                </tr>
                                                <?php } ?>
                                                <?php if ($service->assigned_to_type == 'crew') { ?>
                                                <tr>
                                                    <td style="font-size: 14px;color: #000000;font-weight: 500;padding-top: 5px;"><strong style="font-weight: 600;width: 105px;display: inline-block;">Crew Name</strong>
                                                    <?php echo $service['employeeCrew']->name ?></td>
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
