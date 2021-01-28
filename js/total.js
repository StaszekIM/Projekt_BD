let table = document.getElementById("item_table");
table = table.childNodes;
table.forEach(function (row){ // For each table row
    let entries = row.childNodes;
    let price = 0;
    let amount = 0;
    let total_elem = null;
    entries.forEach(function (elem){ // Row elements - find price and amount
        if (elem.className == 'cart_price') {
            price = parseInt(elem.childNodes[1].childNodes[0].innerHTML);
        }else if (elem.className == 'cart_quantity') {
            elem.childNodes.forEach(function (i){
                if (i.className == 'cart_quantity_button') amount = parseInt(i.childNodes[3].value);
            })
        }else if (elem.className == 'cart_total'){
            total_elem = elem;
        }
    });
    if (total_elem != null) {
        total_elem.childNodes.forEach(function (elem){
            elem.innerHTML = price * amount;
        });
    }
});