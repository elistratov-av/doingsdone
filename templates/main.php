<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($projects as $prj): ?>
                <li class="main-navigation__list-item <?php if ($id == $prj['id']):?>main-navigation__list-item--active<?php endif;?>">
                    <a class="main-navigation__list-item-link" href="index.php?id=<?= $prj['id'] ?>"><?= esc($prj['name']) ?></a>
                    <span class="main-navigation__list-item-count"><?= task_count($tasks, $prj['name']) ?></span>
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
            <input class="checkbox__input visually-hidden show_completed" type="checkbox" <?php if ($show_complete_tasks):?>checked<?php endif;?>>
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

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
                        <span class="checkbox__text"><?= esc($task['title']) ?></span>
                    </label>
                </td>

                <td class="task__file">
                    <a class="download-link" href="#">Home.psd</a>
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
</main>
