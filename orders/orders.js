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

var modal = document.getElementById("modal-cancel");
var modal2 = document.getElementById("modal-note");
var modal3 = document.getElementById("modal-paid");
var span = document.getElementsByClassName("close")[0];

function openModalCancel() {
    modal.style.display = "block";
}

function closeModalCancel() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    if (event.target == modal2) {
        modal2.style.display = "none";
    }
    if (event.target == modal3) {
        modal3.style.display = "none";
    }
}

function openModalNote() {
    modal2.style.display = "block";
}

function closeModalNote() {
    modal2.style.display = "none";
}

function openModalPaid() {
    modal3.style.display = "block";
}

function closeModalPaid() {
    modal3.style.display = "none";
}

function printOrder() {
    window.print();
}