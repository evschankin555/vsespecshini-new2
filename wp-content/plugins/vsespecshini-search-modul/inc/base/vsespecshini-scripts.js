document.addEventListener("DOMContentLoaded", function() {
    const tile = document.querySelector('.home-search-layout');
    if (!tile) {
        return;
    }

    tile.style.display = 'none';

    const isUserLoggedIn = tile.getAttribute('data-loggedin') === 'true';

    if (isUserLoggedIn) {
        tile.style.display = 'flex';
    }
    const form = document.querySelector('form[action="/filter/tyre/"]');
    form.addEventListener("submit", function(e) {
        e.preventDefault();
        let params = [];
        form.querySelectorAll('select').forEach(select => {
            const name = select.name;
            const value = select.value;
            if (value) {
                params.push(value);
            }
        });

        const searchQuery = params.join("+");
        window.location.href = `https://vsespecshini.ru/?s=${searchQuery}&post_type=product&taxonomy=product_cat&product_cat=shini`;
    });
});