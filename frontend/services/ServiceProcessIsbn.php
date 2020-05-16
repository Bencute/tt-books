<?php


namespace frontend\services;


use Exception;
use frontend\helper\Isbn;
use frontend\helper\IsbnProcessReport;
use frontend\helper\IsbnProcessReportLine;
use frontend\helper\IsbnProcessReportLineIsbn;
use frontend\model\Book;
use Sys;

/**
 * Class ServiceProcessIsbn
 * @package frontend\services
 */
class ServiceProcessIsbn
{
    /**
     * @return string
     */
    public static function getPathReport(): string
    {
        return Sys::getBaseDir() . '/web/reports';
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function process(): bool
    {
        $books = Book::findAllAsIterator([
            'expressions' => [
                'description_ru RLIKE \'\\\\d\\\\S{9,}(\\\\d|[xX])\''
            ],
        ]);

        $report = new IsbnProcessReport(self::getPathReport());
        $report->delete();

        foreach ($books as $book) {
            /** @var Book $book */
            $reportLine = (new IsbnProcessReportLine())
                ->setId($book->id)
                ->setDesc($book->descriptionRu);
            if (is_string($book->descriptionRu)) {
                $isbns = self::getIsbnsFromString($book->descriptionRu);
                if (!empty($isbns)) {
                    foreach ($isbns as $isbn) {
                        $fields = $book->getIssetIsbnFields($isbn);
                        $isbnReport = new IsbnProcessReportLineIsbn($isbn);
                        if (empty($fields)) {
                            if (Isbn::isValid($isbn)) {
                                $isbnReport->setValid(true)
                                    ->setWriteTo($book->addIsbn($isbn));
                            } else {
                                $isbnReport->setValid(false)
                                    ->setWriteTo($book->addWrongIsbn($isbn));
                            }
                            $book->save();
                        } else {
                            $isbnReport->setCompareFields($fields);
                        }
                        $reportLine->addIsbn($isbnReport);
                    }
                    $reportLine->setResultFields($book->asArray());
                    $report->add($reportLine);
                }
            }
        }
        return true;
    }

    /**
     * @param string $str
     * @return array
     * @throws Exception
     */
    private static function getIsbnsFromString(string $str): array
    {
        if (!mb_ereg_search_init($str, '\d\S{9,}(\d|[xX])'))
            throw new Exception('Dont init mb_ereg_search_init');

        $result = [];

        while ($value = mb_ereg_search_regs()) {
            $result[] = $value[0];
        }
        return $result;
    }
}