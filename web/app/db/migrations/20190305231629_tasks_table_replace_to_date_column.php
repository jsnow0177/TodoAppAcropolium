<?php


use Phinx\Migration\AbstractMigration;

class TasksTableReplaceToDateColumn extends AbstractMigration
{

    public function change()
    {
        $this->table('tasks')
            ->renameColumn('to_date', 'created')
            ->update();
    }

}
