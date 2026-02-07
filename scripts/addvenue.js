document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('venue-form');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); // Stop page reload

        // Get values
        const venueName = document.getElementById('venue-name').value;
        const venueLocation = document.getElementById('venue-location').value;
        const venueContact = document.getElementById('venue-contact').value;
        const venueEmail = document.getElementById('venue-email').value;
        const venueDescription = document.getElementById('venue-description').value;
        const venuePrice = document.getElementById('venue-price').value; // Fix ID

        // File handling - SIMPLIFIED for now
        const imageInput = document.getElementById('venue-images');
        const venueImage =
            imageInput.files.length > 0 ? imageInput.files[0].name // Just use first filename
                : 'default-venue.jpg'; // Fallback image

        const openingTime = document.getElementById('opening-time').value;
        const closingTime = document.getElementById('closing-time').value;

        const newVenue = {
            id: Date.now(),
            image: venueImage,
            name: venueName,
            address: venueLocation,
            bookingPrice: venuePrice,
            contact: venueContact,
            email: venueEmail,
            description: venueDescription,
            operatingHours: `${openingTime} to ${closingTime}`,
            rating: { stars: 0 }
        };

        let venues = JSON.parse(localStorage.getItem('venues')) || [];
        venues.push(newVenue);
        localStorage.setItem('venues', JSON.stringify(venues));

        form.reset();
        alert('✅ Venue added successfully!');

    });
});