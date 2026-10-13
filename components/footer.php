 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="global.css">
<footer style="background: #333; color: white; padding: 2rem 0; margin-top: 2rem;">
    <div
    style="max-width: 1200px; margin: 0 auto; display: flex; flex-wrap: wrap; gap: 2rem; align-items: flex-start;">
        <div style="flex: 1; min-width: 250px;">
            <img src="https://i.postimg.cc/Rh4vpGD4/LOGO.png" alt="Wichy Coconut Logo" style="height: 100px; margin-bottom: 1rem;">
            <p style="margin-bottom: 1rem;">
                The Wichy Plantation Company (Pvt) Ltd connects consumers around the world with coconut farmers in
                Sri Lanka, promoting global health and wellbeing while enriching local communities through
                sustainable agricultural practices.
            </p>
            <div style="margin-bottom: 0.5rem; display: flex; align-items: center;">
                <i class="fa-solid fa-mobile-screen-button" style="margin-right: 0.75rem; width: 1em; text-align: center; color: white;"></i> +94 770 88 45 45
            </div>
             <div style="margin-bottom: 0.5rem; display: flex; align-items: center;">
                <i class="fa-solid fa-phone" style="margin-right: 0.75rem; width: 1em; text-align: center; color: white;"></i> +(94) 11-2891693
            </div>
            <div style="margin-bottom: 0.5rem; display: flex; align-items: flex-start;">
                <i class="fa-solid fa-location-dot" style="margin-right: 0.75rem; width: 1em; text-align: center; margin-top: 4px; color: white;"></i> 107, UDA Industrial Estate, Katuwana, Homagama, Sri Lanka.
            </div>
        </div>
        <form id="comment-form" action="save_message.php" method="POST"
      style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 1rem;">

    <input type="text" id="user_name" name="user_name" placeholder="NAME" required
           style="padding: 0.75rem; border-radius: 0.5rem; border: none;">

    <input type="email" id="user_email" name="user_email" placeholder="E - Mail" required
           style="padding: 0.75rem; border-radius: 0.5rem; border: none;">

    <textarea id="user_comment" name="user_comment" placeholder="Comment" required
              style="padding: 0.75rem; border-radius: 0.5rem; border: none; min-height: 80px;"></textarea>

    <button type="submit" id="submit-btn"
            style="background: #43a047; color: white; padding: 0.75rem; border-radius: 9999px; border: none; font-weight: bold;">SUBMIT</button>
            
    <!-- Success message container -->
    <div id="success-message" style="display: none; background: #d4edda; color: #155724; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #c3e6cb; text-align: center; font-weight: bold;">
        Your comment added successfully!
    </div>
    
    <!-- Error message container -->
    <div id="error-message" style="display: none; background: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f5c6cb; text-align: center; font-weight: bold;">
        Something went wrong. Please try again.
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('comment-form');
    const submitBtn = document.getElementById('submit-btn');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Hide any previous messages
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';
        
        // Change button text to show loading
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        
        // Create FormData object
        const formData = new FormData(form);
        
        // Send AJAX request
        fetch('save_message.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // Check if the response contains success message
            if (data.includes('Thank You') || data.includes('successfully')) {
                // Show success message
                successMessage.style.display = 'block';
                
                // Clear form fields
                document.getElementById('user_name').value = '';
                document.getElementById('user_email').value = '';
                document.getElementById('user_comment').value = '';
                
                // Hide success message after 5 seconds
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 5000);
            } else {
                // Show error message
                errorMessage.style.display = 'block';
                
                // Hide error message after 5 seconds
                setTimeout(function() {
                    errorMessage.style.display = 'none';
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorMessage.style.display = 'block';
            
            // Hide error message after 5 seconds
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 5000);
        })
        .finally(() => {
            // Reset button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>
    </div>
</footer>