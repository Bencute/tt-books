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
class Task extends ActiveRecord
{
    /**
     * @var int|null
     */
    public ?int $id = null;

    /**
     * @var string|null
     */
    public ?string $name = null;

    /**
     * @var string|null
     */
    public ?string $email = null;

    /**
     * @var string|null
     */
    public ?string $content = null;

    /**
     * @var string|null
     */
    public ?string $dateCreate = null;

    /**
     * @var bool
     */
    public bool $performed = false;

    /**
     * @var bool
     */
    public bool $updated = false;

    /**
     * @inheritDoc
     */
    public string $namePrimaryKey = 'id';

    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return 'task';
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return [
            'id',
            'name',
            'email',
            'content',
            'performed',
        ];
    }

    /**
     * @inheritDoc
     */
    public function getAttributesSave(): array
    {
        return [
            'name',
            'email',
            'content',
            'performed',
            'updated',
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

    public function isPerformed(): bool
    {
        return (bool) $this->performed;
    }

    public function isUpdated(): bool
    {
        return (bool) $this->updated;
    }

    public function setIsUpdated(): bool
    {
        $this->updated = true;
        return true;
    }

    public static function getFilters(): array
    {
        return [
            'idAsk' => [
                'attribute' => 'id',
                'order' => 'asc',
                'title' => 'titleIdAsk'
            ],
            'idDesc' => [
                'attribute' => 'id',
                'order' => 'desc',
                'title' => 'titleIdDesc'
            ],
            'nameAsk' => [
                'attribute' => 'name',
                'order' => 'asc',
                'title' => 'titleNameAsk'
            ],
            'nameDesc' => [
                'attribute' => 'name',
                'order' => 'desc',
                'title' => 'titleNameDesc'
            ],
            'emailAsk' => [
                'attribute' => 'email',
                'order' => 'asc',
                'title' => 'titleEmailAsk'
            ],
            'emailDesc' => [
                'attribute' => 'email',
                'order' => 'desc',
                'title' => 'titleEmailDesc'
            ],
            'performedAsk' => [
                'attribute' => 'performed',
                'order' => 'asc',
                'title' => 'titlePerformedAsk'
            ],
            'performedDesc' => [
                'attribute' => 'performed',
                'order' => 'desc',
                'title' => 'titlePerformedDesc'
            ],
        ];
    }
}