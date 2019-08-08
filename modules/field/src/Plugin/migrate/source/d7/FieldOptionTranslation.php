<?php

namespace Drupal\field\Plugin\migrate\source\d7;

/**
 * Gets field option label translations.
 *
 * @MigrateSource(
 *   id = "d7_field_option_translation",
 *   source_module = "i18n_field"
 * )
 */
class FieldOptionTranslation extends Field {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = parent::query();
    $query->leftJoin('i18n_string', 'i18n', 'i18n.type = fc.field_name');
    $query->leftJoin('locales_target', 'lt', 'lt.lid = i18n.lid');
    $query->condition('i18n.textgroup', 'field')
      ->condition('objectid', '#allowed_values')
      ->isNotNull('language');
    // Add all i18n and locales_target fields.
    $query
      ->fields('i18n')
      ->fields('lt');
    $query->addField('fc', 'type');
    $query->addField('fci', 'bundle');
    $query->addField('i18n', 'lid', 'i18n_lid');
    $query->addField('i18n', 'type', 'i18n_type');

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'bundle' => $this->t('Entity bundle'),
      'lid' => $this->t('Source string ID'),
      'textgroup' => $this->t('A module defined group of translations'),
      'context' => $this->t('Full string ID'),
      'objectid' => $this->t('Object ID'),
      'property' => $this->t('Object property for this string'),
      'objectindex' => $this->t('Integer value of Object ID'),
      'format' => $this->t('The input format used by this string'),
      'translation' => $this->t('Translation of the option'),
      'language' => $this->t('Language code'),
      'plid' => $this->t('Parent lid'),
      'plural' => $this->t('Plural index number in case of plural strings'),
      'i18n_status' => $this->t('A boolean indicating whether this translation needs to be updated'),
    ];
    return parent::fields() + $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return parent::getIds() +
      [
        'language' => ['type' => 'string'],
        'property' => ['type' => 'string'],
      ];
  }

}
