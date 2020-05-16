<?php


namespace frontend\helper;


/**
 * Class IsbnProcessReportLine
 * @package frontend\helper
 */
class IsbnProcessReportLine implements IsbnProcessReportLineInterface
{
    /**
     * @var string
     */
    private string $id = '';
    /**
     * @var string
     */
    private string $desc = '';
    /**
     * @var array
     */
    private array $resultFields = [];
    /**
     * @var array IsbnProcessReportLineIsbnInterface[]
     */
    private array $isbns = [];

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     * @return IsbnProcessReportLine
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * {@inheritdoc}
     * @return IsbnProcessReportLine
     */
    public function setDesc(string $desc): self
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultFields(): array
    {
        return $this->resultFields;
    }

    /**
     * {@inheritdoc}
     * @return IsbnProcessReportLine
     */
    public function setResultFields(array $resultFields): self
    {
        $this->resultFields = $resultFields;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @return IsbnProcessReportLine
     */
    public function addIsbn(IsbnProcessReportLineIsbnInterface $isbnReport): self
    {
        $this->isbns[] = $isbnReport;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getIsbns(): array
    {
        return $this->isbns;
    }

    /**
     * {@inheritdoc}
     */
    public function asArray(): array
    {
        $strIsbns = [];
        foreach ($this->getIsbns() as $isbn) {
            /** @var IsbnProcessReportLineIsbn $isbn */
            $strIsbns[$isbn->getIsbn()] = $isbn->asArray();
        }

        return [
            'id' => $this->getId(),
            'desc' => $this->getDesc(),
            'fields' => $this->getResultFields(),
            'isbns' => $strIsbns,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $config): self
    {
        $line = (new static())
            ->setId($config['id'])
            ->setDesc($config['id'])
            ->setResultFields($config['fields'])
            ->setResultFields($config['fields']);

        foreach ($config['isbns'] as $aIsbn) {
            $line->addIsbn(IsbnProcessReportLineIsbn::fromArray($aIsbn));
        }

        return $line;
    }
}