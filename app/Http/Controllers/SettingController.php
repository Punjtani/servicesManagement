<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::where('key', "qbo_oauth_data")->value("value");
        $mail_setting = Setting::where('key', "mail_data")->value("value");
        $data = [];
        if ($setting) {
            $setting = json_decode($setting);
            $data["client_id"] = $setting->client_id;
            $data["client_secret"] = $setting->client_secret;
            $data["redirect_url"] = $setting->redirect_url;
            $data["environment"] = $setting->environment;
        }
        if($mail_setting)
        {
            $mail_setting = json_decode($mail_setting);
            $data["mail_mailer"] = $mail_setting->mail_mailer ?? '';
            $data["mail_host"] = $mail_setting->mail_host ?? '';
            $data["mail_port"] = $mail_setting->mail_port ?? '';
            $data["mail_username"] = $mail_setting->mail_username ?? '';
            $data["mail_password"] = $mail_setting->mail_password ?? '';
            $data["mail_encryption"] = $mail_setting->mail_encryption ?? '';
            $data["mail_from_address"] = $mail_setting->mail_from_address ?? '';
            $data["mail_cc_address"] = $mail_setting->mail_cc_address ?? '';
            $data["mail_bcc_address"] = $mail_setting->mail_bcc_address ?? '';
            $data["mail_admin_address"] = $mail_setting->mail_admin_address ?? '';
            $data["mail_from_name"] = $mail_setting->mail_from_name ?? '';
        }
        return response()->json([
            'settings' => $data,
        ], 200);
    }
    public function store(Request $request)
    {
        $setting = Setting::where('key', "qbo_oauth_data")->first();
        $mail_setting = Setting::where('key', "mail_data")->first();
        if (!$setting) {
            $setting = new Setting();
        }
        if (!$mail_setting) {
            $mail_setting = new Setting();
        }
        $data["client_id"] = $request->client_id;
        $data["client_secret"] = $request->client_secret;
        $data["redirect_url"] = $request->redirect_url;
        $data["environment"] = $request->environment;
        $mail_data["mail_mailer"] = $request->mail_mailer;
        $mail_data["mail_host"] = $request->mail_host;
        $mail_data["mail_port"] = $request->mail_port;
        $mail_data["mail_username"] = $request->mail_username;
        $mail_data["mail_password"] = $request->mail_password;
        $mail_data["mail_encryption"] = $request->mail_encryption;
        $mail_data["mail_from_address"] = $request->mail_from_address;
        $mail_data["mail_cc_address"] = $request->mail_cc_address;
        $mail_data["mail_bcc_address"] = $request->mail_bcc_address;
        $mail_data["mail_admin_address"] = $request->mail_admin_address;
        $mail_data["mail_from_name"] = $request->mail_from_name;
        $setting->key = "qbo_oauth_data";
        $setting->value = json_encode($data);
        $mail_setting->key = "mail_data";
        $mail_setting->value = json_encode($mail_data);
        $setting->save();
        $mail_setting->save();
        return response()->json([
            // 'status' => true
        ], 200);
    }
}
