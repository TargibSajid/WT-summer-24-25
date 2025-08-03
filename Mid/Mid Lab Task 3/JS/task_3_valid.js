function filedfilled ()
{
	const firstName = document.getElementById("firstName").value.trim();

    const lastName = document.getElementById("lastName").value.trim();

    const address = document.getElementById("address").value.trim();

    const city = document.getElementById("city").value.trim();

    const state = document.getElementById("state").value;

    const phone = document.getElementById("phone").value.trim();

    const email = document.getElementById("email").value.trim();

    const password = document.getElementById("password").value;

    const confirmPassword = document.getElementById("confirmPassword").value;

    const otherAmount = document.getElementById("otherAmount").value.trim();

    const donationRadios = document.getElementsByName("donationAmount");

 
    if (!firstName || !lastName || !address || !city || !phone || !email || !password || !confirmPassword)
       {
      alert("Please fill in all required fields.");
      return false;
        }

          const nameRegex = /^[A-Za-z\s]+$/;
  if (!nameRegex.test(firstName) || !nameRegex.test(lastName)) {
    alert("First and Last Name should contain alphabets only.");
    return false;
  }


  const phoneRegex = /^\d{11}$/;
  if (!phoneRegex.test(phone)) {
    alert("Phone number must be exactly 11 digits.");
    return false;
  }

  
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    alert("Please enter a valid email address.");
    return false;
  }


  const passRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
  if (!passRegex.test(password)) {
    alert("Password must contain at least 1 uppercase, 1 lowercase, 1 digit, 1 special character, and be at least 8 characters long.");
    return false;
  }

 
  if (password !== confirmPassword) {
    alert("Password and Confirm Password do not match.");
    return false;
  }

  
  let donationSelected = false;
  for (let i = 0; i < donationRadios.length; i++) {
    if (donationRadios[i].checked) {
      donationSelected = true;
      break;
    }
  }

  
  if (!donationSelected) {
    alert("Please select a donation amount.");
    return false;
  }

  alert("Form submitted successfully!");
  return true;



}