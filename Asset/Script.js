function removeMember(id) {
  if (!confirm("Remove this member?")) return;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../Controller/WorkspaceController.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    let data = JSON.parse(this.responseText);
    if (data.status === "success") {
      let row = document.getElementById("row" + id);
      if (row) {
        row.style.transition = "opacity 0.4s";
        row.style.opacity = "0";
        setTimeout(function () {
          row.remove();
        }, 400);
      }
    } else {
      alert("Error: " + (data.message || "Could not remove member"));
    }
  };

  xhr.onerror = function () {
    alert("Request failed. Please try again.");
  };

  xhr.send("action=delete&id=" + id);
}
