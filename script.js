document.getElementById("restreamForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "start_restreaming.php", true);
    xhr.onload = function () {
        if (xhr.status == 200) {
            alert(xhr.responseText);
        } else {
            alert("Error: " + xhr.status);
        }
    };
    xhr.send(formData);
});
