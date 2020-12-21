function selectMenu() {
    var selectBox = document.getElementById('profile-menu');
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;

    if(selectedValue == 'logout') {
        window.open('../logout.php', '_SELF');
    }
}