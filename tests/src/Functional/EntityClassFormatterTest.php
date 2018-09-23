<?php

namespace Drupal\Tests\entity_class_formatter\Functional;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\Tests\BrowserTestBase;

/**
 * @group entity_class_formatter
 */
class EntityClassFormatterTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'entity_class_formatter',
    'field',
    'filter',
    'node',
    'system',
    'text',
    'user',
  ];

  /**
   * Define test class for entity field.
   */
  const TEST_ENTITY_FIELD_CLASS = 'test-entity-field-class';

  /**
   * Define test class for string field.
   */
  const TEST_STRING_FIELD_CLASS = 'test-string-field-class';

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->drupalCreateContentType(['type' => 'page', 'name' => 'Basic page']);

    $account = $this->drupalCreateUser(['access content']);
    $this->drupalLogin($account);
  }

  /**
   * {@inheritdoc}
   */
  public function testEntityFieldClass() {
    $field_config = $this->createField('entity_reference');

    $node = $this->drupalCreateNode(['title' => self::TEST_ENTITY_FIELD_CLASS]);

    $entity = $this->drupalCreateNode([
      $field_config->getName() => [
        0 => ['target_id' => $node->id()],
      ],
    ]);
    $entity->save();

    $this->drupalGet($entity->toUrl());
    $assert_session = $this->assertSession();
    $assert_session->elementExists('css', '.node.' . self::TEST_ENTITY_FIELD_CLASS);
  }

  /**
   * {@inheritdoc}
   */
  public function testStringFieldClass() {
    $field_config = $this->createField('string');

    $entity = $this->drupalCreateNode([
      $field_config->getName() => [
        0 => ['value' => self::TEST_STRING_FIELD_CLASS],
      ],
    ]);
    $entity->save();

    $this->drupalGet($entity->toUrl());
    $assert_session = $this->assertSession();
    $assert_session->elementExists('css', '.node.' . self::TEST_STRING_FIELD_CLASS);
  }

  /**
   * Creates a field and sets the formatter.
   *
   * @param string $field_type
   *   The type of field.
   *
   * @return \Drupal\field\Entity\FieldConfig
   *   The newly created field.
   */
  protected function createField($field_type) {
    $entity_type = 'node';
    $bundle = 'page';
    $field_name = mb_strtolower($this->randomMachineName());

    $field_storage = FieldStorageConfig::create([
      'entity_type' => $entity_type,
      'field_name' => $field_name,
      'type' => $field_type,
      'cardinality' => FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED,
    ]);
    $field_storage->save();

    $field_config = FieldConfig::create([
      'field_storage' => $field_storage,
      'bundle' => $bundle,
    ]);
    $field_config->save();

    $display = EntityViewDisplay::create([
      'targetEntityType' => $entity_type,
      'bundle' => $bundle,
      'mode' => 'full',
      'status' => TRUE,
    ]);
    $display->setComponent($field_name, [
      'type' => 'entity_class_formatter',
      'settings' => [],
    ]);
    $display->save();

    return $field_config;
  }

}
