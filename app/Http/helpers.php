<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\User;

function testMail()
{
    $message = file_get_contents(view('emails/template'));
    return $message;
}
function phpmailer_donate($data = []){
    $mail   = new PHPMailer();
    $mymail = 'no-reply@pw-orion.co.id';

    $url = url('/donate_sukses.html');
    // return file_get_contents($url);
    // return $url;
    $client = new \GuzzleHttp\Client();
    $response = $client->get($url);
    $response->getStatusCode(); # 200
    $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'

    $a = (string) $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'

    $mail->isSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $mail->Host       = 'smtp.gmail.com';
    $mail->Username   = env('SMTP_USER');
    $mail->Password   = env('SMTP_PW');

    //Recipients
    $mail->setFrom($mymail, 'Admin');
    $mail->addAddress($data->user->email, $data->user->truename);
    $mail->addReplyTo($mymail, 'Admin');
    $mail->isHTML(true);
    $mail->Subject = 'Donate Success';

    $user = User::find($data->user->ID);
    $a = str_replace("%nama%", $user->truename, $a);
    $a = str_replace("%curr%", settings('currency_name'), $a);
    $a = str_replace("%amount%", (round($data->amount / settings('form_per'))), $a);
    $a = str_replace("%total%", $user->money, $a);
    $mail->Body    = $a;
    
    // return $a;
    $mail->send();
    // if(!$mail->send()){
    //     return back()->with('msg', 'Link could not be sent');
    // }else{
    //     return back()->with('msg', 'Link has been sent! Please check your email for reset link');
    // }
}
function phpmailer($data = []){
    $mail   = new PHPMailer();
    $mymail = 'no-reply@pw-orion.co.id';

    $mail->isSMTP();
    $mail->Mailer = "smtp";
    $mail->SMTPDebug  = 0;
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;
    $mail->Host       = 'smtp.gmail.com';
    $mail->Username   = env('SMTP_USER');
    $mail->Password   = env('SMTP_PW');

    //Recipients
    $mail->setFrom($mymail, 'Admin');
    $mail->addAddress($data['penerima'], $data['nama_penerima']);
    $mail->addReplyTo($mymail, 'Admin');
    $mail->isHTML(true);
    $mail->Subject = $data['subjek'];
    // $mail->Body    = $data['pesan'];

    $url = url('auth/mailview');
    $client = new \GuzzleHttp\Client();
    $response = $client->get($url);
    $response->getStatusCode(); # 200
    $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
    $a = (string) $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'
    $a = str_replace("%nama%", $data['nama_penerima'], $a);
    $a = str_replace("%token%", base64_encode($data['penerima']), $a);
    $mail->Body    = $a;

    if(!$mail->send()){
        return back()->with('msg', 'Link could not be sent');
    }else{
        return back()->with('msg', 'Link has been sent! Please check your email for reset link');
    }
}

function makeTime( $time )
{
    $days = floor( $time / 86400 );
    $days = $days > 0 ? ( $days > 1 ) ? $days . " " . trans( 'ranking.time.days' ) : $days . " " . trans( 'ranking.time.day' ) : '';

    $hours = ceil( ceil( $time - ( (int)$days * 86400 ) ) / 3600 );
    $hours = $hours > 1 ? ( $hours > 1 ) ? $hours . " " . trans( 'ranking.time.hours' ) : $hours . " ". trans( 'ranking.time.hour' ) : ' > 1 ' . trans( 'ranking.time.hour' );

    $time = $days . ' ' . $hours;

    return $time;
}

/**
 * Set the active class to the current opened menu.
 *
 * @param  string|array $route
 * @param  string $className
 * @return string
 */
function isActive( $route, $className = 'active' )
{
    if ( is_array( $route ) ) {
        return in_array(Route::currentRouteName(), $route ) ? $className : '';
    }
    if ( Route::currentRouteName() == $route ) {
        return $className;
    }
    if ( strpos( URL::current(), $route ) ) return $className;
}

function isDone( $step )
{
    $steps = steps();
    $step = array_search( $step, $steps );
    $current_step = array_search( Route::currentRouteName(), $steps );
    return ( $current_step > $step ) ? 'done' : NULL;
}

function setupTasks()
{
    $jobs = shell_exec( 'crontab -l' );

    foreach ( preg_split( "/((\r?\n)|(\r\n?))/", $jobs ) as $line )
    {
        if ( !str_contains( $line, ['php', 'artisan'] ) )
        {
            return TRUE;
        }
    }
}

function steps()
{
    $steps = [];
    foreach ( Route::getRoutes() as $route )
    {
        $route_name = $route->getName();
        if ( str_contains( $route_name, 'admin.installer' ) )
        {
            $steps[] = $route_name;
        }
    }
    return $steps;
}