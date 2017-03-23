<?php

use Phinx\Migration\AbstractMigration;

class Groups extends AbstractMigration
{
        public function change()
        {
                $table = $this->table('groups');
                $table->addColumn('name', 'string')
                    ->addColumn('notes', 'text', ["null" => true])
                    ->create();
        }
}
