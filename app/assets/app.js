// Confirmation de suppression d'un plat avec une fenêtre <dialog> (présente uniquement sur le dashboard).
var dialog = document.getElementById('confirm-delete');

if (dialog) {
    var formToSubmit = null; // formulaire du plat en attente de confirmation

   document.querySelectorAll('.delete-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // on bloque l'envoi tant que l'admin n'a pas confirmé
            formToSubmit = form;
            // le nom du plat est affiché comme du texte, jamais comme du HTML
            document.getElementById('confirm-delete-name').textContent = form.dataset.name;
            dialog.returnValue = ''; // efface la réponse précédente
            dialog.showModal();
        });
    });

 // À la fermeture, returnValue contient la "value" du bouton cliqué
    dialog.addEventListener('close', function () {
        if (dialog.returnValue === 'confirm' && formToSubmit) {
            formToSubmit.submit();
        }
        formToSubmit = null;
    });
}

// Messages après un vote : fermeture au clic sur × et disparition automatique.
// Chaque message a la classe "flash" (voir plat.php).
document.querySelectorAll('.flash').forEach(function (flash) {
    function hide() {
        flash.classList.add('is-hiding'); // lance le fondu défini dans style.css
        setTimeout(function () { flash.remove(); }, 400); // retire le message une fois le fondu terminé (0,4 s)
    }

    var closeButton = flash.querySelector('.flash-close');
    if (closeButton) {
        closeButton.addEventListener('click', hide); // fermeture manuelle
    }

    setTimeout(hide, 5000); // disparition automatique après 5 secondes
});

// Retire "vote=..." de l'adresse : sinon, un rechargement (F5) réafficherait le même message.
if (window.location.search.indexOf('vote=') !== -1) {
    var url = new URL(window.location.href);
    url.searchParams.delete('vote');
    window.history.replaceState(null, '', url);
}