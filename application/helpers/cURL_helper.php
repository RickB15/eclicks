<?php

    // Getting Retaltion ID from Bizzmail
    function getRelationID($bizz_url, $host_email, $guest_email){
        $fetch_url = $bizz_url."v1/scheduler/relation"."?hostEmail=".$host_email."&guestEmail=".$guest_email;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, 'Content-Type:application/json');
        // curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close ($ch);

        return $result;
    }

    // Sending Emails to the relations
    function sendEmail($bizz_url,$targetID,$eventTime,$guestName,$mailApiKey,$mailId){
        $fetch_url = $bizz_url."v1/send/email/".$mailId;
        $authorization = 'Authorization: '.$mailApiKey;
        // echo $authorization."<br>";
        // echo $fetch_url."<br>";
        $data = array(
            'target' => $targetID,
            'type' => 'relation',
            'events' => array(
                'eventTime' => $eventTime,
                'guestName' => $guestName
            )
        );
        $payload = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );

        $result = curl_exec($ch);
        curl_close($ch);

        echo $result."<br>";
    }

    // Sending SMS to the relations
    function sendSMS($bizz_url,$targetID,$eventTime,$smsApiKey,$smsId){
        $fetch_url = $bizz_url."v1/send/sms/".$smsId;
        $authorization = 'Authorization: '.$smsApiKey;
        // echo $authorization."<br>";
        // echo $fetch_url."<br>";
        $data = array(
            'target' => $targetID,
            'type' => 'relation',
            'eventTime' => $eventTime
        );
        $payload = json_encode($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );

        $result = curl_exec($ch);
        curl_close($ch);

        echo $result."<br>";
    }

    // Getting Overview Details
    function getOverviewDetails($bizz_url,$apiKey){
        $fetch_url = $bizz_url."v1/scheduler/overview"."?apiKey=".$apiKey;
        $authorization = 'Authorization: '.$apiKey;
        // echo $authorization."<br>";
        // echo $fetch_url."<br>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );
        $result = curl_exec($ch);
        curl_close ($ch);

        return $result;
    }

    // Getting Notification Emails
    function getNotificationEmails($bizz_url, $apiKey){
        $fetch_url = $bizz_url."v1/scheduler/notificationEmails"."?apiKey=".$apiKey;
        $authorization = 'Authorization: '.$apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );
        $result = curl_exec($ch);
        curl_close ($ch);

        return $result;
    }

    // Getting Notification Sms
    function getNotificationSms($bizz_url, $apiKey){
        $fetch_url = $bizz_url."v1/scheduler/notificationSms"."?apiKey=".$apiKey;
        $authorization = 'Authorization: '.$apiKey;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );
        $result = curl_exec($ch);
        curl_close ($ch);

        return $result;
    }

    // Saving username to bizzmail if username is null
    function saveUsername($bizz_url, $username, $apiKey){
        $fetch_url = $bizz_url."v1/scheduler/saveUsername";
        $authorization = 'Authorization: '.$apiKey;

        $data = array(
            "api_key" => $apiKey,
            "username" => $username
        );
        $payload = json_encode($data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );

        $response = curl_exec($ch);

        return $response;
    }

    // Creating new group
    function creatingGroup($bizz_url,$apiKey){

        $fetch_url = $bizz_url."v1/scheduler/createNewGroup";
        $authorization = 'Authorization: '.$apiKey;

        $data = array(
            "title" => "Client Schedular"
        );
        $payload = json_encode($data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );

        $response = curl_exec($ch);

        return $response;
    }

    // Copying mail if doesn't exist
    function copyMails($bizz_url,$apiKey){
        require_once('configMailMeta.php');

        $fetch_url = $bizz_url."v1/scheduler/copyMails";
        $authorization = 'Authorization: '.$apiKey;

        $mails = array(
            $appointmentConfirmNL,
            $appointmentConfirmENG,
            $mail24hr,
            $mail1hr,
            $mail10min,
            $appointmentCancel,
            $notificationBooking,
        );
        
        $data = array(
            "mails" => $mails
        );

        $payload = json_encode($data);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fetch_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $authorization,
            'Content-Type: application/json')
        );

        $response = curl_exec($ch);

        return $response;

    }
?>