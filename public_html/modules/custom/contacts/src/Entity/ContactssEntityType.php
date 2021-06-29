<?php

namespace Drupal\contacts\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Contactss entity type entity.
 *
 * @ConfigEntityType(
 *   id = "contactss_entity_type",
 *   label = @Translation("Contactss entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\contacts\ContactssEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\contacts\Form\ContactssEntityTypeForm",
 *       "edit" = "Drupal\contacts\Form\ContactssEntityTypeForm",
 *       "delete" = "Drupal\contacts\Form\ContactssEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\contacts\ContactssEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "contactss_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "contactss_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/contactss_entity_type/{contactss_entity_type}",
 *     "add-form" = "/admin/structure/contactss_entity_type/add",
 *     "edit-form" = "/admin/structure/contactss_entity_type/{contactss_entity_type}/edit",
 *     "delete-form" = "/admin/structure/contactss_entity_type/{contactss_entity_type}/delete",
 *     "collection" = "/admin/structure/contactss_entity_type"
 *   }
 * )
 */
class ContactssEntityType extends ConfigEntityBundleBase implements ContactssEntityTypeInterface {

  /**
   * The Contactss entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Contactss entity type label.
   *
   * @var string
   */
  protected $label;

}
