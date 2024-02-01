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



$new_releases = getNewReleases($token);
$array_artist = [];
$ids = [];
foreach ($new_releases['albums']['items'] as $index => $new_release){
    $artist = $new_release['artists'][0];
    $artist_id = $artist['id'];
    $artist_name = $artist['name'];
    $url_artist = '/artist/' . urlencode($artist_name) . '?id=' . urlencode($artist_id);

    $album_name = $new_release['name'];
    $album_image = $new_release['images'][0]['url'];
    $album_url = $new_release['external_urls']['spotify'];

    $array_artist[$index] = [
        'artist_name' => $artist_name,
        'artist_id' => $artist_id,
        'artist_image' => "",
        'url_artist' => $url_artist,
        'album_name' => $album_name,
        'album_image' => $album_image,
        'album_url' => $album_url
    ];

    $ids[] = $artist_id;
}

// Récupérer les images des artistes
$artists = getArtists($token, implode(',', $ids));

foreach ($artists['artists'] as $index => $artist){
    $array_artist[$index]['artist_image'] = $artist['images'][0]['url'];
}

shuffle($array_artist);



?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dorchify - Accueil</title>
        <!-- <link rel="stylesheet" href="./css/sytle2.css"> --> 
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="./js/script.js"></script>
    </head>
    
    <body class="bg-gray-900 text-white">

        <!-- Nouvelles sorties -->
        <div class="fixed top-0 left-0 w-1/3 h-screen overflow-y-auto py-10 bg-gray-800 rounded-lg" x-data="{ activeRelease: null }">
            
            <h2 class="text-xl font-bold text-center text-white mb-6">Nouvelles Sorties en France</h2>

            <div class="flex flex-col items-center space-y-4">
                <?php foreach ($array_artist as $index => $artist):
                    $image_url = $artist['album_image'];
                    $artist_name = $artist['artist_name'];
                    $artist_id = $artist['artist_id'];
                    $url_artist = $artist['url_artist'];
                    $url_album = $artist['album_url'];
                    $album_name = $artist['album_name'];
                ?>
                    <a href="<?= $url_album ?>" target="_blank" class="group">
                        <img 
                            src="<?= $image_url ?>" 
                            alt="<?= $album_name ?>" 
                            class="w-32 h-32 object-cover rounded-full border-4 border-transparent transition transform group-hover:scale-110 group-hover:border-green-500"
                        >
                    </a>
                    <div class="flex flex-col items-center"> <!-- Ajout de la classe CSS pour aligner le texte -->
                        <p class="font-bold text-white"><?= $album_name ?></p>
                        <a href="<?= $url_artist ?>" class="hover:text-green-500 hover:underline">
                            <?= $artist_name ?>
                        </a>
                    </div>
                    
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        if ($userIsConnected == false):?>
            <div class="flex items-center justify-center h-screen bg-gray-900">
                <a href="<?= htmlspecialchars(AUTH_URL) ?>" class="px-10 py-4 bg-green-500 text-white text-xl font-bold rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-700 focus:ring-opacity-50 transition duration-300" style="font-family: 'Circular Std', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                    Se connecter pour voir ses statistiques
                </a>
            </div>
        <?php else:?>
            <div class="flex items-center justify-center h-screen bg-gray-900">
                <a href="/user" class="px-10 py-4 bg-green-500 text-white text-xl font-bold rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-700 focus:ring-opacity-50 transition duration-300" style="font-family: 'Circular Std', 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                    Accéder à votre dashboard
                </a>
            </div>
        <?php endif;?>

        <!-- Section des Artistes Tendances -->
        <div class="fixed top-0 right-0 w-1/3 h-screen overflow-y-auto py-10 bg-gray-700 rounded-lg" x-data="{ activeArtist: null }">
            <h2 class="text-xl font-bold text-center text-white mb-6">Artistes Tendances</h2>

            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($array_artist as $index => $artist):
                    $artist_name = $artist['artist_name'];
                    $artist_image = $artist['artist_image'];
                    $url_artist = $artist['url_artist'];
                    $album_name = $artist['album_name'];
                    $album_image = $artist['album_image'];
                    $album_url = $artist['album_url'];
                ?>
                    <div class="flex flex-col items-center" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                        <a href="<?= $url_artist ?>" target="_blank" class="group">
                            <img 
                                src="<?= $artist_image ?>" 
                                alt="<?= $artist_name ?>" 
                                class="w-32 h-32 object-cover rounded-full border-4 border-transparent transition transform group-hover:scale-110 group-hover:border-green-500"
                            >
                        </a>
                        <p :class="{ 'text-white': hover, 'text-gray-400': !hover }"><?= $artist_name ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>    
    </body>

</html>
