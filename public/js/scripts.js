
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('#editEntryBtn');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bookId = this.getAttribute('data-id');
            const modalBody = document.querySelector('#editModal .modal-body');

            // Lade den Inhalt dynamisch in das Modal
            fetch(`editForm.php?id=${bookId}`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                });
        });
    });
});