var modalCancel = document.getElementById("modal-cancel");
var modalNote = document.getElementById("modal-note");
var modalPaid = document.getElementById("modal-paid");
var modalShipping = document.getElementById("modal-shipping");

function openModalCancel() {
    modalCancel.style.display = "block";
}
function closeModalCancel() {
    modalCancel.style.display = "none";
}

function openModalNote() {
    modalNote.style.display = "block";
}
function closeModalNote() {
    modalNote.style.display = "none";
}

function openModalPaid() {
    modalPaid.style.display = "block";
}
function closeModalPaid() {
    modalPaid.style.display = "none";
}

function openModalShipping() {
    modalShipping.style.display = "block";
}
function closeModalShipping() {
    modalShipping.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modalCancel) {
        modalCancel.style.display = "none";
    }
    if (event.target == modalNote) {
        modalNote.style.display = "none";
    }
    if (event.target == modalPaid) {
        modalPaid.style.display = "none";
    }
    if (event.target == modalShipping) {
        modalShipping.style.display = "none";
    }
}

function printOrder() {
    // hide side-nav and main-nav
    document.getElementById('mySidenav').style.display = "none";
    document.getElementById('main-nav').style.display = "none";
    
    // set content to full width
    document.getElementById('tabcontent').style.width = "90vw";
    document.getElementById('tabcontent').style.marginTop = "50px";
    document.getElementById('tabcontent').style.marginLeft = "-200px";

    window.print();

    setTimeout(function() {
        window.location.reload();
    },100)
}

function selectStatus() {
    document.getElementById('status-btn').click();
}

function validateForm() {
    var address = document.getElementById('address').value;
    var address2 = document.getElementById('address2').value;
    var city = document.getElementById('city').value;
    var state = document.getElementById('state').value;
    var zip_code = document.getElementById('zip_code').value;

    if(address == "") {
        document.getElementById('address-err').innerHTML = "Please enter address";
    }
    if(address2 == "") {
        document.getElementById('address-err').innerHTML = "Please enter address 2";
    }
    if(city == "") {
        document.getElementById('city-err').innerHTML = "Please enter city";
    }
    if(state == "") {
        document.getElementById('state-err').innerHTML = "Please enter state";
    }
    if(zip_code == "") {
        document.getElementById('zip-code-err').innerHTML = "Please enter zip code";
    }

    if(address != "" && address2 != "" && city != "" && state != "" && zip_code != "") {
        document.getElementById('shipping-btn').type = 'submit';
        document.getElementById('shipping-btn').click();
    }
}