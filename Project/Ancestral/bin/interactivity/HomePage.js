window.addEventListener('scroll', function() {
  const header = document.getElementById('main-header');
  if (window.scrollY > 60) {
    header.style.top = '0px';
  } else {
    header.style.top = '60px';
  }
});

console.log("JS is linked");

console.log(document.cookie);

const welcomeMessage = document.getElementById('welcome');
const companyName = document.getElementById('COMname');
const logo = document.getElementById('logo');

window.addEventListener('load', function() {

  this.setTimeout(() => {
    welcomeMessage.classList.add('show');
  }, 500);


  this.setTimeout(() => {
    logo.style.transform = "rotate3d(0,1, 0, 360deg)";
    logo.style.transition = 'all 3s ease-in-out';
    
});



});


const logoutimg = document.getElementById("log");
const btnlogout = document.getElementById("logbtn");





btnlogout.addEventListener("mouseenter", function() {
    logoutimg.src = "../../Logout.png";
    btnlogout.style.cursor = "pointer";
});
btnlogout.addEventListener("mouseleave", function() {
    logoutimg.src = "../../Logout2.png";
    btnlogout.style.cursor = "pointer";
    btnlogout.style.transition = "0.5s";
});


    const user = document.getElementById("User");


    if (usernameFromPHP) {
        // If logged in
        user.textContent =  usernameFromPHP;
        user.href = "Track.php"; // Redirect to profile or homepage
    } else {
        // If not logged in
        user.textContent = "Sign In";
        user.href = "signup.php";
    }






  

