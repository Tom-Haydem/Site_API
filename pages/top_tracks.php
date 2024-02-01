<div class="flex flex-col w-2/4 h-screen"> <!-- Assurez-vous que ceci est cohérent avec le conteneur précédent -->
    <div class="overflow-y-auto rounded-lg shadow-lg max-h-full"> <!-- Utiliser max-h-full ici aussi -->
        <div class="overflow-y-auto rounded-lg shadow-lg" style="height: calc(10 * 4rem);"> <!-- Hauteur calculée en fonction de la taille d'une ligne -->

            <!-- Titre de la section -->
            <h1 class="text-2xl text-white font-bold p-4 text-center">TOP 20 des sons les plus écoutés par <?= htmlspecialchars($userInfo['display_name']) ?></h1>

            <!-- Barre de recherche -->
            <div class="sticky top-0 z-10 bg-green-900 p-4">
                <input type="text" id="searchInputTracks" onkeyup="searchTable(this, document.getElementById('tableBodyTracks'),1)" placeholder="Rechercher un titre..." class="p-2 bg-gray-800 text-white w-full rounded">
            </div>

            <!-- Tableau des sons -->
            <table class="w-full text-gray-200">
                <!-- En-tête du tableau -->
                <thead class="text-xs uppercase bg-green-900 text-green-300 sticky top-20 z-10">
                    <tr>
                        <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 0)">
                            #
                            <span id="icon-track_rank">▽</span>
                        </th>

                        <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 1)">
                            Titre
                            <span id="icon-track_name">▽</span>
                        </th>

                        <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 2)">
                            Artiste
                            <span id="icon-track_artist">▽</span>
                        </th>

                        <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 3)">
                            Album/Single
                            <span id="icon-track_album_type">▽</span>
                        </th>

                        <th scope="col" class="py-3 px-6 cursor-pointer" onclick="sortTable(this.parentNode.parentNode.parentNode, 4)">
                            Date de sortie
                            <span id="icon-track_release_date">▽</span>
                        </th>
                    </tr>
                </thead>

                <!-- Corps du tableau -->
                <tbody id="tableBodyTracks" class="bg-gray-800 bg-opacity-90 divide-y divide-gray-700">
                    <?php foreach ($topTracks['items'] as $index => $track): // Pour chaque son dans le tableau des sons
                        $track_name = $track['name']; // On récupère le nom du son
                        $track_image = $track['album']['images'][0]['url']; // On récupère l'image du son
                        $track_url = $track['external_urls']['spotify']; // On récupère l'url du son
                        $track_artists = $track['artists']; // On récupère les artistes du son
                        $track_album_type = $track['album']['album_type']; // On récupère le type de l'album du son
                        $track_album_release_date = $track['album']['release_date']; // On récupère la date de sortie de l'album du son
                        ?>

                        <tr class="hover:bg-green-700 hover:bg-opacity-50 cursor-pointer" >
                        
                            <td class="py-4 px-6">
                                <?= $index + 1 ?>
                            </td>

                            <td class="py-4 px-6">
                                <?= htmlspecialchars($track_name) ?>
                            </td>

                            <td class="py-4 px-6 flex items-center">
                                <img class="h-10 w-10 rounded-full mr-3" src="<?= $track_image ?>" alt="<?= $track_name ?>"></img>
                                
                                <?php foreach ($track_artists as $artist): // Pour chaque artiste du son
                                    $track_artist = $artist['name']; // On récupère le nom de l'artiste
                                    $track_artist_id = $artist['id']; // On récupère l'id de l'artiste
                                    $url_artist = '/artist/' . urlencode($track_artist) . '?id=' . urlencode($track_artist_id); // On crée l'url de l'artiste pour pouvoir y accéder.
                                ?>
                                    <a href="<?=$url_artist?>" class="artist-link hover:underline hover:text-green-500" 
                                    data-artist-name="<?= htmlspecialchars($track_artist) ?>" data-artist-id="<?= htmlspecialchars($track_artist_id) ?>">
                                        <?= htmlspecialchars($track_artist) . '&nbsp;' ?>
                                    </a>
                                <?php endforeach; ?>
                            </td>


                            <td class="py-4 px-6">
                                <?= htmlspecialchars($track_album_type) ?>
                            </td>

                            <td class="py-4 px-6">
                                <?= htmlspecialchars($track_album_release_date) ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>