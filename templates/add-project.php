<section class="content__side">
    <?= include_template('projects.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'id' => $id,
        'projects' => $projects,
        'tasks' => $tasks
    ]) ?>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form"  action="add-project.php" method="post" autocomplete="off">
        <div class="form__row">
            <label class="form__label" for="project_name">Название <sup>*</sup></label>

            <?php $classname = isset($errors["name"]) ? "form__input--error" : ""; ?>
            <input class="form__input" type="text" name="name" id="project_name" value="<?= $project['name'] ?>" placeholder="Введите название проекта">
            <p class="form__message"><?= $errors["name"] ?? '' ?></p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
