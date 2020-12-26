function setDefault() {
    document.getElementById("default").click();
}

function openOrderDetails(orderId) {
    window.open('../order-details/order-details.php?order_id='+orderId, '_self');
}

function openTabOrders(evt, tabName) {
    var i, tabcontent2, tablinks2;

    tabcontent2 = document.getElementsByClassName("tabcontent2");
    for(i=0;i<tabcontent2.length;i++) {
        tabcontent2[i].style.display = "none";
    }

    tablinks2 = document.getElementsByClassName("tablinks2");
    for(i=0;i<tablinks2.length;i++) {
        tablinks2[i].className = tablinks2[i].className.replace(" active2", "");
    }

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active2";
}

var modalOrder = document.getElementById("modal-order");
var span = document.getElementsByClassName("close")[0];

function openModalOrder() {
    modalOrder.style.display = "block";
}
function closeModalOrder() {
    modalOrder.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modalOrder) {
        modalOrder.style.display = "none";
    }
}

function validateForm() {
    var product = document.getElementById('product').value;
    var customer = document.getElementById('customer').value;
    var date = document.getElementById('date').value;
    var shipping = document.getElementById('shipping').value;
    var quantity = document.getElementById('quantity').value;

    if(product == "") {
        document.getElementById('product-err').innerHTML = "Please choose a product";
    }
    if(customer == "") {
        document.getElementById('customer-err').innerHTML = "Please choose a customer";
    }
    if(date == "") {
        document.getElementById('date-err').innerHTML = "Please choose a date";
    }
    if(shipping == "") {
        document.getElementById('shipping-err').innerHTML = "Please enter shipping cost";
    }
    if(quantity == "") {
        document.getElementById('shipping-err').innerHTML = "Please enter quantity";
    }

    if(product != "" && customer != "" && date != "" && shipping != "" && quantity != "") {
        document.getElementById('submit-btn').type = 'submit';
        document.getElementById('submit-btn').click();
    }
}