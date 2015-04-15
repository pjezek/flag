<?php

/**
 * @file
 * Contains \Drupal\flag\Tests\ActionFlagTest.
 */

namespace Drupal\flag\Tests;

use Drupal\rules\Engine\RulesLog;
use Drupal\simpletest\KernelTestBase;

/**
 * Tests the Flag Actions.
 *
 * @group flag
 */
class ActionFlagTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['flag', 'rules', 'entity', 'user', 'node', 'system'];

  /**
   * The expression plugin manager.
   *
   * @var \Drupal\rules\Engine\ExpressionPluginManager
   */
  protected $expressionManager;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->installEntitySchema('flagging');

    $this->installSchema('system', ['sequences']);
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');

    $this->expressionManager = $this->container->get('plugin.manager.rules_expression');
  }

  /**
   * Tests passing a string context to a condition.
   */
  public function testActionFlag() {
    $entity_manager = $this->container->get('entity.manager');
    $entity_manager->getStorage('node_type')
      ->create(['type' => 'page'])
      ->save();

    $node = $entity_manager->getStorage('node')
      ->create([
        'title' => 'test',
        'type' => 'page',
      ]);

    $action = $this->expressionManager->createAction('rules_flag_flag');
    $action->setContextValue('node', $node);
    $action->execute();
  }

}
