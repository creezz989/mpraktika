<?php

namespace Drupal\contacts\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Contactss entity revision.
 *
 * @ingroup contacts
 */
class ContactssEntityRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Contactss entity revision.
   *
   * @var \Drupal\contacts\Entity\ContactssEntityInterface
   */
  protected $revision;

  /**
   * The Contactss entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $contactssEntityStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->contactssEntityStorage = $container->get('entity_type.manager')->getStorage('contactss_entity');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'contactss_entity_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => format_date($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.contactss_entity.version_history', ['contactss_entity' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $contactss_entity_revision = NULL) {
    $this->revision = $this->ContactssEntityStorage->loadRevision($contactss_entity_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->ContactssEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Contactss entity: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Contactss entity %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.contactss_entity.canonical',
       ['contactss_entity' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {contactss_entity_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.contactss_entity.version_history',
         ['contactss_entity' => $this->revision->id()]
      );
    }
  }

}
