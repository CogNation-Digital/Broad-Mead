       // Auto-submit form on Enter key
        document.querySelectorAll('.filter-input').forEach(function(input) {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.form.submit();
                }
            });
        });
        
        // Mailshot specific JavaScript
        <?php if ($mode === 'mailshot'): ?>
        // Select all functionality
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="selected_candidates[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
            
            // Update "select all" checkbox when individual checkboxes change
            document.querySelectorAll('input[name="selected_candidates[]"]').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const allCheckboxes = document.querySelectorAll('input[name="selected_candidates[]"]');
                    const checkedCheckboxes = document.querySelectorAll('input[name="selected_candidates[]"]:checked');
                    selectAllCheckbox.checked = allCheckboxes.length === checkedCheckboxes.length;
                });
            });
        }
        <?php endif; ?>
        
        // Highlight search terms in results
        function highlightSearchTerms() {
            const keyword = '<?php echo addslashes($keyword_filter); ?>';
            const location = '<?php echo addslashes($location_filter); ?>';
            const position = '<?php echo addslashes($position_filter); ?>';
            
            if (keyword || location || position) {
                const terms = [keyword, location, position].filter(term => term.length > 0);
                
                terms.forEach(term => {
                    if (term.length > 2) {
                        const regex = new RegExp(`(${term})`, 'gi');
                        document.querySelectorAll('tbody td').forEach(cell => {
                            if (cell.innerHTML.match(regex)) {
                                cell.innerHTML = cell.innerHTML.replace(regex, '<mark>$1</mark>');
                            }
                        });
                    }
                });
            }
        }
        
        // Call highlight function after page load
        document.addEventListener('DOMContentLoaded', highlightSearchTerms);