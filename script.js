function toggleCategories() {
    var categoriesList = document.getElementById("categories-list");
    if (categoriesList.style.display === "none") {
        categoriesList.style.display = "block";
    } else {
        categoriesList.style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    function filterRecipes() {
        const searchBar = document.getElementById('searchBar');
        const filter = searchBar.value.toLowerCase();
        const recipesContainer = document.getElementById('recipesContainer');
        const recipes = recipesContainer.getElementsByClassName('recipe');
        
        for (let i = 0; i < recipes.length; i++) {
            const title = recipes[i].getElementsByTagName('h2')[0].textContent || recipes[i].getElementsByTagName('h2')[0].innerText;
            if (title.toLowerCase().indexOf(filter) > -1) {
                recipes[i].style.display = "";
            } else {
                recipes[i].style.display = "none";
            }
        }
    }
    
    document.getElementById('searchBar').addEventListener('keyup', filterRecipes);
});



