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

// Roue du destin : la roue tourne et s'arrête sur le plat déjà tiré au sort par PHP.
// Le JS ne décide rien : il calcule seulement l'angle pour que la flèche tombe sur le gagnant.
var wheel = document.getElementById('wheel');

if (wheel) {
    var count = Number(wheel.dataset.count);             // nombre de parts
    var winnerIndex = Number(wheel.dataset.winnerIndex); // position du gagnant dans la roue
    var result = document.getElementById('wheel-result');
    var spinButton = document.getElementById('wheel-spin');
    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Avec JS : on cache le résultat et on montre le bouton pour lancer la roue
    result.hidden = true;
    spinButton.hidden = false;

    var showResult = function () {
        result.hidden = false;
        result.classList.add('is-revealed'); // petite animation d'apparition (style.css)
    };

    spinButton.addEventListener('click', function () {
        spinButton.disabled = true; // un seul lancer par tirage

        var slice = 360 / count;                          // taille d'une part, en degrés
        var middle = (winnerIndex + 0.5) * slice;         // milieu de la part gagnante (0° = en haut)
        var offset = (Math.random() - 0.5) * slice * 0.6; // léger décalage : la flèche ne tombe pas toujours pile au centre
        // 5 tours complets, puis on recule de "middle" : la part gagnante finit sous la flèche
        var angle = 5 * 360 - middle + offset;

        if (reduceMotion) {
            // Mouvement réduit demandé : pas de rotation animée, résultat immédiat
            wheel.style.transform = 'rotate(' + angle + 'deg)';
            showResult();
            return;
        }

        // transitionend : déclenché quand la rotation CSS est terminée. once : écouté une seule fois
        wheel.addEventListener('transitionend', showResult, { once: true });
        wheel.style.transform = 'rotate(' + angle + 'deg)'; // lance la rotation (transition dans style.css)
    });
}
