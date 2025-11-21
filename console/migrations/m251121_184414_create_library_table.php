<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%library}}`.
 */
class m251121_184414_create_library_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%library}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_library_user_id', '{{%library}}', 'user_id');
        $this->addForeignKey('fk_library_user_id', '{{%library}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        $this->createIndex('idx_library_book_id', '{{%library}}', 'book_id');
        $this->addForeignKey('fk_library_book_id', '{{%library}}', 'book_id', '{{%book}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_library_book_id', '{{%library}}');
        $this->dropIndex('idx_library_book_id', '{{%library}}');

        $this->dropForeignKey('fk_library_user_id', '{{%library}}');
        $this->dropIndex('idx_library_user_id', '{{%library}}');

        $this->dropTable('{{%library}}');
    }
}
