// Form Validation and Utility Functions

// Validate email format
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Validate phone number (basic validation)
function validatePhone(phone) {
    const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
    return phoneRegex.test(phone);
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Validate contact form
function validateContactForm() {
    const name = document.getElementById('customer_name').value.trim();
    const email = document.getElementById('customer_email').value.trim();
    const phone = document.getElementById('customer_phone').value.trim();
    const message = document.getElementById('message').value.trim();

    if (!name || name.length < 3) {
        alert('Please enter a valid name (at least 3 characters)');
        return false;
    }

    if (!email || !validateEmail(email)) {
        alert('Please enter a valid email address');
        return false;
    }

    if (!phone || !validatePhone(phone)) {
        alert('Please enter a valid phone number');
        return false;
    }

    if (!message || message.length < 10) {
        alert('Please enter a message (at least 10 characters)');
        return false;
    }

    return true;
}

// Validate car form (admin)
function validateCarForm() {
    const name = document.getElementById('name').value.trim();
    const brand = document.getElementById('brand').value.trim();
    const model = document.getElementById('model').value.trim();
    const year = document.getElementById('year').value.trim();
    const price = document.getElementById('price').value.trim();

    if (!name || name.length < 3) {
        alert('Please enter a valid car name');
        return false;
    }

    if (!brand || brand.length < 2) {
        alert('Please enter a valid brand');
        return false;
    }

    if (!model || model.length < 2) {
        alert('Please enter a valid model');
        return false;
    }

    if (!year || year < 1900 || year > new Date().getFullYear() + 1) {
        alert('Please enter a valid year');
        return false;
    }

    if (!price || price <= 0) {
        alert('Please enter a valid price');
        return false;
    }

    return true;
}

// Confirm delete action
function confirmDelete(itemName) {
    return confirm(`Are you sure you want to delete ${itemName}? This action cannot be undone.`);
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });

    // Mobile nav toggle
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            const isOpen = navMenu.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // Add form validation listeners
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            if (!validateContactForm()) {
                e.preventDefault();
            }
        });
    }

    const carForm = document.getElementById('carForm');
    if (carForm) {
        carForm.addEventListener('submit', function(e) {
            if (!validateCarForm()) {
                e.preventDefault();
            }
        });
    }

    // Image preview for multiple images input (admin add-car)
    const imagesInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    if (imagesInput && imagePreview) {
        imagesInput.addEventListener('change', function() {
            imagePreview.innerHTML = '';
            const files = Array.from(this.files).slice(0, 8); // limit preview to 8
            files.forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                const wrap = document.createElement('div');
                wrap.style.width = '90px';
                wrap.style.height = '70px';
                wrap.style.overflow = 'hidden';
                wrap.style.borderRadius = '6px';
                wrap.style.border = '1px solid #333';
                wrap.style.background = '#111';
                const img = document.createElement('img');
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                wrap.appendChild(img);
                imagePreview.appendChild(wrap);
            });
        });
    }

    // Gallery thumbnail click behavior (car details)
    function initGallery() {
        const mainImage = document.getElementById('mainImage');
        const thumbs = document.querySelectorAll('.thumb');
        if (!mainImage || !thumbs.length) return;
        thumbs.forEach((t, idx) => {
            t.addEventListener('click', function() {
                const src = this.querySelector('img').getAttribute('data-src') || this.querySelector('img').src;
                mainImage.src = src;
                thumbs.forEach(x => x.classList.remove('active'));
                this.classList.add('active');
            });
            // mark first as active
            if (idx === 0) t.classList.add('active');
        });
    }
    initGallery();

    // Small behaviour for chat widget: allow label toggle on click (desktop)
    const chatWidget = document.querySelector('.chat-widget');
    if (chatWidget) {
        const label = chatWidget.querySelector('.chat-label');
        const btn = chatWidget.querySelector('.chat-btn');
        // On larger screens clicking the icon toggles the label visibility for easier access
        if (btn && label) {
            btn.addEventListener('click', function(e) {
                // If on mobile, let the link open directly (do not toggle)
                if (window.innerWidth <= 640) return;
                e.preventDefault();
                const isHidden = label.style.display === 'none' || getComputedStyle(label).display === 'none';
                label.style.display = isHidden ? 'inline-block' : 'none';
            });
        }
    }
});

// Update contact status with AJAX (admin)
function updateContactStatus(contactId, newStatus) {
    if (!confirm('Are you sure you want to update this status?')) {
        return;
    }

    fetch('update-contact-status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'contact_id=' + contactId + '&status=' + newStatus
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            alert('Status updated successfully');
            location.reload();
        } else {
            alert('Error updating status');
        }
    })
    .catch(error => console.error('Error:', error));
}

// Delete contact with confirmation
function deleteContact(contactId) {
    if (confirmDelete('this contact')) {
        window.location.href = 'delete-contact.php?id=' + contactId;
    }
}

// Delete car with confirmation
function deleteCar(carId) {
    if (confirmDelete('this car')) {
        window.location.href = 'delete-car.php?id=' + carId;
    }
}
