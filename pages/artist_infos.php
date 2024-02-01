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
$artistId = $_GET['id'] ?? null;

try{
    $artistInfo = getArtistInfo ($token, $artistId); // Récupère les informations de l'artiste
    $artistAlbums = getArtistTopAlbums ($token, $artistId); // Récupère les albums de l'artiste
    $artistTopTracks = getArtistTopTracks ($token, $artistId); // Récupère les top tracks de l'artiste
}
catch(Exception $e){
    echo $e->getMessage();
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Informations sur l'artiste</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="../js/script.js"></script>
</head>
<body class="bg-gray-900 text-white" x-data="{ activeAlbum: null, rotate: false }">
    
    <div class="artist-info my-8 mx-auto max-w-4xl p-8 shadow-lg rounded-lg bg-gray-800" style="cursor: pointer;" onclick="window.open('<?= $artistInfo['external_urls']['spotify']; ?>', '_blank');">
        <div class="flex items-center space-x-4">
            <img class="w-32 h-32 rounded-full border-4 border-transparent transition duration-300 hover:border-green-600" src="<?= $artistInfo['images'][0]['url']; ?>" alt="<?= $artistInfo['name']; ?>">
            <div>
                <h1 class="text-4xl font-bold"><?= $artistInfo['name']; ?></h1>
                <p class="text-green-500">Genres: <?= implode(', ', $artistInfo['genres']); ?></p>
                <p>Followers: <?= number_format($artistInfo['followers']['total']); ?></p>
                <p>Popularité: <?= $artistInfo['popularity']; ?></p>
            </div>
        </div>
    </div>

    <!-- Albums Carousel -->
    <div class="artist-albums my-8 mx-auto max-w-6xl p-8">
        <h2 class="text-2xl font-bold mb-4">Albums</h2>
        <div class="flex justify-center items-end gap-4">
            <?php foreach ($artistAlbums['items'] as $index => $album): ?>
            <!-- Ensure index starts from 0 and use modulus for looping -->
            <div 
                class="album p-4 rounded-lg bg-gray-800 shadow-lg transform transition duration-500 ease-in-out"
                :class="{ 
                    'opacity-50 scale-90': activeAlbum !== <?= $index ?>, 
                    'opacity-100 scale-110': activeAlbum === <?= $index ?> 
                }"
                @mouseover="activeAlbum = <?= $index ?>; rotate = false"
                @mouseleave="activeAlbum = null; rotate = true"
                style="min-width: 20%;"
                onclick="window.open('<?= $album['external_urls']['spotify'] ?>', '_blank');"
                onmouseover="this.style.cursor='pointer';"
            >
                <img src="<?= $album['images'][0]['url'] ?>" alt="<?= $album['name'] ?>" class="w-full rounded-md mb-4">
                <p class="text-lg font-semibold"><?= $album['name'] ?></p>
                <p>Date de sortie: <?= $album['release_date'] ?></p>
                <p>Nombre de titres: <?= $album['total_tracks'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>


    <!-- Top Tracks -->
    <div class="artist-top-tracks my-8 mx-auto max-w-6xl p-8">
        <h2 class="text-2xl font-bold mb-4">Top Tracks</h2>
        <div class="flex justify-between">
            <?php foreach (array_slice($artistTopTracks['tracks'], 0, 3) as $index => $track): ?>
                <div class="track p-4 rounded-lg bg-gray-800 shadow-lg" style="width: calc(100% / 3);">
                    <p class="text-lg font-bold"><?= $track['name']; ?></p>
                    <p><strong>Popularité:</strong> <?= $track['popularity']; ?></p>
                    <p><strong>Date de sortie:</strong> <?= $track['album']['release_date']; ?></p>
                    <button class="bg-green-500 text-white px-4 py-2 rounded mt-4" onclick="toggleIframe('iframe<?= $index ?>')">
                        <img src="../src/img/play.png" alt="Play" width="20" height="20">
                    </button>
                </div>
                <div class="w-4"></div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php foreach ($artistTopTracks['tracks'] as $index => $track): ?>
    <!-- Autres éléments de track -->
    <div id="iframe<?= $index ?>" style="position: fixed; bottom: 0; left: 0; width: 100%; display: none;">
        <iframe src="https://open.spotify.com/embed/track/<?= $track['id']; ?>" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
    </div>
    <?php endforeach; ?>


    <?php include './pages/icons.php';?>

</body>
</html>
        


