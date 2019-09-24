<?php if (count($tasks) > 1) : ?>
<?php $names = array_column($tasks, 'name'); ?>
Уважаемый, <?= $user ?>. На <?= $date ?> у вас запланированы задачи: <?= join(", ",$names) ?>.
<?php else : ?>
Уважаемый, <?= $user ?>. У вас запланирована задача <?= $tasks[0]['name'] ?> на <?= $date ?>
<?php endif ?>
