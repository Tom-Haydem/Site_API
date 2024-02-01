/**
 * La méthode toggleText() permet d'afficher ou de cacher le texte d'une cellule.
 * @param {HTMLElement} cell
 * @param {String} fullText
 */
function toggleText(cell, fullText) {
    if (cell.classList.contains('truncate')) {
        cell.classList.remove('truncate');
        cell.textContent = fullText;
    } else {
        cell.classList.add('truncate');
        cell.textContent = fullText.substring(0, 30) + '...';
    }
}


/**
 * La méthode toggleVisibility() permet d'afficher ou de cacher le contenu d'une ligne.
 * @param {Event} event 
 */
function toggleVisibility(event) {
    let target = event.currentTarget;
    let content = target.nextElementSibling;
    let arrow = target.querySelector('span'); // Récupérer le span inclus dans le span
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        target.classList.add('text-white');
        arrow.textContent = '▽'; // À replier
    } else {
        content.classList.add('hidden');
        target.classList.remove('text-white');
        arrow.textContent = '▷'; // À déplier
    }
}

/**
 * Ajout d'un déclencheur d'événement sur chaque ligne du tableau sur le sélecteur de date
 * Lorsque l'utilisateur quitte le champ, on récupère la valeur saisie et on compare avec les dates
 * de chaque ligne du tableau. Si la date saisie est incluse dans la date de la ligne, on affiche
 * la ligne, sinon on la cache.
 */
document.getElementById('date-input').addEventListener('blur', function () {
    let date = this.value;
    let rows = document.getElementById('logs-table').querySelectorAll('tbody tr');
    for (let i = 0; i < rows.length; i++) {
        let row = rows[i];
        let rowDate = row.cells[0].textContent;
        if (rowDate.includes(date)) {
            row.classList.remove('hidden');
        } else {
            row.classList.add('hidden');
        }
    }
});

