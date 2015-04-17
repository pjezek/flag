<?php
/**
 * @file
 * Contains the \Drupal\flag\Plugin\Action\Base class.
 */

namespace Drupal\flag\Plugin\Action;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\flag\FlagService;
use Drupal\rules\Core\RulesActionBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Base extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * @var FlagService $flagService
   */
  protected $flagService;

  /**
   * @param array       $configuration
   * @param string      $plugin_id
   * @param mixed       $plugin_definition
   * @param FlagService $flagService
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
   * @param array                                                     $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string                                                    $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed                                                     $plugin_definition
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
