<?php


namespace frontend\helper;


use Exception;
use frontend\exceptions\ExceptionEmptyLines;
use Iterator;

class IsbnProcessReportLinesIterator implements Iterator
{
    /**
     * @var array
     */
    private array $lines;

    /**
     * IsbnProcessReportLinesIterator constructor.
     * @param array $lines
     * @throws Exception
     */
    public function __construct(array $lines)
    {
//        if (empty($lines))
//            throw new ExceptionEmptyLines('Empty lines passed');

        $this->lines = $lines;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return IsbnProcessReportLine::fromArray(
            json_decode(
                current($this->lines),
                true
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        next($this->lines);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->lines);
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return key($this->lines) !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        reset($this->lines);
    }
}