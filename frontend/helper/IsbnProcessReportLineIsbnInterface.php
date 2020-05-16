<?php


namespace frontend\helper;


/**
 * Interface IsbnProcessReportLineIsbnInterface
 * @package frontend\helper
 */
interface IsbnProcessReportLineIsbnInterface
{
    /**
     * IsbnProcessReportLineIsbnInterface constructor.
     * @param string $isbn
     */
    public function __construct(string $isbn);

    /**
     * @param bool $state
     * @return $this
     */
    public function setValid(bool $state): self;

    /**
     * @param string $field
     * @return $this
     */
    public function setWriteTo(string $field): self;

    /**
     * @param array $fields
     * @return $this
     */
    public function setCompareFields(array $fields): self;

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