<?php

include './config.php';


/**
 * Récupère un token d'accès
 * @param string $clientId
 * @param string $clientSecret
 * @param string $tokenURL
 */
function getGeneralAccessToken($clientId, $clientSecret, $tokenURL) {
    $ch = curl_init($tokenURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . base64_encode("$clientId:$clientSecret")));

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $tokens = json_decode($response, true);
        if (isset($tokens['access_token'])) {
            $_SESSION['general_access_token'] = $tokens['access_token'];
            $_SESSION['general_expires_in'] = $tokens['expires_in'];
            $_SESSION['general_expires_at'] = time() + $tokens['expires_in'];
        }else{
            echo "Erreur lors de la récupération du token d'accès";
        }
        logApiCall($tokenURL, $tokens);
    }else{
        echo "Erreur lors de la récupération du token d'accès";
    }
}


/**
 * La méthode refreshUserToken() permet de récupérer un nouveau token d'accès pour l'utilisateur
 * @param string $refreshToken
 * @param string $clientId
 * @param string $clientSecret
 * @param string $tokenURL
 * @return string|null
 */
function refreshUserToken($refreshToken, $clientId, $clientSecret, $tokenURL) {
    $ch = curl_init($tokenURL);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'refresh_token',
        'refresh_token' => $refreshToken
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode("$clientId:$clientSecret")
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $tokens = json_decode($response, true);
        if (isset($tokens['access_token'])) {
            $_SESSION['user_access_token'] = $tokens['access_token'];
            $_SESSION['user_expires_in'] = $tokens['expires_in'];
            $_SESSION['user_expires_at'] = time() + $tokens['expires_in'];
        }
        logApiCall($tokenURL, $tokens);
    }

}

/**
 * La méthode isUserTokenExpired() permet de savoir si le token d'accès de l'utilisateur est expiré
 */
function isUserTokenExpired() {
    return isset($_SESSION['user_expires_at']) && time() > $_SESSION['user_expires_at'];
}

/**
 * La méthode isGeneralTokenExpired() permet de savoir si le token d'accès général est expiré
 */
function isGeneralTokenExpired() {
    return isset($_SESSION['general_expires_at']) && time() > $_SESSION['general_expires_at'];
}

/**
 * Récupère les informations de l'utilisateur
 * @param string $accessToken
 * @return array|null
 */
function getUserInfo($accessToken) {
    $url = "https://api.spotify.com/v1/me";
    $headers = array("Authorization: Bearer {$accessToken}");
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }
    
    return null;
}


/**
 * Récupère les artistes les plus écoutés par l'utilisateur (20 artistes)
 * @param string $accessToken
 * @return array|null
 */
function getTopArtists($accessToken) {
    $url = "https://api.spotify.com/v1/me/top/artists?time_range=short_term&limit=20"; // Limite à 20 artistes
    $headers = array("Authorization: Bearer {$accessToken}");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }

    return null;
}


/**
 * Récupère les artistes les plus écoutés par l'utilisateur (20 tracks)
 * @param string $accessToken
 * @return array|null
 */
function getTopTracks($accessToken) {
    $url = "https://api.spotify.com/v1/me/top/tracks?time_range=short_term&limit=20"; // Limite à 20 tracks
    $headers = array("Authorization: Bearer {$accessToken}");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }

    return null;
}

/**
 * La méthode getArtistInfo() permet de récupérer les informations d'un artiste
 * @param string $accessToken
 * @param string $id_artist
 */
function getArtistInfo($accessToken, $id_artist){
    $url = "https://api.spotify.com/v1/artists/$id_artist";
    $headers = array("Authorization: Bearer {$accessToken}");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }

    return null;
}

/**
 * La méthode getArtistTopAlbums() permet de récupérer les albums les plus écoutés d'un artiste
 * @param string $accessToken
 * @param string $id_artist
 */

 function getArtistTopAlbums($accessToken, $id_artist){
    $url = "https://api.spotify.com/v1/artists/$id_artist/albums?limit=3&market=FR"; // Limite à 5 albums et marché français
    $headers = array("Authorization: Bearer {$accessToken}");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }

    return null;
}

/**
 * La méthode getArtistTopTracks() permet de récupérer les top tracks d'un artiste
 * @param string $accessToken
 * @param string $id_artist
 */
 function getArtistTopTracks($accessToken, $id_artist){
    $url = "https://api.spotify.com/v1/artists/$id_artist/top-tracks?market=FR"; // Marché français
    $headers = array("Authorization: Bearer {$accessToken}");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }

    return null;
}


/**
 * La méthode getNewReleases() permet de récupérer les dernières sorties
 * @param string $accessToken
 * @return array|null
 */
function getNewReleases($accessToken) {
    $url = "https://api.spotify.com/v1/browse/new-releases?country=FR&limit=50";
    $headers = array("Authorization: Bearer {$accessToken}");
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }
    
    return null;
}

/**
 * La méthode getArtists() permet de récupérer les informations de plusieurs artistes
 * @param string $accessToken
 * @param string $artists_id (séparés par des virgules)
 * @return array|null
 */
function getArtists($accessToken, $artists_id){
    $url = "https://api.spotify.com/v1/artists?ids=$artists_id";
    $headers = array("Authorization: Bearer {$accessToken}");
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);

    curl_close($ch);
    
    if ($response) {
        $formated_response = json_decode($response, true);
        logApiCall($url, $formated_response);
        return $formated_response;
    }
    
    return null;
}


/**
 * La méthode logApiCall() permet de sauvegarder les appels à l'API dans un fichier JSON
 * @param string $request - URL de la requête
 * @param string $response - Réponse de l'API
 */
function logApiCall($request, $response) {

    $date = date('Y-m-d H:i:s'); // Format de la date: AAAA-MM-JJ HH:MM:SS
    $requestJson = json_encode($request, JSON_PRETTY_PRINT); // Formatage de la requête en JSON
    $responseJson = json_encode($response, JSON_PRETTY_PRINT); // Formatage de la réponse en JSON
    
    try{
    
        // Préparation de la requête avec des placeholders
        $stmt = DB->prepare("INSERT INTO spotifyapi (date, request, response) VALUES (:date, :request, :response)");
    
        // Liaison des valeurs avec les placeholders
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':request', $requestJson);
        $stmt->bindParam(':response', $responseJson);
    
        // Exécution de la requête préparée
        $stmt->execute();
    }catch(PDOException $e){
        error_log($e->getMessage());
    }
}


?>
