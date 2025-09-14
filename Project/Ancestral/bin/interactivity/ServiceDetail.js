




window.addEventListener('scroll', function() {
  const header = document.getElementById('main-header');
  if (window.scrollY > 60) {
    header.style.top = '0px';
  } else {
    header.style.top = '60px';
  }
});


console.log('hellow');

// Get the URL parameters
const params = new URLSearchParams(window.location.search);

// Retrieve specific variables
const service_id = params.get('service_id'); // "Sajid"




