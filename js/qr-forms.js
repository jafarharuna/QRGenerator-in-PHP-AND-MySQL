document.getElementById('dataType').addEventListener('change', function() {
    var dataType = this.value;
    var dataInputDiv = document.getElementById('dataInput');
    dataInputDiv.innerHTML = ''; 

    switch (dataType) {
case 'link':
    dataInputDiv.innerHTML = '<label for="data" class="c2 dark-c4 fs20 fs32-M ">Link:</label><input type="url" id="data" name="data" placeholder="https://" required class="accordion-btn">';
    break;

case 'email':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Email:</label>
        <input type="email" id="data" name="data" placeholder="you@example.com" required class="accordion-btn"><br>
        <label for="subject" class="c2 dark-c4 fs20 fs32-M">Subject:</label>
        <input type="text" id="subject" name="subject" placeholder="Subject" class="accordion-btn"><br>
        <label for="body" class="c2 dark-c4 fs20 fs32-M">Body:</label>
        <textarea id="body" name="body" placeholder="Your message..." class="accordion-btn" rows="5"></textarea>
    `;
    break;

      case 'location':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Latitude:</label>
        <input type="text" id="data" name="data" placeholder="Enter latitude" required class="accordion-btn"><br>
        <label for="longitude" class="c2 dark-c4 fs20 fs32-M">Longitude:</label>
        <input type="text" id="longitude" name="longitude" placeholder="Enter longitude" required class="accordion-btn"><br>
        <label for="address" class="c2 dark-c4 fs20 fs32-M">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter address" class="accordion-btn">
    `;
    break;

      case 'phone':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="data" name="data" placeholder="+1-234-567-8900" required class="accordion-btn">
    `;
    break;

     case 'sms':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="data" name="data" placeholder="+1-234-567-8900" required class="accordion-btn"><br>
        <label for="message" class="c2 dark-c4 fs20 fs32-M">Message:</label>
        <textarea id="message" name="message" placeholder="Enter your message here" class="accordion-btn" rows="4" cols="50"></textarea>
    `;
    break;


     case 'whatsapp':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="data" name="data" placeholder="+1-234-567-8900" required class="accordion-btn"><br>
        <label for="message" class="c2 dark-c4 fs20 fs32-M">Message:</label>
        <textarea id="message" name="message" placeholder="Enter your message here" class="accordion-btn" rows="4" cols="50"></textarea>
    `;
    break;

      case 'skype':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Skype Username:</label>
        <input type="text" id="data" name="data" placeholder="Enter your Skype username" required class="accordion-btn"><br>
        <label for="action" class="c2 dark-c4 fs20 fs32-M">Action:</label>
        <select id="action" name="action" required class="accordion-btn">
            <option value="" disabled selected>Select action</option>
            <option value="call">Call</option>
            <option value="chat">Chat</option>
        </select>
    `;
    break;


      case 'zoom':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Meeting ID:</label>
        <input type="text" id="data" name="data" placeholder="Enter Meeting ID" required class="accordion-btn"><br>
        <label for="password" class="c2 dark-c4 fs20 fs32-M">Password:</label>
        <input type="text" id="password" name="password" placeholder="Enter Password" class="accordion-btn">
    `;
    break;

     case 'wifi':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">SSID:</label>
        <input type="text" id="data" name="data" placeholder="Enter SSID" required class="accordion-btn"><br>
        
        <label for="password" class="c2 dark-c4 fs20 fs32-M">Password:</label>
        <input type="text" id="password" name="password" placeholder="Enter Password" class="accordion-btn"><br>
        
        <label for="encryption" class="c2 dark-c4 fs20 fs32-M">Encryption:</label>
        <select id="encryption" name="encryption" required class="accordion-btn">
            <option value="">Select Encryption</option>
            <option value="WPA/WPA2">WPA/WPA2</option>
            <option value="WEP">WEP</option>
            <option value="NONE">None</option>
        </select><br>
    `;
    break;

case 'vcard':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">First Name:</label>
        <input type="text" id="data" name="data" placeholder="Enter First Name" required class="accordion-btn"><br>
        
        <label for="lastName" class="c2 dark-c4 fs20 fs32-M">Last Name:</label>
        <input type="text" id="lastName" name="lastName" placeholder="Enter Last Name" class="accordion-btn"><br>
        
        <label for="phone" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number" class="accordion-btn"><br>
        
        <label for="email" class="c2 dark-c4 fs20 fs32-M">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter Email" class="accordion-btn"><br>
        
        <label for="website" class="c2 dark-c4 fs20 fs32-M">Website:</label>
        <input type="url" id="website" name="website" placeholder="Enter Website" class="accordion-btn"><br>
        
        <label for="company" class="c2 dark-c4 fs20 fs32-M">Company:</label>
        <input type="text" id="company" name="company" placeholder="Enter Company" class="accordion-btn"><br>
        
        <label for="job" class="c2 dark-c4 fs20 fs32-M">Job Title:</label>
        <input type="text" id="job" name="job" placeholder="Enter Job Title" class="accordion-btn"><br>
        
        <label for="officePhone" class="c2 dark-c4 fs20 fs32-M">Office Phone:</label>
        <input type="tel" id="officePhone" name="officePhone" placeholder="Enter Office Phone" class="accordion-btn"><br>
        
        <label for="fax" class="c2 dark-c4 fs20 fs32-M">Fax:</label>
        <input type="text" id="fax" name="fax" placeholder="Enter Fax" class="accordion-btn"><br>
        
        <label for="address" class="c2 dark-c4 fs20 fs32-M">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter Address" class="accordion-btn"><br>
        
        <label for="postcode" class="c2 dark-c4 fs20 fs32-M">Post Code:</label>
        <input type="text" id="postcode" name="postcode" placeholder="Enter Post Code" class="accordion-btn"><br>
        
        <label for="city" class="c2 dark-c4 fs20 fs32-M">City:</label>
        <input type="text" id="city" name="city" placeholder="Enter City" class="accordion-btn"><br>
        
        <label for="state" class="c2 dark-c4 fs20 fs32-M">State:</label>
        <input type="text" id="state" name="state" placeholder="Enter State" class="accordion-btn"><br>
        
        <label for="country" class="c2 dark-c4 fs20 fs32-M">Country:</label>
        <input type="text" id="country" name="country" placeholder="Enter Country" class="accordion-btn">
    `;
    break;



  case 'paypal':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">PayPal Email:</label>
        <input type="email" id="data" name="data" placeholder="Enter PayPal Email" required class="accordion-btn" ><br>
        
        <label for="paypalAmount" class="c2 dark-c4 fs20 fs32-M">Amount:</label>
        <input type="number" id="paypalAmount" name="paypalAmount" placeholder="Enter Amount" min="0" step="0.01" required class="accordion-btn" ><br>
        
        <label for="paypalCurrency" class="c2 dark-c4 fs20 fs32-M">Currency:</label>
        <select id="paypalCurrency" name="paypalCurrency" required class="accordion-btn" >
            <option value="">Select Currency</option>
            <option value="USD">USD - United States Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - British Pound Sterling</option>
            <option value="AUD">AUD - Australian Dollar</option>
            <option value="CAD">CAD - Canadian Dollar</option>
            <option value="JPY">JPY - Japanese Yen</option>
            <option value="CHF">CHF - Swiss Franc</option>
            <option value="CNY">CNY - Chinese Yuan</option>
            <option value="SEK">SEK - Swedish Krona</option>
            <option value="NZD">NZD - New Zealand Dollar</option>
            <option value="KRW">KRW - South Korean Won</option>
            <option value="SGD">SGD - Singapore Dollar</option>
            <option value="NOK">NOK - Norwegian Krone</option>
            <option value="MXN">MXN - Mexican Peso</option>
            <option value="INR">INR - Indian Rupee</option>
        </select>
    `;
    break;


default:
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Text:</label>
        <textarea id="data" name="data" required class="accordion-btn" placeholder="Enter text"></textarea>
    `;
    break;

    }
});


document.querySelector('input[type="submit"]').addEventListener('click', function(event) {
    var inputs = document.querySelectorAll('#dataInput input:required, #dataInput select:required, #dataInput textarea:required');
    var allFilled = true;

    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            input.setCustomValidity('This field is required');
            input.reportValidity();
            allFilled = false;
        } else {
            input.setCustomValidity('');
        }
    });

    if (!allFilled) {
        event.preventDefault(); 
    }
});


  document.addEventListener('DOMContentLoaded', function() {
    var qrForm = document.getElementById('qrForm');
    var submitButton = document.querySelector('input[type="submit"]');

    var changesMade = false;

    function addInputEventListeners() {
        var inputFields = document.querySelectorAll('#dataInput input');
        var selectFields = document.querySelectorAll('#dataInput select');
        var textareaFields = document.querySelectorAll('#dataInput textarea');

        inputFields.forEach(function(inputField) {
            inputField.addEventListener('input', handleInputChange);
        });

        selectFields.forEach(function(selectField) {
            selectField.addEventListener('change', handleInputChange);
        });

        textareaFields.forEach(function(textareaField) {
            textareaField.addEventListener('input', handleInputChange);
        });
    }

    function handleInputChange() {
        changesMade = true;
        submitButton.disabled = false;
    }

    qrForm.addEventListener('submit', function(event) {
        event.preventDefault();

        if (changesMade) {
            handleFormSubmission();
        } else {
            submitButton.disabled = true;
        }
    });

    function handleFormSubmission() {
        var dataType = document.getElementById('dataType').value;
        var dataInputDiv = document.getElementById('dataInput');
        var dataValue = document.getElementById('data').value;

       
        console.log('Data Type:', dataType);
        console.log('Data Value:', dataValue);

        changesMade = false;
        submitButton.disabled = true;
    }

    document.getElementById('dataType').addEventListener('change', function() {
        var dataType = this.value;
        var dataInputDiv = document.getElementById('dataInput');
        dataInputDiv.innerHTML = ''; 
        switch (dataType) {


            case 'email':
                dataInputDiv.innerHTML = `
                    <label for="data" class="c2 dark-c4 fs20 fs32-M">Email:</label>
                    <input type="email" id="data" name="data" placeholder="you@example.com" required class="accordion-btn"><br>
                    <label for="subject" class="c2 dark-c4 fs20 fs32-M">Subject:</label>
                    <input type="text" id="subject" name="subject" placeholder="Subject" class="accordion-btn"><br>
                    <label for="body" class="c2 dark-c4 fs20 fs32-M">Body:</label>
                    <textarea id="body" name="body" placeholder="Your message..." class="accordion-btn" rows="5"></textarea>
                `;
                break;
case 'link':
    dataInputDiv.innerHTML = '<label for="data" class="c2 dark-c4 fs20 fs32-M ">Link:</label><input type="url" id="data" name="data" placeholder="https://" required class="accordion-btn">';
    break;

  case 'location':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Latitude:</label>
        <input type="text" id="data" name="data" placeholder="Enter latitude" required class="accordion-btn"><br>
        <label for="longitude" class="c2 dark-c4 fs20 fs32-M">Longitude:</label>
        <input type="text" id="longitude" name="longitude" placeholder="Enter longitude" required class="accordion-btn"><br>
        <label for="address" class="c2 dark-c4 fs20 fs32-M">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter address" class="accordion-btn">
    `;
    break;

      case 'phone':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="data" name="data" placeholder="+1-234-567-8900" required class="accordion-btn">
    `;
    break;

     case 'sms':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="data" name="data" placeholder="+1-234-567-8900" required class="accordion-btn"><br>
        <label for="message" class="c2 dark-c4 fs20 fs32-M">Message:</label>
        <textarea id="message" name="message" placeholder="Enter your message here" class="accordion-btn" rows="4" cols="50"></textarea>
    `;
    break;


     case 'whatsapp':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="data" name="data" placeholder="+1-234-567-8900" required class="accordion-btn"><br>
        <label for="message" class="c2 dark-c4 fs20 fs32-M">Message:</label>
        <textarea id="message" name="message" placeholder="Enter your message here" class="accordion-btn" rows="4" cols="50"></textarea>
    `;
    break;

      case 'skype':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Skype Username:</label>
        <input type="text" id="data" name="data" placeholder="Enter your Skype username" required class="accordion-btn"><br>
        <label for="action" class="c2 dark-c4 fs20 fs32-M">Action:</label>
        <select id="action" name="action" required class="accordion-btn">
            <option value="" disabled selected>Select action</option>
            <option value="call">Call</option>
            <option value="chat">Chat</option>
        </select>
    `;
    break;


      case 'zoom':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">Meeting ID:</label>
        <input type="text" id="data" name="data" placeholder="Enter Meeting ID" required class="accordion-btn"><br>
        <label for="password" class="c2 dark-c4 fs20 fs32-M">Password:</label>
        <input type="text" id="password" name="password" placeholder="Enter Password" class="accordion-btn">
    `;
    break;

     case 'wifi':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">SSID:</label>
        <input type="text" id="data" name="data" placeholder="Enter SSID" required class="accordion-btn"><br>
        
        <label for="password" class="c2 dark-c4 fs20 fs32-M">Password:</label>
        <input type="text" id="password" name="password" placeholder="Enter Password" class="accordion-btn"><br>
        
        <label for="encryption" class="c2 dark-c4 fs20 fs32-M">Encryption:</label>
        <select id="encryption" name="encryption" required class="accordion-btn">
            <option value="">Select Encryption</option>
            <option value="WPA/WPA2">WPA/WPA2</option>
            <option value="WEP">WEP</option>
            <option value="NONE">None</option>
        </select><br>
    `;
    break;

case 'vcard':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">First Name:</label>
        <input type="text" id="data" name="data" placeholder="Enter First Name" required class="accordion-btn"><br>
        
        <label for="lastName" class="c2 dark-c4 fs20 fs32-M">Last Name:</label>
        <input type="text" id="lastName" name="lastName" placeholder="Enter Last Name" class="accordion-btn"><br>
        
        <label for="phone" class="c2 dark-c4 fs20 fs32-M">Phone Number:</label>
        <input type="tel" id="phone" name="phone" placeholder="Enter Phone Number" class="accordion-btn"><br>
        
        <label for="email" class="c2 dark-c4 fs20 fs32-M">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter Email" class="accordion-btn"><br>
        
        <label for="website" class="c2 dark-c4 fs20 fs32-M">Website:</label>
        <input type="url" id="website" name="website" placeholder="Enter Website" class="accordion-btn"><br>
        
        <label for="company" class="c2 dark-c4 fs20 fs32-M">Company:</label>
        <input type="text" id="company" name="company" placeholder="Enter Company" class="accordion-btn"><br>
        
        <label for="job" class="c2 dark-c4 fs20 fs32-M">Job Title:</label>
        <input type="text" id="job" name="job" placeholder="Enter Job Title" class="accordion-btn"><br>
        
        <label for="officePhone" class="c2 dark-c4 fs20 fs32-M">Office Phone:</label>
        <input type="tel" id="officePhone" name="officePhone" placeholder="Enter Office Phone" class="accordion-btn"><br>
        
        <label for="fax" class="c2 dark-c4 fs20 fs32-M">Fax:</label>
        <input type="text" id="fax" name="fax" placeholder="Enter Fax" class="accordion-btn"><br>
        
        <label for="address" class="c2 dark-c4 fs20 fs32-M">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter Address" class="accordion-btn"><br>
        
        <label for="postcode" class="c2 dark-c4 fs20 fs32-M">Post Code:</label>
        <input type="text" id="postcode" name="postcode" placeholder="Enter Post Code" class="accordion-btn"><br>
        
        <label for="city" class="c2 dark-c4 fs20 fs32-M">City:</label>
        <input type="text" id="city" name="city" placeholder="Enter City" class="accordion-btn"><br>
        
        <label for="state" class="c2 dark-c4 fs20 fs32-M">State:</label>
        <input type="text" id="state" name="state" placeholder="Enter State" class="accordion-btn"><br>
        
        <label for="country" class="c2 dark-c4 fs20 fs32-M">Country:</label>
        <input type="text" id="country" name="country" placeholder="Enter Country" class="accordion-btn">
    `;
    break;



  case 'paypal':
    dataInputDiv.innerHTML = `
        <label for="data" class="c2 dark-c4 fs20 fs32-M">PayPal Email:</label>
        <input type="email" id="data" name="data" placeholder="Enter PayPal Email" required class="accordion-btn" ><br>
        
        <label for="paypalAmount" class="c2 dark-c4 fs20 fs32-M">Amount:</label>
        <input type="number" id="paypalAmount" name="paypalAmount" placeholder="Enter Amount" min="0" step="0.01" required class="accordion-btn" ><br>
        
        <label for="paypalCurrency" class="c2 dark-c4 fs20 fs32-M">Currency:</label>
        <select id="paypalCurrency" name="paypalCurrency" required class="accordion-btn" >
            <option value="">Select Currency</option>
            <option value="USD">USD - United States Dollar</option>
            <option value="EUR">EUR - Euro</option>
            <option value="GBP">GBP - British Pound Sterling</option>
            <option value="AUD">AUD - Australian Dollar</option>
            <option value="CAD">CAD - Canadian Dollar</option>
            <option value="JPY">JPY - Japanese Yen</option>
            <option value="CHF">CHF - Swiss Franc</option>
            <option value="CNY">CNY - Chinese Yuan</option>
            <option value="SEK">SEK - Swedish Krona</option>
            <option value="NZD">NZD - New Zealand Dollar</option>
            <option value="KRW">KRW - South Korean Won</option>
            <option value="SGD">SGD - Singapore Dollar</option>
            <option value="NOK">NOK - Norwegian Krone</option>
            <option value="MXN">MXN - Mexican Peso</option>
            <option value="INR">INR - Indian Rupee</option>
        </select>
    `;
    break;


            default:
                dataInputDiv.innerHTML = `
                    <label for="data" class="c2 dark-c4 fs20 fs32-M">Text:</label>
                    <textarea id="data" name="data" required class="accordion-btn" placeholder="Enter text"></textarea>
                `;
                break;
        }

        addInputEventListeners();
    });
});
