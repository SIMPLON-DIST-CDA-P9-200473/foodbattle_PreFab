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