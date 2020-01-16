<?php

function forms() {
    wp_enqueue_script('recaptcha-js', 'https://www.google.com/recaptcha/api.js?onload=onloadCallback', array('jquery'), true);
    wp_enqueue_script('forms', '/forms.js', array('jquery'), true);
    wp_localize_script( 'forms', '__formVars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'ajax_nonce' => wp_create_nonce('send_form'),
        'app_lang' => defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'pl',
        'sitekey' => CAPTCHA_KEY
    ));
}
add_action( 'wp_enqueue_scripts', 'forms' );

function add_async_attribute($tag, $handle) {
    if ( 'recaptcha-js' !== $handle )
        return $tag;
    return str_replace( '>', ' async defer>', $tag );
}
add_filter('script_loader_tag', 'add_async_attribute', 10, 2);

if( !function_exists('is_production') ) {
    function is_production() {
        return !WP_DEBUG;
    }
}

/* ReCaptcha Keys */
if(!WP_DEBUG) {
    $captcha = '6LepP0IUAAAAACM8orMV9BWPf6pd2Vn727ZS5PNV';
} else {
    $captcha = '6LdqnkcUAAAAALWVzZYHy_vV_C6BnHsYLVJyjXbu';
}

define('CAPTCHA_KEY', $captcha);

/* Log to a file */
function logfile($firstname, $lastname, $email, $phone, $message, $form_type) {
    
}

/* Mail delivery */
function deliver_mail() {
    $form_type = $_POST['form'];

    if ($form_type  && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $firstname = sanitize_text_field( isset($_POST["first-name"]) ? $_POST["first-name"] : '' );
        $lastname = sanitize_text_field( isset($_POST["last-name"]) ? $_POST["last-name"] : '' );
        $email   = sanitize_email( $_POST["email"] );
        $phone   = sanitize_text_field( isset($_POST["phone"]) ? $_POST["phone"] : '');
        $subject = sanitize_text_field( isset($_POST["subject"]) ? $_POST["subject"] : '' );
        $apartment_id = sanitize_text_field( isset($_POST["apartment_id"]) ? $_POST["apartment_id"] : '' );       
        $finance = (int) sanitize_text_field( isset($_POST["finance"]) ? $_POST["finance"] : '' );       
        $msg = $message = esc_textarea( $_POST["message"] );
        $rooms = (int) sanitize_text_field( isset($_POST["rooms"]) ? $_POST["rooms"] : '' );       
        
        if(is_production()) {                    
            $to = 'lead_www@echo.com.pl';
        } else {
            $to = 'karol.lech@gmail.com';
        }

        switch ($form_type) {
            case 'finance':
                $content = get_field('content', get_page_by_path( 'finansowanie' ));
                foreach ($content as $key => $item) {
                    if($item['acf_fc_layout'] == 'finance'){
                        $finance_people = $item['finance_people'];
                        if($finance_email = sanitize_email($finance_people[$finance]['e-mail'])){
                            $to = array($finance_email);
                        }
                    }
                }                
                break;
            case 'offer':
                
                break;
            case 'contact':
               
                break;
            case 'homepage':
                
                break;
            default:
                
                
        }        

        if(is_production()) {
            $headers[] = 'From: ECHO ESENCJA < no-replay@naszejezyce.pl >' ;
        } else {
            $headers[] = 'From: ECHO ESENCJA < no-replay@serwer1482529.home.pl >' ;
        }
        
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'Reply-To: ' . $email;

        $message = '
        <html>
        <body>
            <p>'.$firstname.' '.$lastname.' ('.$email.')</p>
			<p>Telefon:'.$phone.'</p>
            <p>Wiadomość:</p>
            <p>'.$message.'</p>
            <p>'.(($rooms) ? 'Liczba pokoi: '.$rooms : "").'</p>
        </body>
        </html>
        ';

        $name = $firstname.' '.$lastname;

        if (wp_mail( $to, $subject, $message, implode("\r\n", $headers) )) {
            do_action('form_submited', $name, $email, $to, $subject, $message );
            
            //#177 ipresso/pivotal integration
            $ipressoi = get_ipresso_integration();
            $data = array(
                'fname' => $firstname,
                'lname' => $lastname,
                'email' => $email,
                'phone' => $phone,
                'message' => strip_tags($message),
                'agreement' => array('5' => 1, '6' => 1, '7' => 1)
            );
            if($form_type == 'offer'){
                $data['Mieszkanie'] = $apartment_id;
            }
            $response = $ipressoi->api->addContact($data);

            if($form_type == 'offer'){
                $data = $ipressoi->get_apartment_data($apartment_id);

                $apartment = new StdClass();
                $apartment->building_id = $data->BUDYNEK_ID;
                $apartment->apartment_id = $data->LOKAL_ID;
                $apartment->floor_id = $data->PIETRO_ID;
                $apartment->area = $data->POWIERZCHNIA;
                $apartment->rooms = $data->LICZBA_POKOI;
                $apartment->extras = array(
                    "ogrod" => $data->OGROD,
                    "taras" => $data->TARAS,
                    "balkon" => $data->BALKON,
                    "loggia" => $data->LOGGIA,
                    "antresola" => $data->ANTRESOLA,
                );
                $apartment->price = $data->CENA;

                $response = $ipressoi->api->addActivitySendQuestion($apartment);
            }
            
            return array('success' => 'Send OK');
        } else {
            return array('error' => '<i class="icon-warning" aria-hidden="true"></i> ' . __("Formularz zawiera błędy", "echo"));
        }
        
    }
    return array('error' => '<i class="icon-warning" aria-hidden="true"></i> ' . __("Formularz zawiera błędy", "echo"));
}

