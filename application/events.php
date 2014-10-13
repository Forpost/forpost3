<?php

Event::listen('app.on_front_loading',function ($method) {
    if ($method <> 'CLI' && Config::get('app.redirect2main') && Input::SERVER('HTTP_HOST') <> Config::get('app.main_domain')) {
        Lib::redirect('http://'.Config::get('app.main_domain'));
    }
});

Event::listen('app.on_web_controller_loading', function () {
    if (!User::isAuth()) {
        User::authByCookie(Input::COOKIE('FORPOST_USER'),Input::COOKIE('FORPOST_HASH'));
    }
});

Event::listen('sys.on_method_not_allowed', function ($method) {
    Lib::varDump($method);
    die();
});
/*
Event::listen('app.on_starting', function () {
    Lib::varDump(Input::SERVER());
    die();
});
*/
//Event::listen('app.on_404_not_found',function () { Lib::redirect('/404');});
/*
Event::listen('app.on_controller_found',function ($controller_class,$controller_method) {

    if ($controller_class !='Controller_login' && !Session::get('user.auth')) {
        //$controller_class=='Controller_news'
       // Lib::redirect('/login?back_url='.urlencode(Input::SERVER('HTTP_REFERER')));
        Lib::redirect('/login');
    }

});*/
/*
Event::listen('app.on_controller_not_found',function () { Lib::redirect('/404');});

//Event::listen('app.on_model_not_found',function () { Lib::redirect('/404');});
/*
Event::listen('app.on_controller_not_found',function ($controller_class,$controller__method) {

    //$transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -t');

// Mail
$transport = Swift_MailTransport::newInstance();

// Create the Mailer using your created Transport
    $mailer = Swift_Mailer::newInstance($transport);

// Create a message
    $body="Ошибка: запрошенный контроллер не найден".
          "<br>Контроллер: <b>$controller_class</b>".
          "<br>Действие: <b>$controller__method</b>";

    $message = Swift_Message::newInstance('Forpost3 :: Произошла ошибка')
        ->setFrom(array('work@vlane.ru' => 'Дмитрий С. Юрьев'))
        ->setTo(array('coolkid00@gmail.com', 'd.yuriev@saratov.ru' => 'Дмитрий Юрьев'))
        ->setBody($body)->setContentType('text/html');
    ;

// Send the message
    $result = $mailer->send($message);
    ;},5);
*/
