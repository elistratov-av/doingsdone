<section class="content__side">
    <?= include_template('projects.php', [
    'show_complete_tasks' => $show_complete_tasks,
    'id' => $id,
    'projects' => $projects,
    'tasks' => $tasks
]) ?>
</section>

<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get" autocomplete="off">
        <input class="search-form__input" type="text" name="search" value="<?= esc($search) ?>" placeholder="Поиск по задачам">

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
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks):?>checked<?php endif;?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <?php if ($search && !count($tasks)): ?>
        <h2>Ничего не найдено по вашему запросу</h2>
    <?php else: ?>
    <table class="tasks">
        <?php foreach ($tasks as $task): ?>
            <?php
            if ($id && $task['proj_id'] != $id) continue;
            if ($task['completed'] && !$show_complete_tasks) continue;

            $completed = $task['completed'] ? 'task--completed' : '';
            $checked = $task['completed'] ? 'checked' : '';
            $important = !$task['completed'] && less_than_day($task['date_end']) ? 'task--important' : '';
            ?>
            <tr class="tasks__item task <?= $completed ?> <?= $important ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden task__checkbox" type="checkbox" value="1" <?= $checked ?>>
                        <span class="checkbox__text"><?= esc($task['name']) ?></span>
                    </label>
                </td>

                <td class="task__file">
                    <?php if ($task['file']): ?>
                    <a class="download-link" href="<?= $task['file'] ?>" download><?= esc(basename($task['file'])) ?></a>
                    <?php endif; ?>
                </td>

                <td class="task__date"><?= esc($task['date_end']) ?></td>

                <td class="task__controls">
                </td>
            </tr>
        <?php endforeach; ?>
        <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
        <!--                    <tr class="tasks__item task task--completed">-->
        <!--                        <td class="task__select">-->
        <!--                            <label class="checkbox task__checkbox">-->
        <!--                                <input class="checkbox__input visually-hidden" type="checkbox" checked>-->
        <!--                                <span class="checkbox__text">Записаться на интенсив "Базовый PHP"</span>-->
        <!--                            </label>-->
        <!--                        </td>-->
        <!--                        <td class="task__date">10.10.2019</td>-->
        <!---->
        <!--                        <td class="task__controls">-->
        <!--                        </td>-->
        <!--                    </tr>-->
    </table>
    <?php endif; ?>
</main>
