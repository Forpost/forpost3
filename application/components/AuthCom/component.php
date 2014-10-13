<?php

/* Code of component AuthCom goes here. */

if (Session::get('user.auth')) {
    $properties = Session::get('user.properties');
    echo "Вы авторизованы как " . $properties['name'] . ' ' . $properties['surname'] . " (<a href='/login/logout'>Выйти</a>)";
} else {
    echo "Вы не авторизованы (<a href='/login'>Войти</a>)";
}
