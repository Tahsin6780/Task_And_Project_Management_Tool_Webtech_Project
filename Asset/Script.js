function removeMember(id) {

let xhr = new XMLHttpRequest();

xhr.open("POST", "../Controller/WorkspaceController.php", true);

xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");

xhr.onload = function () {

    let data = JSON.parse(this.responseText);

    if (data.status == "success") {

document.getElementById("row" + id).style.display = "none";
    }
};

xhr.send(`action=delete&id=${id}`);
}