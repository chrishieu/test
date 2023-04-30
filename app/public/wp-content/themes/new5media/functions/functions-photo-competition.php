<?php

function fivemedia_get_user_ip() {
    if (isset($_SERVER)) {

        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"];

        if (isset($_SERVER["HTTP_CLIENT_IP"]))
            return $_SERVER["HTTP_CLIENT_IP"];

        return $_SERVER["REMOTE_ADDR"];
    }
    
    return "None";
}

define("RECAPTCHA_V3_SECRET_KEY", '6LeOz2waAAAAAEgnZRedHu3rIXZQpUFw_DDs0bEX');
add_action( 'wp_ajax_vote', 'fivemedia_vote' );
add_action( 'wp_ajax_nopriv_vote', 'fivemedia_vote' );
function fivemedia_vote() {
    //Disable vote function
    echo ""; die();
  
//    require_once(__DIR__ . '/vendor/autoload.php');
//    
//    global $wpdb;
//    
//    if (!session_id()) { session_start(); }
//    
//    $vote = (isset($_POST['photoId'])) ? esc_attr($_POST['photoId']) : '';
//    $token = (isset($_POST['token'])) ? esc_attr($_POST['token']) : '';
//    $first_name = (isset($_POST['first_name'])) ? esc_attr($_POST['first_name']) : '';
//    $last_name = (isset($_POST['last_name'])) ? esc_attr($_POST['last_name']) : '';;
//    $email = (isset($_POST['email'])) ? esc_attr($_POST['email']) : '';
//    
//    
//    $apiKey = get_field('sendinblue_api_key', 'options');
//    $templateId = (int) get_field('sendinblue_template_id', 'options');
//    $page_thanks = get_field('page_thanks_for_confirming_the_votes', 'options');
//    
//    // Checking email is valid or not
//    if (!is_email($email) || strpos($email, '+') !== false) {
//        echo json_encode(array(
//          'success' => false,
//          'data' => [],
//          'message' => 'Please input a valid email address.'
//        ));
//        die();
//    }
//    
//    
//    
//    if (is_email($email) && $first_name != "" && $last_name != "" && $vote != "") {
//        // Check Google Captcha
//            $ch = curl_init();
//          curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
//          curl_setopt($ch, CURLOPT_POST, 1);
//          curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $token)));
//          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//          $response = curl_exec($ch);
//          curl_close($ch);
//
//          $outgg = json_decode($response);
//          if($outgg->success == true && $outgg->score >= 0.7 ) {
//      
//              //Check Email Exist in System
//              $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM fi_photo_competition WHERE email = %s', $email));
//              if ($row) {
//                  echo json_encode(array(
//                      'success' => false,
//                      'data' => [],
//                      'message' => 'This email address already exists in our voting system.'
//                  ));
//                  die();
//              }
//            
//            
//              $hashString = md5(uniqid());
//              $currentTime = new \DateTime();
//              $result = $wpdb->insert( 
//                  'fi_photo_competition', 
//                  array( 
//                      'hash' => $hashString, 
//                      'first_name' => $first_name,
//                      'last_name' => $last_name,
//                      'email' => $email,
//                      'votes' => $vote,
//                      'ip_address' => fivemedia_get_user_ip(),
//                      'session_id' => session_id(),
//                      'time_created' => $currentTime->format('Y-m-d H:i:s'),
//                      'is_confirm' => 0
//                  ), 
//                  array( 
//                      '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'
//                  ) 
//              );
//
//              // Sendemail
//              $config = SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $apiKey);
//
//
//              $apiInstance = new SendinBlue\Client\Api\SMTPApi(
//                  new GuzzleHttp\Client(),
//                  $config
//              );
//
//              $sendEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
//              $sendEmail['to'] = array(array('email'=> $email));
//
//              $sendEmail['templateId'] = $templateId;
//              $sendEmail['params'] = array(
//                      'FIRSTNAME' => $first_name, 
//                      'LASTNAME' => $last_name,
//                      'LINK_CONFIRMATION' => get_permalink($page_thanks).'?photocontest='.$hashString
//              );
//              $sendEmail['attributes'] = $sendEmail['params'];
//
//              try {
//                  $result = $apiInstance->sendTransacEmail($sendEmail);
//              } catch (Exception $e) {
//                  echo json_encode(array(
//                      'success' => false,
//                      'data' => []
//                  ));
//                  die();
//              }
//
//              echo json_encode(array(
//                  'success' => true,
//                  'data' => []
//              ));
//              die();
//          } else {
//              echo json_encode(array(
//                  'success' => false,
//                  'data' => [],
//                  'message' => 'There was an error with ReCaptcha. Please refresh the page and try again!'
//              ));
//              die();
//          }
//        
//
//    }
//    
//    echo json_encode(array(
//        'success' => false,
//        'data' => [],
//        'message' => 'An error occured when submitting the form. Please refresh the page and try again!'
//    ));
//    die();
}


add_action('init', 'fivemedia_count_photo_competition');

function fivemedia_count_photo_competition() {
    global $wpdb;
    
    //Check has is exist
    if (!isset($_GET['photocontest']) || $_GET['photocontest'] == "") {
        return;
    }
    
    $hash = esc_attr($_GET['photocontest']);
    
    
    //Check vote is valid
    $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM fi_photo_competition WHERE hash = %s AND is_confirm = 0 ", $hash));
    
   
    if ($row) {
        // Update votes count
        $photoIds = $row->votes;
        $listPhoto = explode('-', $photoIds);
        
        foreach($listPhoto as $photoId) {
            $vote = get_field('voting', $photoId);
            if (!is_numeric(intval($vote))) {
                $vote = 0;
            }
            $vote = $vote + 1;
            update_field( 'voting', $vote, $photoId );
        }
        // set current vote is valid
        $wpdb->update( 
            'fi_photo_competition', 
            array( 
                'is_confirm' => 1
            ), 
            array( 'hash' => $hash ), 
            array( 
                '%s'
            ), 
            array( '%d' ) 
        );
        
    }
}

