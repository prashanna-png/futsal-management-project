function scrollToVenueSection() {
  const section = document.getElementById('venue-section');
  section.scrollIntoView({ 
    behavior: 'smooth', 
    block: 'start' 
  });
}
