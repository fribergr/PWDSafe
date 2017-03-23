<?php

use Phinx\Migration\AbstractMigration;

class UserGroups extends AbstractMigration
{
        public function change()
        {
                $table = $this->table('usergroups');
                $table->addColumn('userid', 'integer')
                    ->addColumn('groupid', 'integer')
                    ->create();
        }
}
