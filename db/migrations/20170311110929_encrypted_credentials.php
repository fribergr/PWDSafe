<?php

use Phinx\Migration\AbstractMigration;

class EncryptedCredentials extends AbstractMigration
{
        public function change()
        {
                $table = $this->table('encryptedcredentials');
                $table->addColumn('credentialid', 'integer')
                    ->addColumn('userid', 'integer')
                    ->addColumn('data', 'text')
                    ->create();
        }
}
