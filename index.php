<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">        
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\"/>
        <meta content=\"text/html; charset=utf-8\" http-equiv=\"Content-Type\">
        <meta content=\"IE=edge,chrome=1\" http-equiv=\"X-UA-Compatible\">
        <meta content=\"no-cache,no-store,must-revalidate,max-age=-1\" http-equiv=\"Cache-Control\">
        <meta content=\"no-cache,no-store\" http-equiv=\"Pragma\">
        <meta content=\"-1\" http-equiv=\"Expires\">
        <meta content=\"Serge M Frezier\" name=\"author\">
        <meta content=\"INDEX,FOLLOW\" name=\"robots\">
        <meta content=\"\" name=\"keywords\">
        <meta content=\"\" name=\"description\">
        <!--<meta name=\"mobile-web-app-capable\" content=\"yes\">-->
        <style>
            table#taMain {border: 5px solid grey;border-collapse: collapse;}
            table#taMain > thead {color:white; font-weight:bold}
            table#taMain > thead > tr {background-color:orange;}
            table#taMain > thead > tr > td {padding:4px; width:100px!important; max-width:100px!important; min-width:100px!important}
            table#taMain > tbody {color:green;}
            table#taMain > tbody > tr > td {border: 0.5px solid orange;}
            table#taMain > tfoot {color:yellow;}
        </style>
    </head>
    <body>
        <h1>REST APIs</h1>
        <?php

            function get_browser_name($user_agent){
                if(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')){
                    return 'Opera';
                }elseif (strpos($user_agent, 'Edge')){                    
                    return 'Edge';
                }elseif(strpos($user_agent, 'Chrome')){ 
                    return 'Chrome';
                }elseif (strpos($user_agent, 'Safari')){
                    return 'Safari';
                }elseif (strpos($user_agent, 'Firefox')){
                    return 'Firefox';
                }elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')){
                    return 'Internet Explorer';
                }else{
                    return 'Other';
                }
            }

            // Usage:
            echo get_browser_name($_SERVER['HTTP_USER_AGENT'])."<br /><br />";
        ?>
        <?php
            $user_ip=$_SERVER["REMOTE_ADDR"];
            echo 'IPV6 127.0.0. -> '.$user_ip.'<br />';
            $timezone = date_default_timezone_get();
            echo "The current server timezone is: " . $timezone."<br />";
            echo date('l jS \of F Y h:i:s A')."<br /><br />";
        ?>
        <h2>Test REST_1_JSON</h2>
        <h3>Login POST: Displaying users records from the users table Rest API<br /><a href="http://localhost:81/REST_APIs_PHP/REST_1_JSON/login/" target="_blank">URL http://localhost:81/REST_APIs_PHP/REST_1_JSON/login/</a></h3>
    </body>
</html>