function send_confirmation_mail($email) {

    if(is_production()) {        
        $headers[] = 'From: ECHO ESENCJA - ECHO INVESTMENT < lead_www@echo.com.pl >' ;
    } else {
        $headers[] = 'From: ECHO ESENCJA - ECHO INVESTMENT < karol.lech@gmail.com >' ;
    }
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    if(is_production()) {       
        $headers[] = 'Reply-To: lead_www@echo.com.pl';
    } else {
        $headers[] = 'Reply-To: karol.lech@gmail.com';
    }

    if (ICL_LANGUAGE_CODE=='pl') {
        $subject = 'ECHO ESENCJA - zapytanie ze strony WWW';
        $message = '
        <html>
        <body>
        <p>Szanowni Państwo, <br>
        Dziękujemy za kontakt. Zapytanie zostało przekazane do Biura Sprzedaży.
        Biuro Sprzedaży pracuje od poniedziałku do piątku w godzinach 9.00 - 17.00.
        Prosimy, nie odpowiadać na ten e-mail, gdyż został on wysłany przez automat.</p>
        </body>
        </html>
        ';
    }else{
        $subject = 'ECHO ESENCJA - question from the website';
        $message = '
        <html>
        <body>
        <p>Welcome.<br>
        thank you for contacting us. Your enquiry has been forwarded to our Sales Office.
        Our Sales Office is open from Monday to Friday, from 9 a.m. to 5 p.m.
        This message has been generated automatically. Please do not reply to it.</p>
        </body>
        </html>
        ';
    }

    wp_mail( $email, $subject, $message, implode("\r\n", $headers) );


}
if (!function_exists('http_response_code')) {
        function http_response_code($code = NULL) {

            if ($code !== NULL) {

                switch ($code) {
                    case 100: $text = 'Continue'; break;
                    case 101: $text = 'Switching Protocols'; break;
                    case 200: $text = 'OK'; break;
                    case 201: $text = 'Created'; break;
                    case 202: $text = 'Accepted'; break;
                    case 203: $text = 'Non-Authoritative Information'; break;
                    case 204: $text = 'No Content'; break;
                    case 205: $text = 'Reset Content'; break;
                    case 206: $text = 'Partial Content'; break;
                    case 300: $text = 'Multiple Choices'; break;
                    case 301: $text = 'Moved Permanently'; break;
                    case 302: $text = 'Moved Temporarily'; break;
                    case 303: $text = 'See Other'; break;
                    case 304: $text = 'Not Modified'; break;
                    case 305: $text = 'Use Proxy'; break;
                    case 400: $text = 'Bad Request'; break;
                    case 401: $text = 'Unauthorized'; break;
                    case 402: $text = 'Payment Required'; break;
                    case 403: $text = 'Forbidden'; break;
                    case 404: $text = 'Not Found'; break;
                    case 405: $text = 'Method Not Allowed'; break;
                    case 406: $text = 'Not Acceptable'; break;
                    case 407: $text = 'Proxy Authentication Required'; break;
                    case 408: $text = 'Request Time-out'; break;
                    case 409: $text = 'Conflict'; break;
                    case 410: $text = 'Gone'; break;
                    case 411: $text = 'Length Required'; break;
                    case 412: $text = 'Precondition Failed'; break;
                    case 413: $text = 'Request Entity Too Large'; break;
                    case 414: $text = 'Request-URI Too Large'; break;
                    case 415: $text = 'Unsupported Media Type'; break;
                    case 500: $text = 'Internal Server Error'; break;
                    case 501: $text = 'Not Implemented'; break;
                    case 502: $text = 'Bad Gateway'; break;
                    case 503: $text = 'Service Unavailable'; break;
                    case 504: $text = 'Gateway Time-out'; break;
                    case 505: $text = 'HTTP Version not supported'; break;
                    default:
                        exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
                }

                $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

                header($protocol . ' ' . $code . ' ' . $text);

                $GLOBALS['http_response_code'] = $code;

            } else {

                $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

            }

            return $code;

        }
    }
function ajax_send_form()
{
    $output = array();
    // print_r($_POST['ajax-send-form']);
    // print_r(wp_verify_nonce( $_POST['ajax-send-form'], 'send_form' ));
    // echo wp_nonce_field( 'send_form', 'ajax-send-form' ); 
    if (!isset( $_POST['ajax-send-form'] ) || !wp_verify_nonce( $_POST['ajax-send-form'], 'send_form' )) {
        http_response_code(400);
        $output = array('error' => 'Sorry, your nonce did not verify.');
    } else {
        $output = deliver_mail();
        if (isset($output['error']) || !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
        } else {
            http_response_code(200);
        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($output);
    die();
}

add_action( 'wp_ajax_send_form', 'ajax_send_form' );
add_action( 'wp_ajax_nopriv_send_form', 'ajax_send_form' );
