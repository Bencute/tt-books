<?php


namespace frontend\helper;


/**
 * Interface IsbnProcessReportLineInterface
 * @package frontend\helper
 */
interface IsbnProcessReportLineInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id): self;

    /**
     * @return string
     */
    public function getDesc(): string;

    /**
     * @param string $desc
     * @return $this
     */
    public function setDesc(string $desc): self;

    /**
     * @return array
     */
    public function getResultFields(): array;

    /**
     * @param array $resultFields
     * @return $this
     */
    public function setResultFields(array $resultFields): self;

    /**
     * @param IsbnProcessReportLineIsbnInterface $isbnReport
     * @return $this
     */
    public function addIsbn(IsbnProcessReportLineIsbnInterface $isbnReport): self;

    /**
     * @return array
     */
    public function asArray(): array;

    /**
     * @param array $config
     * @return static
     */
    public static function fromArray(array $config): self;
}