<?php

namespace Drupal\all_lives\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

/**
 * Class AllLivesController.
 */
class AllLivesController extends ControllerBase {

  /**
   * Alllives.
   *
   * @return array
   *   Return Hello string.
   */
  public function allLives() {
    $output = $this->getAllLives();

    return $output;
  }

  public function getAllLives() {
    $output = [];
    $output['#theme'] = 'all_lives';
    $now = new DrupalDateTime('now');

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'live');
    $query->condition('status', 1);

    $query->sort('field_date', 'ASC');

    $live_ids = $query->execute();

    $lives = Node::loadMultiple($live_ids);

    foreach ($lives as $key => $live) {
      $url = Url::fromRoute('entity.node.canonical', ['node' => $live->get('nid')->value], array("absolute" => TRUE))->toString();

      if ($live->get('field_date')->value < $now->getTimestamp()) {
        $output['#var']['live'][$key]['past'] = TRUE;
      }
      else {
        $output['#var']['live'][$key]['past'] = FALSE;
      }
      $output['#var']['live'][$key]['date'] = $live->get('field_date')->value;
      $output['#var']['live'][$key]['lieu'] = $live->get('field_lieu')->value;
      $output['#var']['live'][$key]['ville'] = $live->get('field_ville')->value;
      $output['#var']['live'][$key]['url'] = $url;
      $output['#var']['live'][$key]['map'] = $live->field_map->first()->getUrl()->toString();
    }

    return $output;
  }

}
