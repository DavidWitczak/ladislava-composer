<?php

namespace Drupal\block_galerie\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\image\Entity\ImageStyle;

/**
 * Provides a 'GalerieBlock' block.
 *
 * @Block(
 *  id = "galerie_block",
 *  admin_label = @Translation("Galerie block"),
 * )
 */
class GalerieBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $output = $this->getPhotos();

    return $output;
  }

  public function getPhotos(){
    $output = [];
    $output['#theme'] = 'block_galerie';

    // filtres galerie basés sur la taxo
    $taxo = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('categorie_photo', 0, 1);

    $terms = [];
    foreach ($taxo as $term) {
      $terms[$term->tid] = $term->name;
    }

    $output['#var']['filter'] = $terms;

    // les photos
    $query = \Drupal::entityQuery('media');
    $query->condition('bundle', 'image');
    $query->condition('status', 1);
    $query->condition('field_categorie_photo', '', '<>');

    $photos_ids = $query->execute();

    $output['#var']['photos'] = $photos_ids;

    return $output;
  }

}
