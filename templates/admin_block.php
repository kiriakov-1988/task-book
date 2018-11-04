<div class="text-right m-2">

        <?php   if (isset($_SESSION['adminMarker'])): ?>

            <p>Авторизованы, <a href="/log-out">выйти</a>!</p>

        <?php else: ?>

            <p><a href="/log-in">Авторизоваться</a></p>

        <?php endif; ?>

</div>


