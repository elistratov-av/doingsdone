<section class="content__side">
    <?= include_template('projects.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'id' => $id,
        'projects' => $projects,
        'tasks' => $tasks
    ]) ?>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>

    <form class="form" action="add.php" method="post" enctype="multipart/form-data" autocomplete="off">
        <?php $classname = isset($errors["name"]) ? "form__input--error" : ""; ?>
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?= $classname ?>" type="text" name="name" id="name" value="<?= $task['name'] ?>" placeholder="Введите название">
            <p class="form__message"><?= $errors["name"] ?? '' ?></p>
        </div>

        <?php $classname = isset($errors["proj_id"]) ? "form__input--error" : ""; ?>
        <div class="form__row">
            <label class="form__label" for="proj_id">Проект <sup>*</sup></label>

            <select class="form__input form__input--select <?= $classname ?>" name="proj_id" id="proj_id">
                <?php foreach($projects as $proj): ?>
                    <option value="<?= $proj['id'] ?>" <?= $task['proj_id'] == $proj['id'] ? 'selected' : '' ?>><?= $proj['name'] ?></option>
                <?php endforeach; ?>
            </select>
            <p class="form__message"><?= $errors["proj_id"] ?? '' ?></p>
        </div>

        <?php $classname = isset($errors["date_end"]) ? "form__input--error" : ""; ?>
        <div class="form__row">
            <label class="form__label" for="date_end">Дата выполнения</label>

            <input class="form__input form__input--date <?= $classname ?>" type="text" name="date_end" id="date_end" value="<?= $task['date_end'] ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <p class="form__message"><?= $errors["date_end"] ?? '' ?></p>
        </div>

        <?php $classname = isset($errors["file"]) ? "form__input--error" : ""; ?>
        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file <?= $classname ?>">
                <input class="visually-hidden" type="file" name="file" id="file" value="">

                <label class="button button--transparent" for="file">
                    <span>Выберите файл</span>
                </label>
            </div>
            <p class="form__message"><?= $errors["file"] ?? '' ?></p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</main>
