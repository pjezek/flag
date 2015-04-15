<?php

/**
 * @file
 * Contains \Drupal\Tests\flag\Integration\Action.
 */

namespace Drupal\Tests\flag\Integration\Action;

use Drupal\Core\Action\ActionManager;
use Drupal\Core\Cache\NullBackend;
use Drupal\Core\TypedData\TypedDataManager;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\DependencyInjection\ContainerBuilder;

class ActionFlagTest extends UnitTestCase {

  /**
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * @var \Drupal\Core\Action\ActionManager
   */
  protected $actionManager;

  /**
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Array object keyed with module names and TRUE as value.
   *
   * @var \ArrayObject
   */
  protected $enabledModules;

  /**
   * @todo: refactor RulesIntegrationTestBase to reuse this
   */
  protected function setEnableModules() {
    // Register plugin managers used by Rules, but mock some unwanted
    // dependencies requiring more stuff to loaded.
    $this->moduleHandler = $this->getMockBuilder('Drupal\Core\Extension\ModuleHandlerInterface')
      ->disableOriginalConstructor()
      ->getMock();
    // Set all the modules as being existent.
    $this->enabledModules = new \ArrayObject();
    $this->enabledModules['flag'] = TRUE;
    $this->enabledModules['rules'] = TRUE;
    $this->enabledModules['rules_test'] = TRUE;
    $this->moduleHandler->expects($this->any())
      ->method('moduleExists')
      ->will($this->returnCallback(function ($module) {
        return [$module, $this->enabledModules[$module]];
      }));
  }
  /**
   * Fakes the enabling of a module and adds its namespace for plugin loading.
   *
   * Default behaviour works fine for core modules.
   *
   * @param string $name
   *   The name of the module that's gonna be enabled.
   * @param array $namespaces
   *   Map of the association between module's namespaces and filesystem paths.
   */
  protected function enableModule($name, array $namespaces = []) {
    $this->enabledModules[$name] = TRUE;

    if (empty($namespaces)) {
      $namespaces = ['Drupal\\' . $name => $this->root . '/core/modules/' . $name . '/src'];
    }
    foreach ($namespaces as $namespace => $path) {
      $this->namespaces[$namespace] = $path;
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->setEnableModules();
    $this->enableModule('node');

    $namespaces = new \ArrayObject([
      'Drupal\\flag' => $this->root . '/modules/flag/src',
      'Drupal\\rules' => $this->root . '/modules/rules/src',
      'Drupal\\Core\\TypedData' => $this->root . '/core/lib/Drupal/Core/TypedData',
      'Drupal\\Core\\Validation' => $this->root . '/core/lib/Drupal/Core/Validation',
    ]);

    $cacheBackend = new NullBackend('rules');

    $classResolver = $this->getMockBuilder('Drupal\Core\DependencyInjection\ClassResolverInterface')
      ->disableOriginalConstructor()
      ->getMock();

    $this->actionManager = new ActionManager(
      $namespaces,
      $cacheBackend,
      $this->moduleHandler
    );

    $this->typedDataManager = new TypedDataManager(
      $namespaces,
      $cacheBackend,
      $this->moduleHandler,
      $classResolver
    );

    $entityManager = $this->getMockBuilder('Drupal\Core\Entity\EntityManagerInterface')
      ->disableOriginalConstructor()
      ->getMock();


    $container = new ContainerBuilder();
    $container->set('flag', $this->getFlagServiceMock([]));
    $container->set('entity.manager', $entityManager);
    $container->set('plugin.manager.action', $this->actionManager);
    $container->set('typed_data_manager', $this->typedDataManager);
    \Drupal::setContainer($container);
    $this->container = $container;

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
