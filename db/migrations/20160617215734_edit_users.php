<?php
use Phinx\Migration\AbstractMigration;

class EditUsers extends AbstractMigration
{
        public function change()
        {
                $table = $this->table('users');
                $table->changeColumn('lastlogin', 'datetime', ['null' => true])
                    ->update();
        }
}
