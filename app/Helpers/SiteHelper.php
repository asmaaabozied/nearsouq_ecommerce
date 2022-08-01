<?php

namespace App\Helpers;
class SiteHelper
{
    public static function sendMessage(string $player_ids, string $body, string $title,  string $environment, string $type = NULL, string $id = NULL, string $image_url = NULL): string
    {
        //dd($image_url);
        if($environment === 'web'){
            $app_id = "bfade32e-b06a-4f8f-9845-86a9d8c8b0ad";
        }else{
            $app_id = "10061170-611a-4764-9149-550b481ae906";
        }
        $content = array(
            "ar" => trans($body),
            "en" => trans($body),
        );

        $content_title = array(
            "ar" => trans($title),
            "en" => trans($title),
        );

        $fields = array(
            'app_id' => $app_id,
            'include_player_ids' => array($player_ids),
            'contents' => $content,
            'headings' => $content_title
        );

        $fields['data'] = array(
            "type" => $type,
            "payload" => $id,
            "image_url" => $image_url,
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);
        //dd($response);
        return $response;
    }

}
