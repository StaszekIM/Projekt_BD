var add_to_cart = function (elem) {
    children = elem.parentNode.childNodes;
    children.forEach(function (x) {
        if (x.className == 'id') {
            let id = x.innerHTML;
            let request = new XMLHttpRequest();
            request.open('POST', '/add_to_cart.php');
            request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            request.onload = function () {
                if (request.readyState === 4) {
                    if (request.status === 200) {
                        alert("Success");
                    } else {
                        alert("Fail");
                    }
                }
            };
            request.send("id=" + id);
        }
    });
}

var delete_from_cart = function(elem, id) {
    let request = new XMLHttpRequest();
    request.open('GET', '/delete_from_cart.php?id=' + id);
    request.onload = function () {
        if (request.readyState === 4) {
            if (request.status === 200) {
                elem.parentElement.parentElement.remove();
            } else {
                alert("Fail");
            }
        }
    };
    request.send();
}