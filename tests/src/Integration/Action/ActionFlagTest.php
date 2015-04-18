<?php
/**
 * @file
 * Contains \Drupal\Tests\flag\Integration\Action\ActionFlagTest.
 */

namespace Drupal\Tests\flag\Integration\Action;

/**
 * Tests Class for flag_action_flag rules action plugin.
 * @coversDefaultClass \Drupal\flag\Plugin\Action\Flag
 * @group flag_action
 */
class ActionFlagTest extends FlagIntegrationTestBase {

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

  /**
   * Tests the execution of the flag action.
   *
   * @covers ::summary
   */
  public function testActionFlag() {

    $flagServiceMock = $this->getFlagServiceMock([]);
    $flagServiceMock
      ->expects($this->once())
      ->method('flag')
      ->will($this->returnValue([]));
    $this->container->set('flag', $flagServiceMock);

    $node = $this->getMock('Drupal\node\NodeInterface');

    $flag = $this->getFlagMock();

    $action = $this->getFlagAction();
    $action->setContextValue('entity', $node);
    $action->setContextValue('flag', $flag);
    $action->execute();
  }

  /**
   * Helper function to create a 'flag_action_flag' action.
   *
   * @return object
   */
  protected function getFlagAction() {
    return $this->actionManager->createInstance('flag_action_flag');
  }

}
