<?php

use Phinx\Migration\AbstractMigration;

class OpenSslKeys extends AbstractMigration
{
        public function change()
        {
                $table = $this->table('users');
                $table->addColumn('pubkey', 'text', ['null' => true])
                    ->addColumn('privkey', 'text', ['null' => true])
                    ->update();
        }
}
