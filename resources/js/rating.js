// Vérifier périodiquement si l'utilisateur peut noter l'application
function checkForRating() {
    fetch('/ask-for-rating')
        .then(response => response.json())
        .then(data => {
            if (data.showRatingPrompt) {
                showRatingPrompt();
            }
        });
}

function showRatingPrompt() {
    // Afficher une boîte de dialogue ou un modal pour demander la notation
    // Par exemple :
    if (confirm("Aimez-vous utiliser notre application ? Voulez-vous la noter ?")) {
        window.location.href = '/rate-app';
    }
}

// Vérifier toutes les 5 minutes, par exemple
setInterval(checkForRating, 5 * 60 * 1000);