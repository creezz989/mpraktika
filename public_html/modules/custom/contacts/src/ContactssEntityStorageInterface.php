<?php

namespace Drupal\contacts;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\contacts\Entity\ContactssEntityInterface;

/**
 * Defines the storage handler class for Contactss entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Contactss entity entities.
 *
 * @ingroup contacts
 */
interface ContactssEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Contactss entity revision IDs for a specific Contactss entity.
   *
   * @param \Drupal\contacts\Entity\ContactssEntityInterface $entity
   *   The Contactss entity entity.
   *
   * @return int[]
   *   Contactss entity revision IDs (in ascending order).
   */
  public function revisionIds(ContactssEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Contactss entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Contactss entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\contacts\Entity\ContactssEntityInterface $entity
   *   The Contactss entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ContactssEntityInterface $entity);

  /**
   * Unsets the language for all Contactss entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
