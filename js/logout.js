var logout = function() {
    let request = new XMLHttpRequest();
    request.open('GET', '/logout.php');
    request.onload = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                document.getElementById('BLogout').hidden = true;
                document.getElementById('BLogin').hidden = false;
            } else {
                alert("Fail during logout");
            }
        }
    };
    request.send();
}