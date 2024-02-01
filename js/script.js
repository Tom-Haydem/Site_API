/**
 * La méthode searchTable() permet de filtrer les lignes du tableau selon le texte entré dans le champ de recherche.
 * @param {HTMLElement} inputElement - L'élément de champ de recherche.
 * @param {HTMLElement} tableElement - L'élément de tableau.
 * @param {number} column - Le numéro de la colonne à filtrer.
 */
function searchTable(inputElement, tableElement, column) {
    var input, filter, table, tr, td, i, txtValue;
    


    input = inputElement; // Champ de recherche
    filter = input.value.toUpperCase(); // Convertir le texte en majuscules pour la recherche insensible à la casse
    table = tableElement; // Récupération du tableau
    tr = table.getElementsByTagName("tr"); // Récupération des lignes du tableau

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[column]; // Récupération de la colonne de l'artiste
        if (td) { // Si la colonne existe
            txtValue = td.textContent || td.innerText; // Récupération du texte de la colonne
            if (txtValue.toUpperCase().indexOf(filter) > -1) { // Si le texte de la colonne contient le texte de recherche
                tr[i].style.display = ""; // Afficher la ligne
            } else {
                tr[i].style.display = "none"; // Cacher la ligne
            }
        }
    }
}

/**
 * La méthode sortTable() permet de trier les lignes du tableau selon la colonne choisie.
 * @param {HTMLElement} tableElement - L'élément de tableau.
 * @param {Number} n  - Le numéro de la colonne à trier.
 */
function sortTable(tableElement,n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;

    table = tableElement; // Récupération du tableau

    switching = true; // switching est vrai tant qu'un échange a été effectué

    // Mettre dir à asc par défaut :
    dir = "asc"; 

    while (switching) { // Boucle tant qu'un échange a été effectué
        
        switching = false; // Aucun échange n'a été effectué
        rows = table.rows; // Récupération des lignes du tableau
        
        
        // Boucler pour parcourir toutes les lignes du tableau sauf la première (en-tête)
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;

            // Prendre deux éléments à comparer, un de la ligne actuelle et un de la suivante :
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            // Vérifier si les deux lignes doivent échanger de place, en fonction de la direction, asc ou desc :
            if (dir == "asc") {
                if (n === 0) { // Si c'est la colonne du rang, comparer comme des nombres
                    if (Number(x.innerHTML) > Number(y.innerHTML)) {
                        shouldSwitch = true; // Si c'est le cas, marquer qu'un échange doit être effectué
                        break;
                    }
                } else { // Sinon, comparer comme des chaînes de caractères
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true; // Si c'est le cas, marquer qu'un échange doit être effectué
                        break;
                    }
                }
            } else if (dir == "desc") { // Si la direction est "desc"
                if (n === 0) { // Si c'est la colonne du rang, comparer comme des nombres
                    if (Number(x.innerHTML) < Number(y.innerHTML)) {
                        shouldSwitch = true;
                        break;
                    }
                } else { // Sinon, comparer comme des chaînes de caractères
                    if (x.innerHTML.toLowerCase().trim() < y.innerHTML.toLowerCase().trim()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
        }
        if (shouldSwitch) {
            // Si un échange a été marqué, faire l'échange et marquer qu'un échange a été effectué :
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            // Chaque fois qu'un échange est effectué, augmenter cette variable de 1 :
            switchcount++;      
        } else {
            // Si aucun échange n'a été effectué ET que la direction est "asc",
            // mettre la direction à "desc" et recommencer la boucle :
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }

    // Mettre à jour les icônes de tri
    var tableBodyElement = table.getElementsByTagName("tbody")[0];

    updateSortIcons(tableBodyElement, n, dir);
}





/**
 * La méthode updateSortIcons() permet de mettre à jour les icônes de tri du tableau.
 * @param {HTMLElement} tableBodyElement - L'élément de tableau.
 * @param {Number} column 
 * @param {String} direction 
 */
function updateSortIcons(tableBodyElement,column, direction) {
    id_icons_artist_table = {
        "0" : document.getElementById('icon-artist_rank'),
        "1" : document.getElementById('icon-artist_display_name'),
        "2" : document.getElementById('icon-artist_popularity'),
    }
    
    id_icons_track_table = {
        "0": document.getElementById('icon-track_rank'),
        "1": document.getElementById('icon-track_name'),
        "2": document.getElementById('icon-track_artist'),
        "3": document.getElementById('icon-track_album_type'),
        "4": document.getElementById('icon-track_release_date'),
    }


    if (tableBodyElement.id === "tableTopArtist") {
        var icons = id_icons_artist_table;
    } else if (tableBodyElement.id === "tableBodyTracks") {
        var icons = id_icons_track_table;
    }
    
    console.log(icons);

    for (var key in icons) {
        if (icons.hasOwnProperty(key)) {
            if (parseInt(key) === column) {
                icons[key].textContent = direction === 'asc' ? '△' : '▽'; // Met à jour l'icône de la colonne
            } else {
                icons[key].textContent = '⬍'; // Réinitialise les icônes des autres colonnes
            }
        }
    }
}


/**
 * La méthode toggleIframe() permet d'afficher ou de cacher un iframe.
 * @param {string} iframeId L'identifiant de l'iframe à afficher ou cacher.
 * @returns {void}
 */
function toggleIframe(iframeId) {
    var iframe = document.getElementById(iframeId);
    var iframes = document.querySelectorAll('div[id^="iframe"]');
    
    if (iframe.style.display === 'block') {
        iframe.style.display = 'none';
    } else {
        for (var i = 0; i < iframes.length; i++) {
            iframes[i].style.display = 'none';
        }
        iframe.style.display = 'block';
    }
}

// Attendez que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Trouvez tous les éléments avec la classe 'artist'
    var artists = document.querySelectorAll('.artist');
    
    // Ajoutez un écouteur d'événements pour chaque élément
    artists.forEach(function(artist) {
        artist.addEventListener('click', function() {
            // Récupérez le nom et l'ID de l'artiste
            var name = this.getAttribute('data-name');
            var id = this.getAttribute('data-id');

            // Redirigez vers la page de l'artiste avec le nom et l'ID en paramètres
            window.location.href = '/artist/' + encodeURIComponent(name) + '?id=' + encodeURIComponent(id);
        });
    });
});