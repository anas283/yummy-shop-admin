function openCustomerForm() {
    window.open('./add-customer.php', '_SELF');
}

function goBack(evt) {
    evt.preventDefault();
    window.history.back();
}

var modalDetail = document.getElementById("modal-detail");
var span = document.getElementsByClassName("close")[0];

function openModalDetail(data) {
    document.getElementById('address').innerHTML = data[0];
    document.getElementById('address2').innerHTML = data[1];
    document.getElementById('city').innerHTML = data[2];
    document.getElementById('zip_code').innerHTML = "0" + data[3];
    document.getElementById('phone_number').innerHTML = "+60" + data[4];

    modalDetail.style.display = "block";
}

function closeModalDetail() {
    modalDetail.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modalDetail) {
        modalDetail.style.display = "none";
    }
}

function goToEdit() {
    window.open('./edit-customer.php', '_SELF');
}