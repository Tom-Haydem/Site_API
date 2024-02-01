<?php

// Définition des routes
Flight::route('/', function(){
    include './pages/home_login.php';
});

Flight::route('/api/callback/', function(){
    include './api/callback.php';
});


Flight::route('/user/', function(){
    include './pages/user_dashboard.php';
});

Flight::route('/artist/@name', function($name){
    $id = Flight::request()->query['id'];
    include './pages/artist_infos.php';
});

Flight::route('/log', function(){
    include './pages/logs_infos.php';
});