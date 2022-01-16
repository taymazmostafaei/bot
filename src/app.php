<?php

$APP;

require_once 'vendor/autoload.php';
require_once 'server/model.php';

use ShadPHP\ShadPHP;

$account = new ShadPHP(989394097242); // Only without zero and with area code 98

$account->onUpdate(function (array $update) use ($account) {
    if (isset($update['data_enc'])) {
        $message = $update['data_enc'];
        foreach ($message['message_updates'] as $value) {
            $messageContent = $value['message'];
            $type = $messageContent['type'];
            $author_type = $messageContent['author_type'];
            $author_object_guid = $messageContent['author_object_guid'];
            if ($author_type == 'User' && $type == 'Text') {
                $text = (string)$messageContent['text'];
                if ( isset($APP[$author_object_guid]['IsOnRequest']) ) {
                    $account->sendMessage($author_object_guid, 'تشکر پیغام شما به مدیریت ارسال شد');
                    
                    $account->sendMessage('u0SpKI0d9f29b277b49d53b333fb4734', $APP[$author_object_guid]['first_name'] . ' ' . $APP[$author_object_guid]['last_name'] .' : '. $text);

                    unset($APP[$author_object_guid]['IsOnRequest']);
                }
                else if (preg_match('/([0-9]){10}/', $text) && strlen($text) == 10) {
                    $student = find_code($text) ?: null;
                    if ($student) {

                        $text = choose_mood($student['first_name'], $student['last_name']);
                        $account->sendMessage($author_object_guid, $text);
                        
                        $APP[$author_object_guid] = [
                            'code' => $student['code'],
                            'first_name' => $student['first_name'],
                            'last_name' => $student['last_name'],
                        ];

                    } else {
                        $account->sendMessage($author_object_guid, 'کدملی پیدا نشد');
                    }
                }elseif ( $text == 2 && isset($APP[$author_object_guid])) {
                    $code = find_nessom_user($APP[$author_object_guid]['code']);
                    $account->sendMessage($author_object_guid, nessome_response($code['nessom_user'], $code['nessom_pass']));
                }else if($text == 1 && isset($APP[$author_object_guid])){
                    $APP[$author_object_guid]['IsOnRequest'] = true;
                    $account->sendMessage($author_object_guid, 'پیغام خود را وارد کنید');
                }elseif ( $text == 3 && isset($APP[$author_object_guid]) ) {
                    $message = report_card($APP[$author_object_guid]['code'], '58965435');
                    $account->sendMessage($author_object_guid, $message);
                }
                else{
                    $account->sendMessage($author_object_guid, 'دستور نامعتبر');
                }
            }
        }
    }
});