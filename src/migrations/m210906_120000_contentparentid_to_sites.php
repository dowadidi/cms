<?php

namespace craft\migrations;

use Craft;
use craft\db\Migration;
use craft\db\Table;

/**
 * m210906_120000_contentparentid_to_sites migration.
 */
class m210906_120000_contentparentid_to_sites extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(Table::SITES, 'contentParentId', $this->integer()->after('groupId')->null());
        $this->addForeignKey(null, Table::SITES, ['contentParentId'], Table::SITES, ['id'], 'SET NULL', null);
    }
}
