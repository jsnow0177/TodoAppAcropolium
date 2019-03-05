<?php


use Phinx\Migration\AbstractMigration;

class CreateTasksTable extends AbstractMigration
{

    public function change()
    {
        $this->table('tasks', ['id' => false, 'primary_key' => ['task_id', 'user_id']])
            ->addColumn('task_id', 'integer', [
                'signed' => false,
                'identity' => true
            ])
            ->addColumn('user_id', 'integer', [
                'signed' => false
            ])
            ->addColumn('title', 'string', [
                'length' => 255
            ])
            ->addColumn('body', 'text')
            ->addColumn('to_date', 'datetime')
            ->addColumn('status', 'set', [
                'values' => ['new', 'active', 'done'],
                'default' => 'new'
            ])
            ->create();
    }
}
