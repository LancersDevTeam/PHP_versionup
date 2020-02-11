<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PrefecturalsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PrefecturalsTable Test Case
 */
class PrefecturalsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\PrefecturalsTable
     */
    public $Prefecturals;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Prefecturals',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Prefecturals') ? [] : ['className' => PrefecturalsTable::class];
        $this->Prefecturals = TableRegistry::getTableLocator()->get('Prefecturals', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Prefecturals);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testValidate()
    {
        // 正常系
        $prefectural = $this->Prefecturals->newEntity([
            'id' => 1,
            'region_id' => 1,
            'name' => '北海道',
            'created' => '2019-09-04 12:27:03',
            'modified' => '2019-09-04 12:27:03'
        ]);
        $this->assertSame([], $prefectural->getErrors());

        // nameが空
        $prefectural = $this->Prefecturals->newEntity([
            'id' => 1,
            'region_id' => 1,
            'name' => '',
            'created' => '2019-09-04 12:27:03',
            'modified' => '2019-09-04 12:27:03'
        ]);
        $this->assertSame(["name" => ["_empty" => "入力してください", ]], $prefectural->getErrors());
    }
}
