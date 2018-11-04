<?php include 'admin_block.php'?>

<?php
    // данная переменная получается из App\View\View::generate()
    if (count($data['taskData'])):

        $taskData = $data['taskData'];
?>

<h1 class="h4 mt-5">Редактировать задачу № <?=$taskData['id']?></h1>

<?php include 'session-message.php'?>

<div class="row justify-content-center">

    <div class="col-8 p-3 border">
        <form enctype="multipart/form-data" method="post" action="/save-task">

            <?php if (!empty($taskData['img'])): ?>
                <img src="<?=CONFIG_UPLOAD_DIR.$taskData['img']?>">
            <?php else: ?>
                <p>Данная задача без картинки</p>
            <?php endif; ?>

            <input type="hidden" name="id" value="<?=$taskData['id']?>" />

            <div class="row">
                <div class="col-lg-7 text-left">
                    <label for="user_name">Имя пользователя</label>
                    <input type="text" id="user_name" disabled value="<?=$taskData['user_name']?>" />
                </div>
                <div class="col-lg-5 text-lg-right">
                    <label for="email">е-mail</label>
                    <input type="email" id="email" disabled value="<?=$taskData['email']?>" />
                </div>
            </div>

            <div class="row my-2">
                <div class="col-md-3 text-left">
                    <label for="task_text">Текст задачи *</label>
                </div>
                <div class="col-md-9 text-right">
                    <textarea id="task_text" name="task_text" maxlength="255" required style="min-width: 99%"><?=$taskData['task_text']?></textarea>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8 text-left">
                    <label for="status">Отметка о выполнении задачи: </label>
                    <input type="checkbox" id="status" name="status" <?php if ($taskData['status'] == 'completed'): ?>checked disabled<?php endif; ?> />
                </div>
                <div class="col-md-4 text-right">
                    <input class="btn btn-success w-100" type="submit" value="Сохранить" />
                </div>
            </div>

        </form>

    </div>

</div>

<p class="m-3">
    <a href="/" class="btn btn-outline-primary w-25">Вернуться к списку задач</a>
</p>

<?php else: ?>

    <!-- Вывод данного блока мало вероятен, разве что были какието другие изменения в базе, в момент выполнения этого скрипта -->
    <h1 class="h4 mt-5">Произошла ошибка при редактировании задачи</h1>

    <?php include 'session-message.php'?>

<?php endif; ?>
