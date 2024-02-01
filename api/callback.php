<?php


session_start();

$code = $_GET['code'] ?? null;

    if ($code) {
        $code = $_GET['code'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, TOKEN_URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => REDIRECT_URI
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Basic '.base64_encode(CLIENT_ID.':'.CLIENT_SECRET)
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            $tokens = json_decode($response, true);
            if (isset($tokens['access_token'])) {
                $_SESSION['user_access_token'] = $tokens['access_token']; // Permet de récupérer un nouveau token d'accès pour l'utilisateur (SCOPED)
                $_SESSION['user_refresh_token'] = $tokens['refresh_token']; // Permet de récupérer un token de rafraîchissement pour obtenir un nouveau token d'accès pour l'utilisateur (SCOPED)
                $_SESSION['user_expires_in'] = $tokens['expires_in']; // Permet de savoir combien de temps le token est valide pour l'utilisateur (SCOPED)
                $_SESSION['user_expires_at'] = time() + $tokens['expires_in']; // Permet de savoir quand le token expire pour l'utilisateur (SCOPED)
            }else{
                echo "Erreur lors de la récupération du token d'accès";
            }
            logApiCall(TOKEN_URL, $tokens);
            Flight::redirect('/user/');
            exit;
        } else {
            // Gérer l'erreur, par exemple rediriger vers une page d'erreur
        }
    } else {
        Flight::redirect('/');
        exit;
    }
?>
