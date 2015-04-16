<?php

/**
 * @file
 * Contains \Drupal\Tests\flag\Integration\Action.
 */

namespace Drupal\Tests\flag\Integration\Action;

use Drupal\Tests\rules\Integration\RulesIntegrationTestBase;

class ActionFlagTest extends RulesIntegrationTestBase {

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->enableModule('flag', ['Drupal\\flag' => $this->root . '/modules/flag/src']);
    $this->enableModule('node');

    $this->container->set('flag', $this->getFlagServiceMock([]));
  }

  public function testActionFlag() {
    $action = $this->actionManager->createInstance('rules_flag_flag');
    $node = $this->getMock('Drupal\node\NodeInterface');
    $action->setContextValue('node', $node);
    $action->execute();
  }

  protected function getFlagServiceMock($returnValue) {

    $flagServiceMock = $this->getMockBuilder('Drupal\flag\FlagService')
      ->disableOriginalConstructor()
      ->getMock();

    $flagServiceMock
      ->expects($this->once())
      ->method('flag')
      ->will($this->returnValue($returnValue));

      return $flagServiceMock;
    }
}
