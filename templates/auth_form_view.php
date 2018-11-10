<h1 class="h4 mt-5">Форма авторизации</h1>

<?php include 'session-message.php'?>

<div class="row justify-content-center">

    <div class="col-8 p-3 border">
        <form enctype="multipart/form-data" method="post" action="/authorize">

            <div class="row">
                <div class="col-lg-7 text-left">
                    <label for="user">Имя пользователя</label>
                    <input type="text" id="user" name="user" required />
                </div>
                <div class="col-lg-5 text-lg-right">
                    <label for="pass">Пароль</label>
                    <input type="password" id="pass" name="pass" required />
                </div>
            </div>

            <div class="text-center mt-2">
                <input class="btn btn-success w-25" type="submit" value="Авторизоваться" />
            </div>

        </form>
    </div>

</div>

<p class="m-3">
    <a href="<?=$_SERVER['HTTP_REFERER'] ?? '/'?>" class="btn btn-outline-primary">Вернуться на предыдущую страницу</a>
</p>