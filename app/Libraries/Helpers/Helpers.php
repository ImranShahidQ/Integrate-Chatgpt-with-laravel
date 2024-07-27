<?php

use Illuminate\Support\Str;

function print_data($data, $isDie = true)
{
    echo '<pre>';
    if (is_object($data)) {
        print_r($data->toArray());
    } else {
        print_r($data);
    }
    if ($isDie) {
        die;
    }
}

function pd($data, $isDie = true)
{
    echo '<pre>';
    if (is_object($data)) {
        print_r($data->toArray());
    } else {
        print_r($data);
    }
    if ($isDie) {
        die;
    }
}

function customSort($arr1, $a, $b)
{
    $index_a = array_search($a, $arr1);
    $index_b = array_search($b, $arr1);
    return $index_a - $index_b;
}

function status_arr()
{
    $status_arr = [
        COMMON_STATUS_YES => get_status_by_key(COMMON_STATUS_YES),
        COMMON_STATUS_NO => get_status_by_key(COMMON_STATUS_NO),
    ];
    return $status_arr;
}

function get_status_by_key($key)
{
    $txt = '';
    if ($key == COMMON_STATUS_YES) {
        $txt = 'Active';
    } elseif ($key == COMMON_STATUS_NO) {
        $txt = 'De-Active';
    }
    return $txt;
}

function common_status_arr()
{
    $status_arr = [
        COMMON_STATUS_YES => get_status_by_key(COMMON_STATUS_YES),
        COMMON_STATUS_NO => get_status_by_key(COMMON_STATUS_NO),
    ];
    return $status_arr;
}

function get_common_status_by_key($key)
{
    $txt = '';
    if ($key == COMMON_STATUS_YES) {
        $txt = 'Yes';
    } elseif ($key == COMMON_STATUS_NO) {
        $txt = 'No';
    }
    return $txt;
}

function str_uuid()
{
    return Str::uuid()->toString();
}

function string_encode_prefix($id, $preFix = '', $zeros = 3)
{
    return $preFix . str_pad($id, $zeros, '0', STR_PAD_LEFT);
}

function string_decode_prefix($id)
{
    return preg_replace("/[^0-9]/", '', $id);
}

function time_elapsed_string_fn($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function check_days_since($date)
{
    $current = time(); // or your date as well
    $newdate = strtotime(date('Y-m-d', strtotime($date)));
    $datediff = $current - $newdate;
    return round($datediff / (60 * 60 * 24));
}

function number_format_fn($number)
{
    return number_format($number);
}

function remove_comma_fn($strNumb)
{
    if (!empty($strNumb)) {
        if (is_array($strNumb)) {
            $retStr = [];
            foreach ($strNumb as $key => $st) {
                if (!empty($st)) {
                    $retStr[$key] = (str_replace(",", "", $st) * 1);
                } else {
                    $retStr[$key] = $st;
                }
            }
        } else {
            $retStr = (str_replace(",", "", $strNumb) * 1);
        }
    } else {
        $retStr = $strNumb;
    }
    return $retStr;
}

function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function cURL_method_title($key = REQUEST_METHOD_GET)
{
    $txt = 'GET';
    if ($key == REQUEST_METHOD_POST) {
        $txt = 'POST';
    } elseif ($key == REQUEST_METHOD_PUT) {
        $txt = 'PUT';
    } elseif ($key == REQUEST_METHOD_PATCH) {
        $txt = 'PATCH';
    } elseif ($key == REQUEST_METHOD_DELETE) {
        $txt = 'DELETE';
    }
    return $txt;
}

function cURL_get_call($url, $header = array())
{
    if (empty($header)) {
        $header = array(
            'Content-Type: application/json'
        );
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    //curl_errno($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        Log::info('cURL_get_call');
        Log::info($err);
        return false;
    } else {
        //Log::info($response);
//        return true;
        $response = json_decode($response, true);
        return $response;
    }
}

function cURL_post_call($url, $postfields = array(), $headers = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    if (count($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    $response = curl_exec($ch);

    //curl_errno($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        Log::info('cURL_post_call');
        Log::info($err);
        //return $err;
        return false;
    } else {
        //Log::info($response);
//        return true;
        $response = json_decode($response, true);
        return $response;
    }
}

function cURL_post_call2($url, $postfields = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
    $response = curl_exec($ch);

    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        Log::info('cURL_post_call2');
        Log::info($err);
        //return $err;
        return false;
    } else {
        //Log::info($response);
//        return true;
        $response = json_decode($response, true);
        return $response;
    }
}

function randomTimeForLink()
{
    return '?' . time();
}

function limited_characters_string($str, $end = 50, $start = 0)
{
    return mb_substr($str, $start, $end);
}

function get_browser_name($user_agent)
{
    $t = strtolower($user_agent);
    $t = " " . $t;

    $bName = "Unkown";
    if (strpos($t, 'opera') || strpos($t, 'opr/'))
        $bName = 'Opera';
    elseif (strpos($t, 'edge'))
        $bName = 'Edge';
    elseif (strpos($t, 'chrome'))
        $bName = 'Chrome';
    elseif (strpos($t, 'safari'))
        $bName = 'Safari';
    elseif (strpos($t, 'firefox'))
        $bName = 'Firefox';
    elseif (strpos($t, 'msie') || strpos($t, 'trident/7'))
        $bName = 'Internet Explorer';
    return $bName;
}

function trime_fn($str, $trm = " ")
{
    return trim($str, $trm);
}

function generate_random_token()
{
    $n = 50;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}