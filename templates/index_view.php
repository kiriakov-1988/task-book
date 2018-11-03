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