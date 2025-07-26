<!doctype html>
<html class="Headings">

<style>

.Headings {
    background-color: #e9f2fb;
    font-family: Arial, sans-serif;
    color: #153f6a;
}

.Division {
    background-color: white;
    padding: 10px;
    border: 1px solid #ccc;
    display: inline-block;
    width: 50%;
    box-sizing: border-box;
}

.btn-group {
    margin-top: 15px;
}

.btn {
    background-color: #007BFF;
    color: white;
    padding: 8px 14px;
    margin-right: 10px;
    border: none;
    cursor: pointer;
}

.clear {
    background-color: #007BFF;
    color: white;
    padding: 8px 14px;
    margin-right: 10px;
    border: none;
    cursor: pointer;
}

.overflow-box {
    width: 300px;
    height: 60px;
    border: 1px solid red;
    overflow: auto;
    padding: 5px;
    margin-top: 15px;
    font-size: 14px;
    position: relative;
    z-index: 2;
}


input:focus, select:focus, textarea:focus {
    outline: 2px solid #007BFF;
}


#fullname {
    outline: 2px dashed #33aaff;
}

</style>

<head>
    <div>
        <center>
            <h1>Bank Management System</h1>
            <h2>Your Trusted Financial Partner</h2>
        </center>
    </div>
</head>

<body>
    <h3 style="color:black"><b>Customer Registration Form </b> </h3>

    <div class="Division">
        <table>
            <tr>
                <td>Full Name:</td>
                <td></td>
                <td><input type="text" name="fullname" id="fullname"></td>
            </tr>

            <tr>
                <td>Date of Birth:</td>
                <td></td>
                <td><input type="date"></td>
            </tr>

            <tr>
                <td>Gender:</td>
                <td></td>
                <td>
                    <input type="radio"> Male
                    <input type="radio"> Female
                    <input type="radio"> Other
                </td>
            </tr>

            <tr>
                <td>Marital Status:</td>
                <td></td>
                <td>
                    <select>
                        <option>Single</option>
                        <option>Married</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Account Type</td>
                <td></td>
                <td>
                    <select>
                        <option>Savings</option>
                        <option>Checkings</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Initial Deposit Amount :</td>
                <td></td>
                <td><input type="number"></td>
            </tr>

            <tr>
                <td>Mobile Number :</td>
                <td></td>
                <td><input type="text"></td>
            </tr>

            <tr>
                <td>Email Address:</td>
                <td></td>
                <td><input type="email"></td>
            </tr>

            <tr>
                <td>Address:</td>
                <td></td>
                <td><input type="address"></td>
            </tr>

            <tr>
                <td>Occupation:</td>
                <td></td>
                <td><input type="text"></td>
            </tr>

            <tr>
                <td>National ID(NID):</td>
                <td></td>
                <td><input type="text"></td>
            </tr>

            <tr>
                <td>Set Password:</td>
                <td></td>
                <td><input type="password" name="password"></td>
            </tr>

            <tr>
                <td>Upload ID Proof:</td>
                <td></td>
                <td><input type="file"></td>
            </tr>

            <tr>
                <td><input type="checkbox"> I agree to the terms and conditions</td>
            </tr>

            <tr>
                <td></td>
                <td class="btn-group">
                    <button type="submit" class="btn">Register</button>
                    <button type="reset" class="clear">Clear</button>
                </td>
            </tr>
        </table>
    </div>

    <div class="overflow-box">
        This is a demo text to show how overflow works in a small container with a fixed size and scrollbar added when the content is too long to fit inside the box.
    </div>
</body>
</html>
