document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive features for room browsing here
    const searchInput = document.getElementById('roomSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const roomCards = document.querySelectorAll('.card');
            
            roomCards.forEach(card => {
                const roomName = card.querySelector('.card-title').textContent.toLowerCase();
                const equipment = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
                
                if (roomName.includes(searchTerm) || equipment.includes(searchTerm)) {
                    card.closest('.col-md-4').style.display = '';
                } else {
                    card.closest('.col-md-4').style.display = 'none';
                }
            });
        });
    }
});
