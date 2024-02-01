<?php
// Ici, on suppose que $db est votre objet PDO déjà connecté à la base de données
// et que la connexion est stockée dans une variable globale ou obtenue d'une manière ou d'une autre.

// La requête pour obtenir tous les logs
$stmt = DB->query("SELECT date, request, response FROM spotifyapi");

// On récupère tous les résultats
$logsData = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Cette fonction affiche les données imbriquées de façon récursive
function displayResponseData($data, $depth = 0) {
    $colorsDepth = [
        0 => 'text-green-500',
        1 => 'text-blue-500',
        2 => 'text-yellow-500',
        3 => 'text-purple-500',
        4 => 'text-red-500',
        5 => 'text-pink-500',
        6 => 'text-indigo-500',
        7 => 'text-green-500',
        8 => 'text-blue-500',
        9 => 'text-yellow-500',
        10 => 'text-purple-500',
        11 => 'text-red-500',
        12 => 'text-pink-500',
        13 => 'text-indigo-500',
        14 => 'text-green-500',
        15 => 'text-blue-500',
        16 => 'text-yellow-500',
        17 => 'text-purple-500',
        18 => 'text-red-500',
        19 => 'text-pink-500',
        20 => 'text-indigo-500',
    ];
    
    if ($data === null) {
        echo 'null';
    } elseif (!is_array($data)) {
        echo '<span class="' . ($colorsDepth[$depth]) .'">' . htmlspecialchars($data, ENT_QUOTES) . '</span>';
    } else {
        echo '<div class="' . ($depth > 0 ? 'ml-' . ($depth * 4) : '') . '">';
        foreach ($data as $key => $value) {
            echo '<div class="flex flex-col">';
            echo '<span class="hover:text-green-500 cursor-pointer" onclick="toggleVisibility(event)">' . htmlspecialchars($key, ENT_QUOTES) . '<span class="arrow">▷</span> </span>';
            echo '<div class="hidden text-sm">';
            displayResponseData($value, $depth + 1);
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
}


function isJson($string) {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
 }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Journal des échanges API Spotify</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-lg">
    <div class="container mx-auto text-center my-8">
        <h1 class="text-xl text-green-500 font-bold mb-6">Journal des échanges avec l'API Spotify</h1>

        <!-- Sélecteur de date -->
        <div class="my-4">
            <label for="date-input" class="text-white">Choisir une date :</label>
            <input type="date" id="date-input" name="date-input" class="px-2 py-1 rounded-md">
        </div>

        <div id="api-log-table-container" class="overflow-x-auto">
            <table id="logs-table" class="w-full text-gray-200">
                <thead class="text-xs uppercase bg-green-900 text-green-300 sticky top-0 z-10">
                    <tr>
                        <th class="w-1/3 py-3 px-6 truncate">Date</th>
                        <th class="w-1/3 py-3 px-6 truncate">Requête</th>
                        <th class="w-1/3 py-3 px-6 truncate">Réponse</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 bg-opacity-90 divide-y divide-gray-700">
                    <?php foreach ($logsData as $log): ?>
                        <tr>
                            <td class="truncate px-4 py-2 w-1/3 cursor-pointer hover:text-green-500 text-left" onclick="toggleText(this, '<?php echo htmlspecialchars($log['date'], ENT_QUOTES); ?>')">
                                <?php echo htmlspecialchars($log['date'], ENT_QUOTES); ?>
                            </td>
                            <td class="truncate px-4 py-2 w-1/3 cursor-pointer hover:text-green-500 text-left" onclick="toggleText(this, '<?php echo htmlspecialchars($log['request'], ENT_QUOTES); ?>')">
                                <?php echo htmlspecialchars(substr($log['request'], 0, 30) . '...', ENT_QUOTES); ?>
                            </td>
                            <td class="px-4 py-2 w-1/3 border-b border-gray-700 text-left">
                                <?php 
                                    $responseData = json_decode($log['response'], true);
                                    if ($responseData === null && json_last_error() !== JSON_ERROR_NONE) {
                                        echo htmlspecialchars($log['response'], ENT_QUOTES); // Affiche la réponse telle quelle si elle n'est pas au format JSON
                                    } else {
                                        displayResponseData($responseData); // Affiche la réponse sous forme de tableau HTML
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include './pages/icons.php';?>
</body>
<script src="../js/logs.js"></script>
</html>
