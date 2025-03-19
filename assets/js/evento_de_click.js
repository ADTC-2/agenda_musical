    // Seleciona todos os itens do menu
    const menuItems = document.querySelectorAll('.menu-item');

    // Adiciona o evento de clique em cada item
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove a classe 'active' de todos os itens
            menuItems.forEach(i => i.classList.remove('active'));

            // Adiciona a classe 'active' no item clicado
            this.classList.add('active');
        });
    });