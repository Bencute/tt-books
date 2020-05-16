<?php


namespace frontend\helper;


/**
 * Class IsbnProcessReportLineIsbn
 * @package frontend\helper
 */
class IsbnProcessReportLineIsbn implements IsbnProcessReportLineIsbnInterface
{
    /**
     * @var string
     */
    private string $isbn;
    /**
     * @var bool
     */
    private ?bool $valid = null;

    /**
     * @var string
     */
    private string $writeTo = '';

    /**
     * @var array
     */
    private array $compareFields = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(string $isbn)
    {
        $this->isbn = $isbn;
    }

    /**
     * {@inheritdoc}
     */
    public function setValid(?bool $state): self
    {
        $this->valid = $state;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setWriteTo(string $field): self
    {
        $this->writeTo = $field;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCompareFields(array $fields): self
    {
        $this->compareFields = $fields;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid(): ?bool
    {
        return $this->valid;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompareFields(): array
    {
        return $this->compareFields;
    }

    /**
     * {@inheritdoc}
     */
    public function getWriteTo(): string
    {
        return $this->writeTo;
    }

    /**
     * {@inheritdoc}
     */
    public function asArray(): array
    {
        return [
            'isbn' => $this->getIsbn(),
            'valid' => $this->isValid(),
            'write' => $this->getWriteTo(),
            'fields' => $this->getCompareFields(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $config): self
    {
        return (new static($config['isbn']))
            ->setValid($config['valid'])
            ->setWriteTo($config['write'])
            ->setCompareFields($config['fields']);
    }
}