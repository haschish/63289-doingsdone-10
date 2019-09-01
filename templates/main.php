<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
        <?php foreach ($projects as $project):?>
            <?php
            $url = $_SERVER['PHP_SELF'] . '?' . http_build_query(['category' => $project['id']]);
            $active_class = ($project['id'] == $category_id) ? 'main-navigation__list-item--active' : '';
            ?>

            <li class="main-navigation__list-item <?=$active_class?>">
                <a class="main-navigation__list-item-link" href="<?=$url?>"><?=strip_tags($project['name'])?></a>
                <span class="main-navigation__list-item-count"><?=$project['count']?></span>
            </li>
        <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
        href="pages/form-project.html" target="project_add">Добавить проект</a>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks == 1) print('checked'); ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach ($tasks as $task):
            if ($show_complete_tasks == 0 && $task['done']) {
                continue;
            }

            $statuses = [];
            if ($task['done']) {
                $statuses[] = 'task--completed';
            }
            if ( $task['date'] && isLessThan24HoursLeft($task['date']) && !$task['done']) {
                $statuses[] = 'task--important';
            }
        ?>
            <tr class="tasks__item task <?=implode(" ", $statuses)?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" <?=$task['done'] ? 'checked' : ''?>>
                        <span class="checkbox__text"><?=strip_tags($task['name'])?></span>
                    </label>
                </td>
                <td class="task__date"><?=strip_tags($task['date'])?></td>
                <td class="task__controls"></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
