




window.addEventListener('scroll', function() {
  const header = document.getElementById('main-header');
  if (window.scrollY > 60) {
    header.style.top = '0px';
  } else {
    header.style.top = '60px';
  }
});

const srlogo = document.getElementById("srlogo");


const params = new URLSearchParams(window.location.search);

// Retrieve specific variables
const service_id = params.get('service_id'); 

if(service_id === "1")
{
  srlogo.src = "../../AI.png";
}
if(service_id === "2")
{
  srlogo.src = "../../WEB.png";
}

if(service_id === "3")
{
  srlogo.src = "../../SOFT.png";
}

if(service_id === "4")
{
  srlogo.src = "../../MAIN.png";
}

if(service_id === "5")
{
  srlogo.src = "../../CLOUD.png";
}

if(service_id === "6")
{
  srlogo.src = "../../UI.png";
}

if(service_id === "7")
{
  srlogo.src = "../../TECH.png";
}

if(service_id === "8")
{
  srlogo.src = "../../CYBER.png";
}

if(service_id === "9")
{
  srlogo.src = "../../DATA.png";
}

if(service_id === "10")
{
  srlogo.src = "../../GAME.png";
}

if(service_id === "11")
{
  srlogo.src = "../../AI.png";
}



