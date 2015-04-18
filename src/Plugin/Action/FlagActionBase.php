<?php
/**
 * @file
 * Contains \Drupal\flag\Plugin\Action\Base class.
 */

namespace Drupal\flag\Plugin\Action;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\flag\FlagService;
use Drupal\rules\Core\RulesActionBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FlagActionBase provides common methods for flag rules actions.
 */
class FlagActionBase extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * @var FlagService $flagService
   */
  protected $flagService;

  /**
   * Overrides \Drupal\Component\Plugin\ContextAwarePluginBase::__construct().
   *
   * Overrides the construction of context aware plugins to allow FlagService
   * been injected with dependency injection.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\flag\FlagService $flagService
   *   FlagService for working with flags injected with dependency injection.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FlagService $flagService) {
    $this->flagService = $flagService;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   *
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    /** @var FlagService $flagService */
    $flagService = $container->get('flag');
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $flagService
    );
  }
}
