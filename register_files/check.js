var check_password = function(arg) {
    if (typeof(arg) == "undefined") {
        if (document.getElementById('password2').value == '') return;
        if (document.getElementById('password').value.length - document.getElementById('password2').value.length > 2) return;
    }
    if (document.getElementById('password').value != document.getElementById('password2').value) {
        document.getElementById('password-match').hidden = false;
        document.getElementById('btn-register').disabled = true
    } else {
        document.getElementById('password-match').hidden = true;
        document.getElementById('btn-register').disabled = false;
    }
}

var check_email = function() {
    let val = document.getElementById('your_email').value;
    if (! /[^@]+@[^@]+.[a-zA-Z]{2,6}/.test(val)) {
        document.getElementById('email-note').innerHTML = 'Wrong email format';
        document.getElementById('email-note').hidden = false;
        return;
    }else {
        document.getElementById('email-note').hidden = true;
    }
    let request = new XMLHttpRequest();
    request.open('POST', 'https://bmp.mrysnik.site/is_email_valid.php');
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.onload = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                document.getElementById('email-note').hidden = true;
                return;
            } else {
                document.getElementById('email-note').innerHTML = 'Already in use';
                document.getElementById('email-note').hidden = false;
            }
        }
    };
    request.send("email=" + val);
}