<h2 class="content__side-heading">Проекты</h2>

<nav class="main-navigation">
    <ul class="main-navigation__list">
    <?php foreach ($projects as $project):?>
        <?php
        $url = 'index.php?' . http_build_query(['category' => $project['id']]);
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
