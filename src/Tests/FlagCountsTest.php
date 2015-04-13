<?php
/**
 * @file
 * Contains \Drupal\flag\Tests\FlagCountsTest.
 */

namespace Drupal\flag\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\node\Entity\Node;
use Drupal\flag\Entity\Flag;

/**
 * Tests the Flag counts API.
 *
 * @todo These are currently very basic, and simply test that the various count
 * functions return a sane value and don't crash.
 *
 * @group flag
 */
class FlagCountsTest extends WebTestBase {

  /**
   * Set to TRUE to strict check all configuration saved.
   *
   * @see \Drupal\Core\Config\Testing\ConfigSchemaChecker
   *
   * @var bool
   */
  protected $strictConfigSchema = FALSE;

  /**
   * The label of the flag to create for the test.
   *
   * @var string
   */
  protected $label = 'Test label 123';

  /**
   * The ID of the flag to create for the test.
   *
   * @var string
   */
  protected $id = 'test_label_123';

  /**
   * The flag link type.
   *
   * @var string
   */
  protected $flagLinkType;

  protected $flagConfirmMessage = 'Flag test label 123?';
  protected $unflagConfirmMessage = 'Unflag test label 123?';

  /**
   * User object.
   *
   * @var \Drupal\user\Entity\User|false
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('flag', 'node');

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->flagService = \Drupal::service('flag');

    // Create a flag.
    $this->flag = Flag::create([
      'id' => $this->id,
      'entity_type' => 'node',
      'types' => [
        'article',
      ],
      'flag_type' => 'flagtype_node',
      'link_type' => 'reload',
      'flagTypeConfig' => [],
      'linkTypeConfig' => [],
    ]);

    $this->flag->save();

    // Create a user who may flag.
    $this->adminUser = $this->drupalCreateUser([
      'administer flags',
    ]);

    // Create a node to flag.
    $this->node = Node::create([
      'body' => [
        [
          'value' => $this->randomMachineName(32),
          'format' => filter_default_format(),
        ]
      ],
      'type' => 'article',
      'title' => $this->randomMachineName(8),
    ]);
    $this->node->save();
  }

  /**
   * Tests that counts are kept in sync and can be retrieved.
   */
  public function testFlagCounts() {
    // Flag the node.
    $this->flagService->flag($this->flag, $this->node, $this->adminUser);

    // Check each of the count API functions.
    $flag_get_entity_flag_counts = flag_get_entity_flag_counts($this->flag, 'node');
    $this->assertEqual($flag_get_entity_flag_counts, 1, "flag_get_entity_flag_counts() returns the expected count.");

    $flag_get_user_flag_counts = flag_get_user_flag_counts($this->flag, $this->adminUser);
    $this->assertEqual($flag_get_user_flag_counts, 1, "flag_get_user_flag_counts() returns the expected count.");

    $flag_get_counts = flag_get_counts('node', $this->node->id());
    $this->assertEqual($flag_get_counts[$this->id], 1, "flag_get_counts() returns the expected count.");

    $flag_get_flag_counts = flag_get_flag_counts($this->id);
    $this->assertEqual($flag_get_flag_counts, 1, "flag_get_flag_counts() returns the expected count.");
  }

}
