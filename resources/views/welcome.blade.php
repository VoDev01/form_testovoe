<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js'])
    <title>Форма тз</title>
</head>

<script type="module">
    $("#register_submit").on('click', function(e) {
        e.preventDefault();
        $.ajax({
            method: 'POST',
            url: 'post_register',
            dataType: 'json',
            data: $("#register_form").serialize(),
            success: function(data) {
                window.location.href = data.redirect;
            },
            error: function(data) {
                let all_errors = data.responseJSON.errors;
                $.each(all_errors, function(key, value) {
                    let error_text = document.createElement('span');
                    error_text.id = key + '-error';
                    error_text.classList.add('error');
                    error_text.style.color = "red";
                    error_text.innerHTML = " " + value[0];
                    let field_id = '#' + key;
                    $(field_id).after(error_text);
                });
            }
        });
    });
</script>

<body>
    <h1>Форма тестового задания</h1>
    <form method="POST" action="post_register" id="register_form">
        @csrf
        <div style="margin-bottom: 10px;">
            <label for="name">Имя</label>
            <input type="text" name="name" id="name">
        </div>
        <div style="margin-bottom: 10px;">
            <label for="phone">Телефон</label>
            <input type="tel" name="phone" id="phone">
        </div>
        <div style="margin-bottom: 10px;">
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
        </div>

        <button id="register_submit" type="submit">Отправить</button>
    </form>
</body>

</html>
