const checkboxes = document.querySelectorAll('.task-checkbox');

        // Add click event listener to each checkbox
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Find the parent task element
                const taskElement = this.closest('.task');

                // Toggle the completed class on the content element
                const contentElement = taskElement.querySelector('.content');

                if (this.checked) {
                    contentElement.classList.add('completed');
                } else {
                    contentElement.classList.remove('completed');
                }
            });
        });
