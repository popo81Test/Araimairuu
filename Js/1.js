function filterCategory(category) {
    const items = document.querySelectorAll('.menu-item');
    items.forEach(item => {
        if (category === 'all' || item.classList.contains(`category-${category}`)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

//น่าจะยังไม่เสร็จ