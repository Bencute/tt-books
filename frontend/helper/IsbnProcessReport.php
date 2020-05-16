<?php


namespace frontend\helper;


use Exception;
use frontend\exceptions\ExceptionNotFoundReport;

/**
 * Class IsbnProcessReport
 * @package frontend\helper
 */
class IsbnProcessReport
{
    /**
     *
     */
    const FILENAME = 'report.txt';

    /**
     * @var string
     */
    private string $path;

    /**
     * IsbnProcessReport constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    private static function getNameFile()
    {
        return self::FILENAME;
    }

    /**
     * @param string $path
     * @return static
     * @throws ExceptionNotFoundReport
     */
    public static function load(string $path): self
    {
        $pathFile = $path . '/' . self::getNameFile();
        if (!file_exists($pathFile))
            throw new ExceptionNotFoundReport('Report not found');

        return new self($path);
    }

    /**
     * @return string
     */
    public function getPathFile(): string
    {
        return self::getPath() . '/' . self::getNameFile();
    }

    /**
     * @param IsbnProcessReportLineInterface $line
     * @throws Exception
     */
    public function add(IsbnProcessReportLineInterface $line): void
    {
        $this->checkPath();
        $strLine = json_encode($line->asArray());
        file_put_contents($this->getPathFile(), trim($strLine) . "\n", FILE_APPEND);
    }

    /**
     * @throws Exception
     */
    private function checkPath(): void
    {
        if (!file_exists($this->getPath()) && !mkdir($this->getPath(), 0777, true))
            throw new Exception('Unable to create report directory');
    }

    /**
     * @return string
     */
    private function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param int $start
     * @param int|null $count
     * @return IsbnProcessReportLinesIterator
     * @throws Exception
     */
    public function getLinesIterator(int $start = 0, ?int $count = null): IsbnProcessReportLinesIterator
    {
        return new IsbnProcessReportLinesIterator($this->getLines($start, $count));
    }

    /**
     * @return false|string
     */
    private function getText()
    {
        return file_get_contents($this->getPathFile());
    }

    /**
     * @return int
     */
    public function getCountLines(): int
    {
        return count($this->getLines());
    }

    /**
     * @param int $start
     * @param int|null $count
     * @return array
     */
    private function getLines(int $start = 0, ?int $count = null): array
    {
        if (empty(trim($this->getText())))
            return [];

        return array_slice(explode("\n", trim($this->getText())), $start, $count);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        if (!file_exists($this->getPathFile()))
            return true;

        return unlink($this->getPathFile());
    }
}