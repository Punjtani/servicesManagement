<?php

use App\Models\RolePermission;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;


function get_settings()
{
    $mail_setting = Setting::where('key', "mail_data")->value("value");

    if ($mail_setting) {
        $mail_setting = json_decode($mail_setting);
        if (isset($mail_setting)) {
            $config = array(
                'driver'     => $mail_setting->mail_mailer ?? '',
                'host'       => $mail_setting->mail_host ?? '',
                'port'       => $mail_setting->mail_port ?? '',
                'encryption' => $mail_setting->mail_encryption ?? '',
                'username'   => $mail_setting->mail_username ?? '',
                'password'   => $mail_setting->mail_password ?? '',
                "from_address" => $mail_setting->mail_from_address ?? '',
                "cc_address" => $mail_setting->mail_cc_address ?? '',
                "bcc_address" => $mail_setting->mail_bcc_address ?? '',
                "admin_address" => $mail_setting->mail_admin_address ?? '',
                "from_name" => $mail_setting->mail_from_name ?? '',
            );
            return Config::set('mail', $config);
        }
    }
}
