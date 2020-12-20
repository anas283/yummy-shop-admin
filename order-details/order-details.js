var modalCancel = document.getElementById("modal-cancel");
var modalNote = document.getElementById("modal-note");
var modalPaid = document.getElementById("modal-paid");

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
}

function printOrder() {
    window.print();
}