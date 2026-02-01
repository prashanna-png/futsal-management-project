window.scrollToVenueSection = function() {
  const section = document.getElementById('venue-section');
  section.scrollIntoView({ 
    behavior: 'smooth', 
    block: 'start' 
  });
};

import { venueData } from '../data/venue.js';

let venueHtml = '';
venueData.forEach(venue => {
  venueHtml += `
    <div class="venue-card">
      <img src="images/1.jpg" alt="" width="100%" />
      <h3>${venue.name}</h3>
      <p>${venue.address}</p>
      <p>Available Courts: ${venue.availableCourts}</p>
      <p>Booking Price: Rs. ${venue.bookingPrice}/hour</p>
      <button>View Details</button>
    </div>
  `;
});

document.querySelector('.venue-cards').innerHTML = venueHtml;

