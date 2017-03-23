<?php

use Phinx\Migration\AbstractMigration;

class AddPrimaryGroup extends AbstractMigration
{
        public function change()
        {
                $table = $this->table('users');
                $table->addColumn('primarygroup', 'integer', ['null' => true])
                    ->update();
        }
}
