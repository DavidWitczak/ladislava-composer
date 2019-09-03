<?php

namespace Drupal\block_live\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

/**
 * Provides a 'LiveBlock' block.
 *
 * @Block(
 *  id = "live_block",
 *  admin_label = @Translation("Live block"),
 * )
 */
class LiveBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $output = $this->getNextLive();

    return $output;
  }

  public function getNextLive() {
    $output = [];
    $output['#theme'] = 'block_live';
    $now = new DrupalDateTime('now');

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'live');
    $query->condition('status', 1);
    $query->condition('field_date', ($now->getTimestamp() + 7200), '>');

    $query->sort('field_date', 'ASC');
    $query->range(0, 6);

    $live_ids = $query->execute();

    $lives = Node::loadMultiple($live_ids);

    foreach ($lives as $key => $live) {
      $url = Url::fromRoute('entity.node.canonical', ['node' => $live->get('nid')->value], array("absolute" => TRUE))->toString();

      $output['#var']['live'][$key]['date'] = $live->get('field_date')->value;
      $output['#var']['live'][$key]['lieu'] = $live->get('field_lieu')->value;
      $output['#var']['live'][$key]['ville'] = $live->get('field_ville')->value;
      $output['#var']['live'][$key]['url'] = $url;
      if (!$live->get('field_map')->isEmpty()) {
        $output['#var']['live'][$key]['map'] = $live->field_map->first()->getUrl()->toString();
      }
    }

    return $output;
  }
}
