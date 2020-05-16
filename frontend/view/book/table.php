<?php

use system\view\View;
use system\helper\Pagination;
use frontend\helper\IsbnProcessReport;
use frontend\helper\IsbnProcessReportLine;
use frontend\helper\IsbnProcessReportLineIsbn;
use frontend\helper\FormHtmlPagination;

/**
 * @var View $this
 * @var array $lines
 * @var IsbnProcessReportLine $line
 * @var IsbnProcessReportLineIsbn $isbn
 * @var Pagination $pagination
 * @var IsbnProcessReport $report
 */
?>

<p class="lead text-center">Отчет изменен: <?=date ("Y-m-d H:i:s", filemtime($report->getPathFile()))?></p>
<?php if ($report->getCountLines()) { ?>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th class="align-middle" rowspan="2" scope="col">Id book</th>
            <th class="align-middle" rowspan="2" scope="col">Result fields</th>
            <th class="align-middle" colspan="4" scope="col">Found ISBN</th>
        </tr>
        <tr>
            <th class="align-middle" scope="col">ISBN</th>
            <th class="align-middle" scope="col">Valid</th>
            <th class="align-middle" scope="col">Wrote to field</th>
            <th class="align-middle" scope="col">Compare fields</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lines as $line) { ?>
            <?php foreach ($line->getIsbns() as $key => $isbn) { ?>
                <tr>
                    <?php if ($key == 0) { ?>
                        <?php $countIsbn = count($line->getIsbns()); ?>
                        <td rowspan="<?=$countIsbn?>"><?=$line->getId()?></td>
                        <td rowspan="<?=$countIsbn?>">
                            <pre><?=htmlentities(var_export($line->getResultFields(), true))?></pre>
                        </td>
                    <?php } ?>

                    <td><?=$isbn->getIsbn()?></td>
                    <td><?=$isbn->isValid() === null ? '' : ($isbn->isValid() ? 'valid' : 'invalid')?></td>
                    <td><?=$isbn->getWriteTo()?></td>
                    <td><?=implode(', ', $isbn->getCompareFields())?></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p class="lead text-center">В отчете строк не найдено</p>
<?php } ?>

<?php if ($pagination->getCountPage() > 1) { ?>
    <nav aria-label="Page navigation example" class="my-3">
        <ul class="pagination justify-content-center">
            <?php for ($i = 0; $i < $pagination->getCountPage(); $i++) { ?>
                <li class="page-item">
                    <a class="page-link js-pageLink" href="<?=FormHtmlPagination::getLink($i + 1)?>">
                        <?=$i + 1?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </nav>
<?php } ?>
