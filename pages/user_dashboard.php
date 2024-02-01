<?php
session_start();

$userIsConnected = false;

if(isset($_SESSION['user_access_token'])){
    $userToken = $_SESSION['user_access_token'];
    if (isUserTokenExpired($userToken)){
        $userToken = refreshUserToken($_SESSION['user_refresh_token'], CLIENT_ID, CLIENT_SECRET, TOKEN_URL);
        $_SESSION['user_access_token'] = $userToken;
        $userIsConnected = true;
    }
    $userIsConnected = true;
}else if(isset($_SESSION['general_access_token'])){
    if (isGeneralTokenExpired()){
        getGeneralAccessToken(CLIENT_ID,CLIENT_SECRET,TOKEN_URL);
    }
}else{
    getGeneralAccessToken(CLIENT_ID,CLIENT_SECRET,TOKEN_URL);
}


$token = $userIsConnected ? $_SESSION['user_access_token'] : $_SESSION['general_access_token'];
    


?>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dorchify - Accueil</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
        <script src="../js/script.js"></script>
    </head>
    <body class="bg-gray-900 text-white">
        <?php if ($userIsConnected == true): ?> <!-- Si l'utilisateur est connecté, on affiche les informations de l'utilisateur -->
            <?php 
            $userInfo = getUserInfo($token);
            $topArtists = getTopArtists($token);
            $topTracks = getTopTracks($token);
            ?>
            <?php include './pages/user_infos.php'; ?>
            <div class="flex items-start bg-gray-900 py-8 space-x-8">
                <?php include './pages/top_artists.php'; ?>
                <?php include './pages/top_tracks.php'; ?>
            </div>
        <?php else: // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
            Flight::redirect('/'); // Redirection vers la page d'accueil
        endif; ?>

    <?php include './pages/icons.php';?>

    </body>
</html>