<?php

/**
 * @file
 * Contains contactss_entity.page.inc.
 *
 * Page callback for Contactss entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Contactss entity templates.
 *
 * Default template: contactss_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_contactss_entity(array &$variables) {
  // Fetch ContactssEntity Entity Object.
  $contactss_entity = $variables['elements']['#contactss_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
