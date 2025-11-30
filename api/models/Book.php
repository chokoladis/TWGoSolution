<?php

namespace api\models;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string $author
 * @property int $published_year
 */
class Book extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author', 'published_year'], 'required', 'message' => 'Поле является обязательным'],
            [['published_year'], 'integer', 'message' => 'Поле должно быть целым числом'],
            [['title', 'author'], 'string', 'message' => 'Поле должно быть строкой'],
            [['title', 'author'], 'length' => [1, 255], 'message' => 'Поле принимает значение 1 до 255 символов']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'author' => 'Author',
            'published_year' => 'Published Year',
        ];
    }

}
