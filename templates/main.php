<section class="content__side">
    <?=$categories_side?>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="GET" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="<?= getGetValue('search'); ?>" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="index.php?filter=all" class="tasks-switch__item <?= getFilterValue() == 'all' ? 'tasks-switch__item--active' : ''?>">Все задачи</a>
            <a href="index.php?filter=today" class="tasks-switch__item <?= getFilterValue() == 'today' ? 'tasks-switch__item--active' : ''?>">Повестка дня</a>
            <a href="index.php?filter=tomorrow" class="tasks-switch__item <?= getFilterValue() == 'tomorrow' ? 'tasks-switch__item--active' : ''?>">Завтра</a>
            <a href="index.php?filter=overdue" class="tasks-switch__item <?= getFilterValue() == 'overdue' ? 'tasks-switch__item--active' : ''?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks == 1) print('checked'); ?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <?php if (empty($tasks)): ?>
        <p>Ничего не найдено по вашему запросу</p>
    <?php else: ?>
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
                            <input class="checkbox__input visually-hidden" type="checkbox" <?=$task['done'] ? 'checked' : ''?> data-task-id="<?= $task['id'] ?>">
                            <span class="checkbox__text"><?=strip_tags($task['name'])?></span>
                        </label>
                    </td>
                    <td class="task__file">
                        <?php if ($task['file']): ?>
                            <a href="uploads/<?= $task['file'] ?>" class="download-link"></a>
                        <?php endif; ?>
                    </td>
                    <td class="task__date"><?=strip_tags($task['date'])?></td>
                    <td class="task__controls"></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</main>
