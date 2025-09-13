window.addEventListener('scroll', function() {
  const header = document.getElementById('main-header');
  if (window.scrollY > 60) {
    header.style.top = '0px';
  } else {
    header.style.top = '60px';
  }
});

const header = document.getElementById("in");
const logoutimg = document.getElementById("log");
const btnlogout = document.getElementById("logbtn");


header.addEventListener("mouseenter", function() {
    header.style.fontSize = "45px";
    header.style.cursor = "pointer";
    header.style.transition = "0.5s";

});


header.addEventListener("mouseleave", function() {
    header.style.fontSize = "40px";
    header.style.cursor = "default";
    header.style.transition = "0.5s";});

btnlogout.addEventListener("mouseenter", function() {
    logoutimg.src = "../../Logout.png";
    btnlogout.style.cursor = "pointer";
});
btnlogout.addEventListener("mouseleave", function() {
    logoutimg.src = "../../Logout2.png";
    btnlogout.style.cursor = "pointer";
    btnlogout.style.transition = "0.5s";
});



