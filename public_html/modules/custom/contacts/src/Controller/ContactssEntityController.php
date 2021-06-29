<?php

namespace Drupal\contacts\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\contacts\Entity\ContactssEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContactssEntityController.
 *
 *  Returns responses for Contactss entity routes.
 */
class ContactssEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Contactss entity revision.
   *
   * @param int $contactss_entity_revision
   *   The Contactss entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($contactss_entity_revision) {
    $contactss_entity = $this->entityTypeManager()->getStorage('contactss_entity')
      ->loadRevision($contactss_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('contactss_entity');

    return $view_builder->view($contactss_entity);
  }

  /**
   * Page title callback for a Contactss entity revision.
   *
   * @param int $contactss_entity_revision
   *   The Contactss entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($contactss_entity_revision) {
    $contactss_entity = $this->entityTypeManager()->getStorage('contactss_entity')
      ->loadRevision($contactss_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $contactss_entity->label(),
      '%date' => $this->dateFormatter->format($contactss_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Contactss entity.
   *
   * @param \Drupal\contacts\Entity\ContactssEntityInterface $contactss_entity
   *   A Contactss entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ContactssEntityInterface $contactss_entity) {
    $account = $this->currentUser();
    $contactss_entity_storage = $this->entityTypeManager()->getStorage('contactss_entity');

    $langcode = $contactss_entity->language()->getId();
    $langname = $contactss_entity->language()->getName();
    $languages = $contactss_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $contactss_entity->label()]) : $this->t('Revisions for %title', ['%title' => $contactss_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all contactss entity revisions") || $account->hasPermission('administer contactss entity entities')));
    $delete_permission = (($account->hasPermission("delete all contactss entity revisions") || $account->hasPermission('administer contactss entity entities')));

    $rows = [];

    $vids = $contactss_entity_storage->revisionIds($contactss_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\contacts\ContactssEntityInterface $revision */
      $revision = $contactss_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $contactss_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.contactss_entity.revision', [
            'contactss_entity' => $contactss_entity->id(),
            'contactss_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $contactss_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.contactss_entity.translation_revert', [
                'contactss_entity' => $contactss_entity->id(),
                'contactss_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.contactss_entity.revision_revert', [
                'contactss_entity' => $contactss_entity->id(),
                'contactss_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.contactss_entity.revision_delete', [
                'contactss_entity' => $contactss_entity->id(),
                'contactss_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['contactss_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
