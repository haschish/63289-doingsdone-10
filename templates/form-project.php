<section class="content__side">
    <?=$categories_side?>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form" action="project-add.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>
            <?php $classname = isset($errors['name']) ? "form__input--error" : ""; ?>
            <input class="form__input <?= $classname; ?>" type="text" name="name" id="project_name" value="<?= getPostValue('name'); ?>" placeholder="Введите название проекта">
            <?php if (isset($errors['name'])) :?>
                <p class="form__message">
                    <?= $errors['name']; ?>
                </p>
            <?php endif ?>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
