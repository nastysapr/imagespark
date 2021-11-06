<?php $pager = $pageData['pager'];?>

<nav aria-label="">
    <ul class="pagination">
        <li class="page-item">
            <a class="page-link" <?php if ($pager->current > 2) {?>
               href="?page=<?= $pager->current - 1; } ?>"><<</a>
        </li>
        <?php foreach ($pager->buttons as $button) {?>
            <li class="page-item <?php if($pager->current == $button) {?>active<?php }?>">
                <a class="page-link" href="?page=<?=$button?>"><?=$button?></a>
            </li>
        <?php }?>
        <li class="page-item">
            <a class="page-link" <?php if ($pager->current < $pager->totalPages) {?>
               href="?page=<?= $pager->current + 1; } ?>">>></a>
        </li>
    </ul>
</nav>