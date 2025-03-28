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
   href="add-project.php" target="project_add">Добавить проект</a>
