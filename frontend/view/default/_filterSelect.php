<?php

use frontend\helper\FormHtmlFilter;

/**
 * @var View $this
 * @var array $tasks
 * @var string $currentFilterName
 * @var array $currentFilter
 */
?>

<?php if (!empty($tasks)) { ?>
    <div class="dropdown my-3">
        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
            Сортировка: <?=FormHtmlFilter::getTitleFilter($currentFilter)?>
        </button>
        <div class="dropdown-menu">
            <?php foreach (FormHtmlFilter::getListSupportFilters() as $nameFilter => $filter) { ?>
                <?php if ($currentFilterName === $nameFilter) { ?>
                    <a class="dropdown-item active" href="<?=FormHtmlFilter::getLink($nameFilter)?>">
                        <?=FormHtmlFilter::getTitleFilter($filter)?>
                    </a>
                <?php } else { ?>
                    <a class="dropdown-item" href="<?=FormHtmlFilter::getLink($nameFilter)?>">
                        <?=FormHtmlFilter::getTitleFilter($filter)?>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
<?php } ?>