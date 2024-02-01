<?php
    // S'il existe des artistes favoris
    if($topArtists):?>
            <div class="flex flex-col w-1/4 h-screen overflow-y-auto rounded-tl-lg rounded-bl-lg">
                <div class="overflow-y-auto rounded-lg shadow-lg max-h-full">
                    
                    <!-- Titre de la section -->
                    <h1 class="text-2xl text-white font-bold p-4 text-center">TOP 20 des artistes les plus écoutés par <?= htmlspecialchars($userInfo['display_name']) ?></h1>

                    <!-- Barre de recherche -->
                    <div class="sticky top-0 z-10 bg-green-900 p-4">
                        <input type="text" id="searchInput" onkeyup="searchTable(this, document.getElementById('tableTopArtist'),1)" placeholder="Rechercher des artistes..." class="p-2 bg-gray-800 text-white w-full rounded">
                    </div>

                    <!-- Tableau des artistes -->
                    <table class="w-full text-gray-200">
                        <!-- En-tête du tableau -->
                        <thead class="text-xs uppercase bg-green-900 text-green-300 sticky top-20 z-10"> 
                            <tr>
                                <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 0)">
                                    #
                                    <span id="icon-artist_rank">▽</span>
                                </th>

                                <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 1)">
                                    Artiste
                                    <span id="icon-artist_display_name">▽</span>
                                </th>

                                <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 2)">
                                    Popularité
                                    <span id="icon-artist_popularity">▽</span>
                                </th>
                            </tr>
                        </thead>

                        <!-- Corps du tableau -->
                        <tbody id="tableTopArtist" class="bg-gray-800 bg-opacity-90 divide-y divide-gray-700">
                            <?php 
                            $artist_rank = 0;
                            foreach ($topArtists['items'] as $artist): // Pour chaque artiste dans le tableau des artistes
                                $artist_rank++; // On incrémente le rang de l'artiste
                                $artist_id = $artist['id']; // On récupère l'id de l'artiste
                                $artist_name = $artist['name']; // On récupère le nom de l'artiste
                                $artist_image = $artist['images'][0]['url']; // On récupère l'image de l'artiste
                                $artist_url = $artist['external_urls']['spotify']; // On récupère l'url de l'artiste
                                $artist_popularity = $artist['popularity']; // On récupère la popularité de l'artiste

                                $url_artist_website = '/artist/' . urlencode($artist_name) . '?id=' . urlencode($artist_id); // On crée l'url de l'artiste pour pouvoir y accéder.
                            ?>
                            <tr class="bg-gray-900 bg-opacity-75 hover:bg-green-700 hover:bg-opacity-50 cursor-pointer" onclick="window.open('<?= htmlspecialchars($url_artist_website) ?>', '_blank')">
                                
                                <td class="py-4 px-6">
                                    <?= $artist_rank ?>
                                </td>
                                
                                <td class="py-4 px-6 flex items-center">
                                    <img class="h-10 w-10 rounded-full mr-3" src="<?= $artist_image ?>" alt="<?= $artist_name ?>">
                                    <?= $artist_name ?>
                                </td>

                                <td class="py-4 px-6">
                                    <?= $artist_popularity ?>
                                </td>
                                
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    <?php endif; 
?>
