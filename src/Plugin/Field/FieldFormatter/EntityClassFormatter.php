<?php

namespace Drupal\entity_class_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'entity_class_formatter'.
 *
 * @FieldFormatter(
 *   id = "entity_class_formatter",
 *   label = @Translation("Entity Class"),
 *   field_types = {
 *     "string",
 *     "list_string",
 *     "entity_reference"
 *   }
 * )
 */
class EntityClassFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // Instead of outputting the value on the page
    // we are inserting it as a class into the markup.
    return [];
  }

}
