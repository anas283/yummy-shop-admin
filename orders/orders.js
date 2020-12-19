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

var modal = document.getElementById("myModal");
var span = document.getElementsByClassName("close")[0];

function openModal() {
    modal.style.display = "block";
}

function closeModal() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

function printOrder() {
    window.print();
}