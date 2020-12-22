function openCustomerForm() {
    window.open('./add-customer.php', '_SELF');
}

function goBack(evt) {
    evt.preventDefault();
    window.history.back();
    // window.open('./customers.php', '_SELF');
}