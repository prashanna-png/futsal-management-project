
import { venueData } from '../data/venue.js';

let venueHtml = '';
venueData.forEach(venue => {
  venueHtml += `
    <div class="venue-card">
      <img class="venue-image" src="images/${venue.image}" alt="${venue.name}" width="100%" />
      <h3>${venue.name}</h3>
      <p>${venue.address}</p>
      <p>Booking Price: Rs. ${venue.bookingPrice}/hour</p>
      <p>Contact: ${venue.contact}</p>
      <div class="product-rating-container">
        <img class="product-rating-stars" src="images/ratings/rating-${venue.rating.stars * 10}.png">
      </div>
      <button>View Details</button>
    </div>
  `;
});

document.querySelector('.venue-cards').innerHTML = venueHtml;

window.scrollToVenueSection = function() {
  const section = document.getElementById('venue-section');
  section.scrollIntoView({ 
    behavior: 'smooth', 
    block: 'start' 
  });
};
