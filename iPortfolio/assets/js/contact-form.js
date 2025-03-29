document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.php-email-form');
    
    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = this;
        const loading = form.querySelector('.loading');
        const errorMessage = form.querySelector('.error-message');
        const sentMessage = form.querySelector('.sent-message');
        
        // Reset states
        loading.style.display = 'block';
        errorMessage.style.display = 'none';
        sentMessage.style.display = 'none';
        errorMessage.textContent = '';

        try {
            // Create form data object
            const formData = new FormData(form);
            
            // Convert to JSON if needed
            const jsonData = {};
            formData.forEach((value, key) => {
                jsonData[key] = value;
            });

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(jsonData)
            });

            // Handle response
            const data = await response.json();
            
            loading.style.display = 'none';
            
            if (data.success) {
                sentMessage.textContent = data.message;
                sentMessage.style.display = 'block';
                form.reset();
                
                setTimeout(() => {
                    sentMessage.style.display = 'none';
                }, 5000);
            } else {
                throw new Error(data.message || 'Request failed');
            }
            
        } catch (error) {
            console.error('Submission error:', error);
            loading.style.display = 'none';
            errorMessage.textContent = error.message || 'Failed to send message. Please try again.';
            errorMessage.style.display = 'block';
        }
    });
});