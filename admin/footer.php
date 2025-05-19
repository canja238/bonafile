</div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirm before deleting items
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this item?')) {
                        e.preventDefault();
                    }
                });
            });
            
            // File upload preview
            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.addEventListener('change', function(e) {
                    const file = this.files[0];
                    const preview = this.parentElement.querySelector('.file-upload-preview');
                    
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.classList.add('visible');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.classList.remove('visible');
                    }
                });
            });
            
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', function() {
                    document.getElementById('mobile-menu').classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html>