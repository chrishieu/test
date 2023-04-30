<?php 


add_action( 'wp_ajax_fm_signup_newsletter', 'fivemedia_ajax_signup_newsletter' );
add_action( 'wp_ajax_nopriv_fm_signup_newsletter', 'fivemedia_ajax_signup_newsletter' );

function fivemedia_ajax_signup_newsletter() {
    
    $first_name = (isset($_POST['first_name'])) ? esc_attr($_POST['first_name']) : '';
    $last_name = (isset($_POST['last_name'])) ? esc_attr($_POST['last_name']) : '';;
    $email = (isset($_POST['email'])) ? esc_attr($_POST['email']) : '';
    $token = (isset($_POST['token'])) ? esc_attr($_POST['token']) : '';
    $signup5Newsletter = (isset($_POST['signup5Newsletter'])) ? esc_attr($_POST['signup5Newsletter']) : '';
    $location = (isset($_POST['location']))? esc_attr($_POST['location']) : null;
    $linkSignup = (isset($_POST['linkSignup']))? esc_attr($_POST['linkSignup']) : null;
    
    
    // Checking email is valid or not
    if (!is_email($email) || strpos($email, '+') !== false) {
        echo json_encode(array(
            'success' => false,
            'data' => [],
            'message' => 'Please input a valid email address.'
        ));
        die();
    }

    if (is_email($email) && $first_name != "" && $last_name == "") {
        
        $keySecret = "6Ldhc5IiAAAAAHo88RwJ1VdGRE198PfjjGpqSrcr";
        // Check Google Captcha
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $keySecret, 'response' => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
       

        $outgg = json_decode($response);
        
        if ($outgg->success == true && $outgg->score >= 0.7) {

            //add Sendinblue             
            $link_form_sendinblue_5_newsletter = get_field('link_form_sendinblue_5_newsletter', 'options');
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $linkSignup);
            curl_setopt($ch, CURLOPT_POST, 1);
            $data_post = array(
                'FIRSTNAME' => $_POST['first_name'],
                'EMAIL' => $_POST['email']
            );
            if ($location) {
                $data_post['LOCATION'] = $location;
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_post);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            if ($signup5Newsletter == true) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $link_form_sendinblue_5_newsletter);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, array(
                    'FIRSTNAME' => $_POST['first_name'],
                    'EMAIL' => $_POST['email']
                ));

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
            }
            

//            echo json_encode(array(
//                'success' => true,
//                'data' => [],
//                'message' => 'Ok'
//            ));
//            die();
        }
    } 
    
    
    echo json_encode(array(
        'success' => true,
        'data' => [],
        'message' => 'Ok'
    ));
    die();
    
}



add_action( 'wp_ajax_fm_signup_newsletter_footer', 'fivemedia_ajax_signup_newsletter_in_footer' );
add_action( 'wp_ajax_nopriv_fm_signup_newsletter_footer', 'fivemedia_ajax_signup_newsletter_in_footer' );

function fivemedia_ajax_signup_newsletter_in_footer() {
    
    $first_name = (isset($_POST['firstname'])) ? esc_attr($_POST['firstname']) : '';
    $last_name = (isset($_POST['lastname'])) ? esc_attr($_POST['lastname']) : '';;
    $email = (isset($_POST['email'])) ? esc_attr($_POST['email']) : '';
    $token = (isset($_POST['token'])) ? esc_attr($_POST['token']) : '';
    
    
    // Checking email is valid or not
    if (!is_email($email) || strpos($email, '+') !== false) {
        echo json_encode(array(
            'success' => false,
            'data' => [],
            'message' => 'Please input a valid email address.'
        ));
        die();
    }

    if (is_email($email) && $first_name != "" && $last_name == "") {
        
        $keySecret = "6Ldhc5IiAAAAAHo88RwJ1VdGRE198PfjjGpqSrcr";
        // Check Google Captcha
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $keySecret, 'response' => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
       

        $outgg = json_decode($response);
        
    


        
        
        
        if ($outgg->success == true && $outgg->score >= 0.7) {

            //add Sendinblue 
            
            
            require_once __DIR__.'/vendor/autoload.php';
            $api_key = get_field('sendinblue_api_key', 'options');
            $sendinblue_list_id = get_field('sendinblue_newsletter_list_id', 'options');
            $config = \SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $api_key);

            $apiInstance = new SendinBlue\Client\Api\ContactsApi(
                new GuzzleHttp\Client(),
                $config
            );

            $createContact = new \SendinBlue\Client\Model\CreateContact([
                'email' => $_POST['email'],
                'attributes' => ['FIRSTNAME' => $_POST['firstname']],
                'listIds' => [6]
            ]);


            try {
                $result = $apiInstance->createContact($createContact);
            } catch (Exception $e) {
                
                $response_body = json_decode($e->getResponseBody(), true);
                
                echo json_encode([
                    'success' => false,
                    'message' => isset($response_body['message'])? $response_body['message'] : ''
                ]);
                die();
            }
            
           

//            echo json_encode(array(
//                'success' => true,
//                'data' => [],
//                'message' => 'Ok'
//            ));
//            die();
        }
    } 
    
    
    echo json_encode(array(
        'success' => true,
        'data' => [],
        'message' => 'Ok'
    ));
    die();
    
}
