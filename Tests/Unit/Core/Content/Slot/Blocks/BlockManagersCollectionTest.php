<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the MIT License. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    MIT License
 *
 */

namespace RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Slot;

use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Tests\Unit\Core\Content\Base\ContentManagerBase;
use RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Slot\Blocks\BlockManagersCollection;

/**
 * BlockManagersCollectionTest
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class BlockManagersCollectionTest extends ContentManagerBase
{
    protected function setUp()
    {
        parent::setUp();

        $this->blocksManagerCollection = new BlockManagersCollection();
    }
    
    public function testFetchElementsFromTheCollection()
    {
        $this->assertEquals(0, $this->blocksManagerCollection->count());
        
        $blockManager1 = $this->createBlockManager($this->createBlock(2));
        $blockManager2 = $this->createBlockManager($this->createBlock(3));
        $this->blocksManagerCollection->addBlockManager($blockManager1);
        $this->blocksManagerCollection->addBlockManager($blockManager2);
        
        $this->assertEquals(2, $this->blocksManagerCollection->count());
        $this->assertEquals(array($blockManager1, $blockManager2), $this->blocksManagerCollection->getBlockManagers());
        $this->assertSame($blockManager1, $this->blocksManagerCollection->first());
        $this->assertSame($blockManager2, $this->blocksManagerCollection->last());
        $this->assertSame($blockManager2, $this->blocksManagerCollection->indexAt(1));
        $this->assertSame(array("index" => 0, "manager" => $blockManager1), $this->blocksManagerCollection->getManagerInfoByBlockId(2));
        $this->assertSame(array("index" => 1, "manager" => $blockManager2), $this->blocksManagerCollection->getManagerInfoByBlockId(3));
        $this->assertSame($blockManager2, $this->blocksManagerCollection->getBlockManager(3));
        $this->assertSame(0, $this->blocksManagerCollection->getBlockManagerIndex(2));
        
        $this->blocksManagerCollection->clear();
        $this->assertEquals(0, $this->blocksManagerCollection->count());
        $this->assertNull($this->blocksManagerCollection->getManagerInfoByBlockId(2));
    }
    
    public function testToArray()
    {   
        $blockManager = $this->createBlockManager(null);
        $this->blocksManagerCollection->addBlockManager($blockManager);
        
        $blockManager->expects($this->once())
             ->method('toArray')
             ->will($this->returnValue(array("foo" => "bar")));
        ;
        
        $this->assertEquals(array(array("foo" => "bar")), $this->blocksManagerCollection->toArray());
    }
    
    public function testInsertElementsAt()
    {   
        $blockManager1 = $this->createBlockManager(null);
        $blockManager2 = $this->createBlockManager(null);
        $this->blocksManagerCollection->addBlockManager($blockManager1);
        $this->blocksManagerCollection->addBlockManager($blockManager2);
        
        $blockManager3 = $this->createBlockManager(null);
        $parts = $this->blocksManagerCollection->insertAt($blockManager3, 1);
        
        $this->assertEquals(array($blockManager1, $blockManager3, $blockManager2), $this->blocksManagerCollection->getBlockManagers());
        $this->assertEquals(array("left" => array($blockManager1), "right" => array($blockManager2)), $parts);
        
        $blockManager4 = $this->createBlockManager(null);
        $parts = $this->blocksManagerCollection->insertAt($blockManager3, 0);
        $this->assertEquals(array($blockManager4, $blockManager1, $blockManager3, $blockManager2), $this->blocksManagerCollection->getBlockManagers());
        $this->assertEquals(array("left" => array(), "right" => array($blockManager1, $blockManager3, $blockManager2)), $parts);
        
        $blockManager5 = $this->createBlockManager(null);
        $parts = $this->blocksManagerCollection->insertAt($blockManager3, 4);
        $this->assertEquals(array($blockManager4, $blockManager1, $blockManager3, $blockManager2, $blockManager5), $this->blocksManagerCollection->getBlockManagers());
        $this->assertEquals(array("left" => array($blockManager4, $blockManager1, $blockManager3, $blockManager2), "right" => array()), $parts);
        
        return $this->blocksManagerCollection;
    }
    
    public function testRemoveElementsAt()
    {   
        $blockManager1 = $this->createBlockManager(null);
        $blockManager2 = $this->createBlockManager(null);
        $blockManager3 = $this->createBlockManager(null);
        $blockManager4 = $this->createBlockManager(null);
        $this->blocksManagerCollection->addBlockManager($blockManager1);
        $this->blocksManagerCollection->addBlockManager($blockManager2);
        $this->blocksManagerCollection->addBlockManager($blockManager3);
        $this->blocksManagerCollection->addBlockManager($blockManager4);
        
        $parts = $this->blocksManagerCollection->removeAt(2);        
        $this->assertEquals(array($blockManager1, $blockManager2, $blockManager4), $this->blocksManagerCollection->getBlockManagers());
        $this->assertEquals(array("left" => array($blockManager1, $blockManager2), "right" => array($blockManager4)), $parts);
        
        $parts = $this->blocksManagerCollection->removeAt(0);        
        $this->assertEquals(array($blockManager2, $blockManager4), $this->blocksManagerCollection->getBlockManagers());
        $this->assertEquals(array("left" => array(), "right" => array($blockManager2, $blockManager4)), $parts);
        
        $parts = $this->blocksManagerCollection->removeAt(1);        
        $this->assertEquals(array($blockManager2), $this->blocksManagerCollection->getBlockManagers());
        $this->assertEquals(array("left" => array($blockManager2), "right" => array()), $parts);
        
        $blockManager5 = $this->createBlockManager(null);
        $this->blocksManagerCollection->addBlockManager($blockManager5);
        
        $parts = $this->blocksManagerCollection->removeAt(0);        
        $this->assertEquals(array($blockManager5), $this->blocksManagerCollection->getBlockManagers());
        $this->assertEquals(array("left" => array(), "right" => array($blockManager5)), $parts);
    }
    
    private function createBlockManager($block = null)
    {
         $blockManager = 
            $this->getMockBuilder('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Core\Content\Block\ServiceBlock\BlockManagerService')
                 ->disableOriginalConstructor()
                 ->getMock()
         ;
         
         if (null !== $block) {
            $blockManager->expects($this->any())
               ->method('get')
               ->will($this->returnValue($block))
            ;
         }
         
         return $blockManager;
    }
    
    private function createBlock($id)
    {
         $block = $this->getMock('RedKiteLabs\RedKiteCms\RedKiteCmsBundle\Model\Block');
         $block->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id))
         ;
         
         return $block;
    }
}