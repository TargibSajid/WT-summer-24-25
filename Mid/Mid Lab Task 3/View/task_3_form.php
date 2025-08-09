<!DOCTYPE html>
<html>
<head>
  <title>Lab Task Form</title>
  <link rel="stylesheet" href="task_3_style.css">
  <script src="task_3_valid.js"></script>
</head>





<body>
  <div class="form-wrapper">
    <div class="form-container">
      <h2 class="section-title">Donor Information</h2>

      <label><b>First Name</b></label><br>
      <input type="text" id="firstName" name="firstName"><br>

      <label><b>Last Name</b></label><br>
      <input type="text" id="lastName" name="lastName"><br>

      <label><b>Address</b></label><br>
      <input type="text" id="address" name="address"><br>

      <label><b>City</b></label><br>
      <input type="text" id="city" name="city"><br>

      <label><b>State</b></label><br>
      <select id="state" name="state">
        <option>Select a State</option>
        <option value="Dhaka">Dhaka</option>
        <option value="Chattagram">Chattagram</option>
        <option value="Khulna">Khulna</option>
      </select><br>

      <label><b>Phone</b></label><br>
      <input type="text" id="phone" name="phone"><br>

      <label><b>Email</b></label><br>
      <input type="text" id="email" name="email"><br>

      <label><b>Create Password (min 8 characters)</b></label><br>
      <input type="password" id="password" name="password"><br>

      <label><b>Confirm Password</b></label><br>
      <input type="password" id="confirmPassword" name="confirmPassword"><br>

      <label><b>Donation Amount</b></label><br>
      <input type="radio" name="donationAmount" value="none"> None
      <input type="radio" name="donationAmount" value="500"> 500 BDT
      <input type="radio" name="donationAmount" value="1000"> 1000 BDT
      <input type="radio" name="donationAmount" value="2000"> 2000 BDT
      <input type="radio" name="donationAmount" value="3000"> 3000 BDT
      <input type="radio" name="donationAmount" value="other"> Other
      <br>

      <label><b>Other Amount</b></label><br>
      <input type="text" id="otherAmount" name="otherAmount"><br><br>

      <input type="checkbox" id="regularGift" name="regularGift">
      <label for="regularGift"><b>I am interested in giving on a regular basis.</b></label><br><br>

      <h2 class="section-title">Additional Information</h2>

      <input type="checkbox" id="anonymousGift" name="anonymousGift">
      <label for="anonymousGift"><b>I would like my gift to remain anonymous.</b></label><br><br>

      <input type="checkbox" id="employerMatch" name="employerMatch">
      <label for="employerMatch"><b>My employer offers a matching gift program.</b></label><br><br>

      <input type="checkbox" id="noThankYou" name="noThankYou">
      <label for="noThankYou"><b>Please don't send a thank you letter.</b></label><br><br>

      <label for="comments"><b>Comments</b></label><br>
      <textarea rows="4" cols="50" id="comments" name="comments"></textarea><br><br>

      <button type="reset">Reset</button>
      <button type="submit" onclick="filedfilled()">Submit</button>
    </div>
  </div>
</body>
</html>
