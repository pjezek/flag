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
  public static $modules = ['flag', 'rules', 'entity'];

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
    $logger = RulesLog::logger();
    $logger->clear();

    $this->installEntitySchema('flag');

    $this->expressionManager = $this->container->get('plugin.manager.rules_expression');
  }

  /**
   * Tests passing a string context to a condition.
   */
  public function testActionFlag() {
    $rule = $this->expressionManager->createRule([
      'action_flag' => [
        'node' => [
          'type' => 'entity:node',
          'label' => 'Node',
        ],
      ],
    ]);

    $rule->addAction('rules_flag_flag');
    $rule->execute();

    
  }

}
