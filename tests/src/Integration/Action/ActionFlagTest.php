<?php

/**
 * @file
 * Contains \Drupal\Tests\flag\Integration\Action.
 */

namespace Drupal\Tests\flag\Integration\Action;

use Drupal\Tests\rules\Integration\RulesEntityIntegrationTestBase;

/**
 * @coversDefaultClass \Drupal\flag\Plugin\Action\Flag
 * @group flag_action
 */
class ActionFlagTest extends RulesEntityIntegrationTestBase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->enableModule('flag', ['Drupal\\flag' => $this->root . '/modules/flag/src']);

    $flagtypeMock = $this->getMockBuilder('Drupal\flag\FlagTypePluginManager')
      ->disableOriginalConstructor()
      ->getMock();
    $this->container->set('plugin.manager.flag.flagtype', $flagtypeMock);

    $linktypeMock = $this->getMockBuilder('Drupal\flag\ActionLinkPluginManager')
      ->disableOriginalConstructor()
      ->getMock();
    $this->container->set('plugin.manager.flag.linktype', $linktypeMock);
  }

  /**
   * Tests the summary.
   *
   * @covers ::summary
   */
  public function testSummary() {
    $flagServiceMock = $this->getFlagServiceMock([]);
    $this->container->set('flag', $flagServiceMock);

    $action = $this->getFlagAction();

    $this->assertEquals('Flag entity', $action->summary());
  }

  public function testActionFlag() {

    $flagServiceMock = $this->getFlagServiceMock([]);
    $flagServiceMock
      ->expects($this->once())
      ->method('flag')
      ->will($this->returnValue([]));
    $this->container->set('flag', $flagServiceMock);

    $action = $this->getFlagAction();
    $node = $this->getMock('Drupal\node\NodeInterface');
    $action->setContextValue('entity', $node);
    $action->execute();
  }

  protected function getFlagServiceMock() {

    $flagServiceMock = $this->getMockBuilder('Drupal\flag\FlagService')
      ->disableOriginalConstructor()
      ->getMock();

      return $flagServiceMock;
    }

  protected function getFlagAction() {
    return $this->actionManager->createInstance('flag_action_flag');
  }
}
