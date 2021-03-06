<?php include 'admin_block.php'?>

<?php
    // данная переменная получается из App\View\View::generate()
    if (count($data['listOfTasks'])):

        // в шаблоне наверно становится много логики, но это моя первая реализация сортировки и пагинации в разрезе MVC

        // данная переменная так же используется в шаблоне
        $page = $data['pagination']['currentPage'];

        // используется для добавления направления сортировки в отсортированом столбце
        if (isset($_SESSION['sorter']['colName']))
        {
            $colName = $_SESSION['sorter']['colName'];
        }  else {
            $colName = '';
        }

        // формирует соответствующий стиль для отсортированого столбца
        if (isset($_SESSION['sorter']['direction']))
        {
            $classStyle = 'sort-' . mb_strtolower($_SESSION['sorter']['direction']);
        }  else {
            $classStyle = '';
        }

        $direction = \App\Model\Session::getDirectionForThLink();

        // как вариант можно выдавать другое сообщение для отсортированого столбца
        $sortTitle = 'Кликните для сортировки по возрастанию, повторный клик сортирует в обратном порядке';
?>

    <h1 class="h3 pt-4">Список задач:</h1>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th><a href="/<?= ($page > 1) ? 'page-' . $page : '' ?>" title="Кликните для сбрасывания условий сортировки">ID</a></th>
            <th <?= ($colName == 'user')  ? 'class="' . $classStyle . '"' : '' ?> >
                <a href="/page-<?=$page?>/sort-user<?= ($colName == 'user')   ? $direction : '' ?>" title="<?=$sortTitle?>" >Имя</a>
            </th>
            <th <?= ($colName == 'email') ? 'class="' . $classStyle . '"' : '' ?> >
                <a href="/page-<?=$page?>/sort-email<?= ($colName == 'email') ? $direction : '' ?>" title="<?=$sortTitle?>" >E-mail</a>
            </th>
            <th>Текст задачи</th>
            <th>Картинка</th>
            <th <?= ($colName == 'status') ? 'class="' . $classStyle . '"' : '' ?> >
                <a href="/page-<?=$page?>/sort-status<?= ($colName == 'status') ? $direction : '' ?>" title="<?=$sortTitle?>" >Статус</a>
            </th>
            <?php if (isset($_SESSION['adminMarker'])): ?> <th>Действие</th> <?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['listOfTasks'] as $row): ?>
            <tr>
                <th><?=$row['id']?></th>
                <td><?=$row['user_name']?></td>
                <td><?=$row['email']?></td>
                <td><?=$row['task_text']?></td>
                <td><?php if (!empty($row['img'])): ?><img style="max-width: 160px" src="<?=CONFIG_UPLOAD_DIR.$row['img']?>"><?php endif; ?></td>
                <td><img style="max-width: 45px" src="/<?=$row['status']?>.png" alt="<?=$row['status']?>"></td>
                <?php if (isset($_SESSION['adminMarker'])): ?> <td><a href="/edit-<?=$row['id']?>" class="btn btn-outline-primary">Редактировать</a></td> <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php include 'pagination.php'?>

<?php else: ?>

    <h1 class="h3 p-5 text-warning">На данный момент в базе еще нету загруженных данных !</h1>

<?php endif; ?>

<?php include 'session-message.php'?>

<h2 class="h4 mt-5">Новая задача</h2>

<div id="preview" class="w-50"></div>

<div class="row justify-content-center pb-5 ь">

    <div class="col-9 p-3 border">
        <form enctype="multipart/form-data" method="post" action="/add-task">

            <div class="row">
                <div class="col-lg-7 text-left">
                    <label for="user_name">Имя пользователя *</label>
                    <input type="text" id="user_name" name="user_name" maxlength="100" required />
                </div>
                <div class="col-lg-5 text-lg-right">
                    <label for="email">е-mail *</label>
                    <input type="email" id="email" name="email" maxlength="100" required />
                </div>
            </div>

            <div class="row my-2">
                <div class="col-md-3 text-left">
                    <label for="task_text">Текст задачи *</label>
                </div>
                <div class="col-md-9 text-right">
                    <textarea id="task_text" name="task_text" maxlength="255" required style="min-width: 99%"></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7 text-left">
                    <label for="userfile">
                        Выберите картинку:
                    </label>
                    <input type="file" name="userfile" id="userfile" accept="<?=CONFIG_MIME_TYPE?>" maxlength="100" />
                </div>
                <div class="col-md-5 text-right">
                    <input class="btn btn-secondary" type="button" value="Предварительный просмотр" onclick="preview()" />
                </div>
            </div>

            <p class="text-center pt-2">
                <input class="btn btn-success w-25" type="submit" value="Добавить" />
            </p>

        </form>
    </div>

</div>