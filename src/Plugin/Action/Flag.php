<?php
/**
 * @file
 * Contains the \Drupal\flag\Plugin\Action\Flag class.
 */

namespace Drupal\flag\Plugin\Action;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\flag\FlagService;
use Drupal\rules\Core\RulesActionBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Entity;
use Drupal\flag\Entity\Flag as FlagEntity;

/**
 * Provides a generic 'flag' action.
 *
 * @Action(
 *   id = "flag_action_flag",
 *   label = @Translation("Flags the specifies the entity."),
 *   category = @Translation("Entity"),
 *   context = {
 *     "entity" = @ContextDefinition("entity",
 *       label = @Translation("Entity"),
 *       description = @Translation("Flags the specifies the entity.")
 *     )
 *   }
 * )
 *
 */
class Flag extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * @var FlagService $flagService
   */
  protected $flagService;

  /**
   * @var Entity $target
   */
  protected $target;

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

  /**
   * {@inheritdoc}
   */
  public function execute() {
    // user, comment, node, entity

    /** @var Entity $target */
    $target = $this->getContextValue('entity');

    $values = array();
    $entity = new FlagEntity($values, $target->getEntityType());
    $this->flagService->flag($entity, $target);
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {
    return $this->t('Flag entity');
  }


}
