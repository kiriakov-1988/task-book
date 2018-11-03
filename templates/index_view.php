<?php
    // данная переменная получается из App\Controller\View::generate()
    if (count($data['listOfTasks'])):
?>

    <h1 class="h3 pt-4">Список задач:</h1>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>E-mail</th>
            <th style="max-width: 50%">Текст задачи</th>
            <th>Картинка</th>
            <th>Статус</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['listOfTasks'] as $row): ?>
            <tr>
                <th><?=$row['id']?></th>
                <td><?=$row['user_name']?></td>
                <td><?=$row['email']?></td>
                <td><?=$row['task_text']?></td>
                <td><?=$row['img']?></td>
                <td><?=$row['status']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <h1 class="h3 p-5 text-warning">На данный момент в базе еще нету загруженных данных !</h1>

<?php endif; ?>

<h2 class="h4 mt-5">Новая задача</h2>

<div class="row justify-content-center">

    <div class="col-8 p-3 border">
        <form enctype="multipart/form-data" method="post" action="/add-task">

            <div class="row">
                <div class="col-7 text-left">
                    <label for="user_name">Имя пользователя *</label>
                    <input type="text" id="user_name" name="user_name" maxlength="100" required />
                </div>
                <div class="col-5 text-right">
                    <label for="email">е-mail *</label>
                    <input type="email" id="email" name="email" maxlength="100" required />
                </div>
            </div>

            <div class="row my-2">
                <div class="col-3 text-left">
                    <label for="task_text">Текст задачи *</label>
                </div>
                <div class="col-9 text-right">
                    <textarea id="task_text" name="task_text" maxlength="255" required style="min-width: 99%"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-8 text-left">
                    <label for="userfile">
                        Выберите картинку:
                    </label>
                    <input type="file" name="userfile" id="userfile" accept="<?=CONFIG_MIME_TYPE?>" maxlength="100" />
                </div>
                <div class="col-4 text-right">
                    <input class="btn btn-success w-100" type="submit" value="Добавить" />
                </div>
            </div>

        </form>
    </div>

</div>