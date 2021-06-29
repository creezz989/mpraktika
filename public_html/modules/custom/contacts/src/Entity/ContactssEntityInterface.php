<?php

namespace Drupal\contacts\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Contactss entity entities.
 *
 * @ingroup contacts
 */
interface ContactssEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Contactss entity name.
   *
   * @return string
   *   Name of the Contactss entity.
   */
  public function getName();

  /**
   * Sets the Contactss entity name.
   *
   * @param string $name
   *   The Contactss entity name.
   *
   * @return \Drupal\contacts\Entity\ContactssEntityInterface
   *   The called Contactss entity entity.
   */
  public function setName($name);

  /**
   * Gets the Contactss entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Contactss entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Contactss entity creation timestamp.
   *
   * @param int $timestamp
   *   The Contactss entity creation timestamp.
   *
   * @return \Drupal\contacts\Entity\ContactssEntityInterface
   *   The called Contactss entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Contactss entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Contactss entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\contacts\Entity\ContactssEntityInterface
   *   The called Contactss entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Contactss entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Contactss entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\contacts\Entity\ContactssEntityInterface
   *   The called Contactss entity entity.
   */
  public function setRevisionUserId($uid);

}
