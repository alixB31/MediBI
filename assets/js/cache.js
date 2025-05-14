// cache.js

const cacheTTL = 3600 * 1000; // Durée de vie du cache en millisecondes (1h)
const cachePrefix = 'medicamentCache_';

// Fonction pour récupérer les données depuis le cache ou faire une requête si le cache est expiré
function getCachedData(key) {
    const cachedData = localStorage.getItem(cachePrefix + key);
    if (cachedData) {
        const parsedData = JSON.parse(cachedData);
        const cacheTimestamp = parsedData.timestamp;

        // Vérifier si le cache est encore valide
        if (Date.now() - cacheTimestamp < cacheTTL) {
            console.log("Utilisation du cache pour " + key);
            return parsedData.data;
        }
    }
    return null;
}

// Fonction pour mettre à jour le cache
function setCacheData(key, data) {
    const cachedData = {
        timestamp: Date.now(),
        data: data
    };
    localStorage.setItem(cachePrefix + key, JSON.stringify(cachedData));
}

// Fonction pour récupérer les médicaments depuis le serveur avec gestion du cache
function fetchMedicaments(url) {
    const cachedMedicaments = getCachedData('medicaments');

    if (cachedMedicaments) {
        return Promise.resolve(cachedMedicaments);
    }

    // Si pas dans le cache ou si le cache est expiré, faire une requête AJAX
    return $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {
            setCacheData('medicaments', data);
            return data;
        },
        error: function () {
            alert('Erreur lors du chargement des données.');
        }
    });
}

// Fonction pour récupérer un médicament spécifique par ID avec cache
function fetchMedicamentById(id, url) {
    const cachedMedicament = getCachedData('medicament_' + id);

    if (cachedMedicament) {
        return Promise.resolve(cachedMedicament);
    }

    // Si pas dans le cache ou si le cache est expiré, faire une requête AJAX
    return $.ajax({
        url: url,
        method: 'GET',
        data: { id: id },
        success: function (data) {
            setCacheData('medicament_' + id, data);
            return data;
        },
        error: function () {
            alert('Erreur lors du chargement des données.');
        }
    });
}
