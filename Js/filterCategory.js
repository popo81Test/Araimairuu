function filterCategory(category) {
    const menuItems = document.querySelectorAll('.menu-item');

    // Reset all button styles
    const buttons = document.querySelectorAll('button[onclick^="filterCategory"]');
    buttons.forEach(button => {
        button.classList.remove('bg-primary', 'text-white');
        button.classList.add('bg-gray-200', 'text-gray-700');
    });

    // Highlight active button
    const activeButton = document.querySelector(`button[onclick="filterCategory('${category}')"]`);
    if (activeButton) {
        activeButton.classList.remove('bg-gray-200', 'text-gray-700');
        activeButton.classList.add('bg-primary', 'text-white');
    }

    if (category === 'all') {
        menuItems.forEach(item => {
            item.style.display = 'block';
        });
    } else if (category === 'recommended') {
        menuItems.forEach(item => {
            if (item.classList.contains('category-recommended')) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
     else {
        menuItems.forEach(item => {
            if (item.classList.contains(`category-${category}`)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
}
