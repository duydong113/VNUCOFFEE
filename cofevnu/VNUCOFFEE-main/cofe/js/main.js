document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.slider-container');
    const prevButton = document.getElementById('prevBtn');
    const nextButton = document.getElementById('nextBtn');
    
    if (slider && prevButton && nextButton) {
        const slideWidth = 240; // Width of item + gap
        
        // Update buttons state
        const updateButtons = () => {
            prevButton.disabled = slider.scrollLeft <= 0;
            nextButton.disabled = slider.scrollLeft >= slider.scrollWidth - slider.clientWidth;
        };

        // Scroll to next/previous item
        prevButton.addEventListener('click', () => {
            slider.scrollLeft -= slideWidth;
            updateButtons();
        });
        
        nextButton.addEventListener('click', () => {
            slider.scrollLeft += slideWidth;
            updateButtons();
        });
        
        // Update buttons on scroll
        slider.addEventListener('scroll', updateButtons);
        
        // Initial button state
        updateButtons();
        
        // Optional: Touch/mouse drag scrolling
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });
    }
});

// Script for changing hero section background based on gallery image click
document.addEventListener('DOMContentLoaded', () => {
  const heroSection = document.querySelector('.hero-section');
  const smallImages = document.querySelectorAll('.small-images-gallery a');

  if (heroSection && smallImages.length > 0) {
    smallImages.forEach(imageLink => {
      imageLink.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent default link behavior
        const imageUrl = imageLink.querySelector('img').src;
        // Update background image using inline style to override CSS rule
        heroSection.style.backgroundImage = `url('${imageUrl}')`;
        heroSection.style.backgroundSize = 'cover'; // Ensure cover size is maintained
        heroSection.style.backgroundPosition = 'center'; // Ensure center position
        heroSection.style.backgroundRepeat = 'no-repeat'; // Ensure no-repeat
      });
    });
  }
});

// Script to show Add to Cart success modal
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('addToCartModal');
  const modalMessage = document.getElementById('modalMessage');
  const closeButton = modal ? modal.querySelector('.close-button') : null;

  // Check if the flag to show modal is set (this would require PHP to set a JS variable or similar)
  // A simpler approach for now is to check for a specific message content
  const currentMessageElement = document.querySelector('.alert.alert-success'); // Assuming success message is displayed in an alert
  if (currentMessageElement && (currentMessageElement.innerHTML.includes('Đã thêm sản phẩm vào giỏ hàng thành công!') || currentMessageElement.innerHTML.includes('Đã cập nhật số lượng sản phẩm trong giỏ hàng!'))) {
    // Copy the message to the modal and display it
    modalMessage.innerHTML = currentMessageElement.innerHTML; // Copy the HTML including links
    if (modal) {
       modal.style.display = 'flex'; // Use flex to center content
    }
    // Optionally, hide the original alert message
    currentMessageElement.style.display = 'none';

    // Clearing the session flag would ideally happen in PHP after displaying once.
  }

  // Close modal when clicking the close button
  if (closeButton) {
    closeButton.addEventListener('click', () => {
      if (modal) modal.style.display = 'none';
    });
  }

  // Close modal when clicking outside the modal content
  if (modal) {
    window.addEventListener('click', (event) => {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  }
});