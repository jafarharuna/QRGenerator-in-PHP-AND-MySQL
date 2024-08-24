document.getElementById('qrForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    var inputs = document.querySelectorAll('#dataInput input, #dataInput select, #dataInput textarea');
    var allFilled = true;

    inputs.forEach(function(input) {
        if (!input.value) {
            input.setCustomValidity('This field is required');
            input.reportValidity();
            allFilled = false;
        } else {
            input.setCustomValidity('');
        }
    });

    if (!allFilled) {
        return;
    }

    var dataType = document.getElementById('dataType').value;
    var data = document.getElementById('data').value;
    var qrData = '';

    switch (dataType) {
        case 'link':
            qrData = data;
            break;
        case 'text':
            qrData = data;
            break;
        case 'email':
            var subject = document.getElementById('subject').value;
            var body = document.getElementById('body').value;
            qrData = `mailto:${data}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            break;
        case 'location':
            var longitude = document.getElementById('longitude').value;
            var address = document.getElementById('address').value;
            qrData = `http://maps.google.com/maps?f=q&q=${encodeURIComponent(data + ',' + longitude + '+(' + address + ')')}`;
            break;
        case 'phone':
            qrData = `tel:${data}`;
            break;
   case 'sms':
    var phoneNumber = document.getElementById('data').value;
    var message = document.getElementById('message').value;
    qrData = `smsto:${phoneNumber}:${encodeURIComponent(message)}`;
    break;


        case 'whatsapp':
            var message = document.getElementById('message').value;
            qrData = `https://wa.me/${data}?text=${encodeURIComponent(message)}`;
            break;
        case 'skype':
            var skypeUsername = document.getElementById('data').value.trim();
            var action = document.getElementById('action').value.trim();
            qrData = `skype:${skypeUsername}?${action}`;
            break;
        case 'zoom':
            var password = document.getElementById('password').value;
            qrData = `https://zoom.us/j/${data}?pwd=${encodeURIComponent(password)}`;
            break;
        case 'wifi':
            var wifiSSID = document.getElementById('data').value;
            var wifiPassword = document.getElementById('password').value;
            var encryptionType = document.getElementById('encryption').value;
            qrData = `WIFI:T:${encryptionType};S:${wifiSSID};P:${wifiPassword};;`;
            break;
        case 'vcard':
            var lastName = document.getElementById('lastName').value;
            var phone = document.getElementById('phone').value;
            var email = document.getElementById('email').value;
            var website = document.getElementById('website').value;
            var company = document.getElementById('company').value;
            var job = document.getElementById('job').value;
            var officePhone = document.getElementById('officePhone').value;
            var fax = document.getElementById('fax').value;
            var address = document.getElementById('address').value;
            var postcode = document.getElementById('postcode').value;
            var city = document.getElementById('city').value;
            var state = document.getElementById('state').value;
            var country = document.getElementById('country').value;
            qrData = `BEGIN:VCARD\nVERSION:2.1\nFN:${data} ${lastName}\nTEL;HOME:${phone}\nEMAIL:${email}\nURL:${website}\nORG:${company}\nTITLE:${job}\nTEL;WORK:${officePhone}\nTEL;FAX:${fax}\nADR;TYPE=work:${address};${postcode};${city};${state};${country}\nEND:VCARD`;
            break;
        case 'paypal':
            var paypalAmount = document.getElementById('paypalAmount').value;
            var paypalCurrency = document.getElementById('paypalCurrency').value;
            qrData = `https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=${data}&amount=${paypalAmount}&currency_code=${paypalCurrency}`;
            break;
        default:
            qrData = data;
    }

    var dotsColor = document.getElementById('dotsColor').value;
    var cornersSquareColor = document.getElementById('cornersSquareColor').value;
    var cornersDotColor = document.getElementById('cornersDotColor').value;
    var bgcolor = document.getElementById('bgcolor').value;
    var shape = document.getElementById('shape').value;
    var size = document.getElementById('size').value;
    var ecLevel = document.getElementById('ecLevel').value;
    var format = document.getElementById('format').value;
    var dotsStyle = document.getElementById('dotsStyle').value;
    var cornersSquareStyle = document.getElementById('cornersSquareStyle').value;
    var cornersDotStyle = document.getElementById('cornersDotStyle').value;
    var logoFile = document.getElementById('logo').files[0];
    var logoSize = parseFloat(document.getElementById('logoSize').value);
    var logoMargin = parseInt(document.getElementById('logoMargin').value);

    document.getElementById('qr-code').innerHTML = '';

    var typeNumber = getTypeNumber(qrData.length);

    var qrCode = new QRCodeStyling({
        width: getSizeValue(size),
        height: getSizeValue(size),
        data: qrData,
        qrOptions: {
            typeNumber: typeNumber,
            mode: 'Byte',
            errorCorrectionLevel: ecLevel
        },
        dotsOptions: {
            color: dotsColor, 
            type: dotsStyle
        },
        backgroundOptions: {
            color: bgcolor
        },
        cornersSquareOptions: {
            type: cornersSquareStyle,
            color: cornersSquareColor 
        },
        cornersDotOptions: {
            type: cornersDotStyle,
            color: cornersDotColor 
        },
        imageOptions: {
            crossOrigin: "anonymous",
            hideBackgroundDots: true, 
            imageSize: logoSize,
            margin: logoMargin
        }
    });

    if (logoFile) {
        var reader = new FileReader();
        reader.onload = function(event) {
            qrCode.update({
                image: event.target.result
            });
            qrCode.append(document.getElementById('qr-code')); 
        };
        reader.readAsDataURL(logoFile);
    } else {
        qrCode.append(document.getElementById('qr-code')); 
    }

    document.getElementById('downloadBtn').style.display = 'block'; 
    document.getElementById('qr-placeholder').style.display = 'none'; 
    document.getElementById('qr-code').style.display = 'block'; 
});

function getTypeNumber(dataLength) {
    if (dataLength <= 100) return 5; 
    else if (dataLength <= 200) return 10; 
    else if (dataLength <= 300) return 15; 
    else if (dataLength <= 400) return 20; 
    else return 25; 
}

function getSizeValue(size) {
    switch (size) {
        case 'small':
            return 128;
        case 'medium':
            return 256;
        case 'medium-large':
            return 384; 
        case 'large':
            return 512;
        default:
            return 256; 
    }
}

document.getElementById('downloadBtn').addEventListener('click', function() {
    var format = document.getElementById('format').value;
    var qrCodeCanvas = document.getElementById('qr-code').querySelector('canvas');
    if (qrCodeCanvas) {
        var dataUrl = qrCodeCanvas.toDataURL('image/' + format);
        var link = document.createElement('a');
        link.href = dataUrl;
        link.download = 'qrcode.' + format;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
});


