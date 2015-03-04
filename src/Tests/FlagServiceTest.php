<?php

/**
 * @file
 * Contains \Drupal\flag\Tests\FlagServiceTest.
 */

namespace Drupal\flag\Tests;

use Drupal\flag\Entity\Flag;
use Drupal\simpletest\KernelTestBase;

/**
 * Tests the FlagService.
 *
 * @group flag
 */
class FlagServiceTest extends KernelTestBase {

  /**
   * Set to TRUE to strict check all configuration saved.
   *
   * @see \Drupal\Core\Config\Testing\ConfigSchemaChecker
   *
   * @var bool
   */
  protected $strictConfigSchema = FALSE;

  public static $modules = array(
    'flag',
    'node',
    'user',
  );

  /**
   * The flag service.
   *
   * @var \Drupal\flag\FlagService;
   */
  protected $flagService;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installConfig(array('flag'));

    $this->flagService = \Drupal::service('flag');
  }

  /**
   * Tests that flags once created can be retrieved.
   */
  public function testFlagServiceGetFlag() {
    $flag = Flag::create([
      'id' => 'test',
      'entity_type' => 'node',
      'types' => [
        'article',
      ],
      'flag_type' => 'flagtype_node',
      'link_type' => 'reload',
      'flagTypeConfig' => [],
      'linkTypeConfig' => [],
    ]);

    $flag->save();

    $result = $this->flagService->getFlags('node', 'article');
    $this->assertIdentical(count($result), 1, 'Found flag type');
  }

}
