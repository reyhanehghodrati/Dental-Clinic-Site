<?php

$request = parse_url($_SERVER['REQUEST_URI']);
//require_once 'controller/Form_controllers.php';
//require_once 'model/Contact.php';



switch ($request['path']) {
//    case '':
//    case '/':
//        require __DIR__ .  'home.php';
//        break;

    case '/projee4/html/reserv':
        require(__DIR__ . '/html/reserv.php');
        break;

    default:
        http_response_code(404);
        require __DIR__ .  '/html/404.php';
}

//Route::get('/view/login',function(){
//    return "test tenis" ;
//});



