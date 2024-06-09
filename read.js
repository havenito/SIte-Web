fetch('read.php')
.then(response => response.json())
.then(data => {
    document.getElementById('recettes').textContent = JSON.stringify(data, null, 2);
})
.catch(error => console.error('Erreur:', error));