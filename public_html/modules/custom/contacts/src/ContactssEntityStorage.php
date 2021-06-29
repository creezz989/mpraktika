<?php

namespace Drupal\contacts;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class ContactssEntityStorage extends SqlContentEntityStorage implements ContactssEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ContactssEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {contactss_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {contactss_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ContactssEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {contactss_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('contactss_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
