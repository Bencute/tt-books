<?php


namespace frontend\model;


use Exception;
use PDOException;
use Sys;
use system\db\ActiveRecord;
use system\exception\DbException;

/**
 * Class Task
 *
 * @package frontend\model
 */
class Book extends ActiveRecord
{
    /**
     * @var int|null
     */
    public ?int $id = null;

    /**
     * @var string|null
     */
    public ?string $titleRu = null;

    /**
     * @var string|null
     */
    public ?string $descriptionRu = null;

    /**
     * @var string|null
     */
    public ?string $isbn = null;

    /**
     * @var string|null
     */
    public ?string $isbn2 = null;

    /**
     * @var string|null
     */
    public ?string $isbn3 = null;

    /**
     * @var string|null
     */
    public ?string $isbn4 = null;

    /**
     * @var string|null
     */
    public ?string $isbnWrong = null;

    /**
     * @inheritDoc
     */
    public string $namePrimaryKey = 'id';

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'books';
    }

    /**
     * @inheritDoc
     */
    public function getAttributesSave(): array
    {
        return [
            'isbn',
            'isbn2',
            'isbn3',
            'isbn4',
            'isbnWrong',
        ];
    }

    /**
     * @inheritDoc
     */
    public function save(bool $validate = true): bool
    {
        Sys::getApp()->getDB()->beginTransaction();
        try {
            if (parent::save($validate) && Sys::getApp()->getDB()->commit()) {
                return true;
            } else {
                throw new Exception('SQL command execute error');
            }
        } catch (Exception | PDOException | DbException $e) {
            // TODO add check debug mode
            Sys::getApp()->getDB()->rollBack();
            throw $e;
        }

        return false;
    }

    /**
     * @throws Exception
     * @return bool
     */
    public function delete(): bool
    {
        Sys::getApp()->getDB()->beginTransaction();
        try {
            if (parent::delete() && Sys::getApp()->getDB()->commit()) {
                return true;
            } else {
                throw new Exception('SQL command execute error');
            }
        } catch (Exception $e) {
            // TODO add check debug mode
            Sys::getApp()->getDB()->rollBack();
            throw $e;
        }

        return false;
    }

    /**
     * @param string $isbn
     * @return array
     */
    public function getIssetIsbnFields(string $isbn): array
    {
        $buf = [];
        if ($isbn == $this->isbn) {
            $buf[] = 'isbn';
        }

        if ($isbn == $this->isbn2) {
            $buf[] = 'isbn2';
        }

        if ($isbn == $this->isbn3) {
            $buf[] = 'isbn3';
        }

        foreach (explode(",", $this->isbn4) as $itemIsbn) {
            if ($isbn == trim($itemIsbn)) {
                $buf[] = 'isbn4';
                break;
            }
        }

        foreach (explode(",", $this->isbnWrong) as $itemIsbn) {
            if ($isbn == trim($itemIsbn)) {
                $buf[] = 'isbn_wrong';
                break;
            }
        }

        return $buf;
    }

    /**
     * @param string $isbn
     * @return string
     */
    public function addIsbn(string $isbn): string
    {
        if (is_null($this->isbn) || empty($this->isbn)) {
            $this->isbn = $isbn;
            return 'isbn';
        }

        if (is_null($this->isbn2) || empty($this->isbn2)) {
            $this->isbn2 = $isbn;
            return 'isbn2';
        }

        if (is_null($this->isbn3) || empty($this->isbn3)) {
            $this->isbn3 = $isbn;
            return 'isbn3';
        }

        if (is_null($this->isbn4) || empty($this->isbn4)) {
            $this->isbn4 = $isbn;
        } else {
            $this->isbn4 = implode(",", [$this->isbn4, $isbn]);
        }
        return 'isbn4';
    }

    /**
     * @param string $isbn
     * @return string
     */
    public function addWrongIsbn(string $isbn): string
    {
        if (is_null($this->isbnWrong) || empty($this->isbnWrong)) {
            $this->isbnWrong = $isbn;
        } else {
            $this->isbnWrong = implode(",", [$this->isbnWrong, $isbn]);
        }
        return 'isbn_wrong';
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return [
            'id' => $this->id,
            'description_ru' => $this->descriptionRu,
            'title_ru' => $this->titleRu,
            'isbn' => $this->isbn,
            'isbn2' => $this->isbn2,
            'isbn3' => $this->isbn3,
            'isbn4' => $this->isbn4,
            'isbn_wrong' => $this->isbnWrong,
        ];
    }
}