var elem = document.getElementById("message");
if (elem) {
    setTimeout(function() {
        elem.parentNode.removeChild(elem);
    }, 5000);
}

var preview = function() {

    var status = true;

    if (document.getElementById('user_name').value === "") {
        document.getElementById('user_name').classList.add('border-danger');
        status = false;
    } else {
        document.getElementById('user_name').classList.remove('border-danger');
    }

    if (document.getElementById('email').value === "") {
        document.getElementById('email').classList.add('border-danger');
        status = false;
    } else {
        document.getElementById('email').classList.remove('border-danger');
    }

    if (document.getElementById('task_text').value === "") {
        document.getElementById('task_text').classList.add('border-danger');
        status = false;
    } else {
        document.getElementById('task_text').classList.remove('border-danger');
    }

    if (status) {
        var user_name = document.getElementById('user_name').value;
        var email     = document.getElementById('email').value;
        var task_text = document.getElementById('task_text').value;

        var preview  = "<p class=\"mt-1\"><i>Превью</i></p>";
            preview += "<div class=\"row mt-1\">";
            preview +=  "<div class=\"col-lg-7\">";
            preview +=      "<p><b>Имя:</b> " + user_name + "</p>";
            preview +=  "</div>";
            preview +=  "<div class=\"col-lg-5\">";
            preview +=      "<p><b>E-mail:</b> " + email + "</p>";
            preview +=  "</div>";
            preview += "</div>";
            preview += "<p class=\"my-2\"><b>Текст задачи:</b> " + task_text + "</p>";

        document.getElementById('preview').classList.add('border');
        document.getElementById('preview').classList.add('border-secondary');
        document.getElementById('preview').innerHTML = preview;

    }

};