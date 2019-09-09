<?php if ($user): ?>
    <a class="main-header__side-item button button--plus open-modal" href="add.php">Добавить задачу</a>

    <div class="main-header__side-item user-menu">
        <div class="user-menu__data">
            <p><?= $user['name']?></p>

            <a href="logout.php">Выйти</a>
        </div>
    </div>
<?php else: ?>
    <a class="main-header__side-item button button--transparent" href="login.php">Войти</a>
<?php endif ?>
