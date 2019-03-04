<?php


use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{

    public function change()
    {
        $this->table('users', ['id' => 'user_id', 'signed' => false])
            ->addColumn('login', 'string', [
                'length' => 255
            ])
            ->addColumn('pass', 'string', [
                'length' => 255
            ])
            ->create();
    }

}
