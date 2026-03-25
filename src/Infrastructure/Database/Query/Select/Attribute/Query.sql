SELECT
  woocommerce_attribute_taxonomies.attribute_label AS attribute_name,
  woocommerce_attribute_taxonomies.attribute_name AS attribute_slug,
  terms.name AS term_name,
  terms.slug AS term_slug

FROM :woocommerce_attribute_taxonomies AS woocommerce_attribute_taxonomies

JOIN :term_taxonomy AS term_taxonomy
  ON term_taxonomy.taxonomy = CONCAT('pa_', woocommerce_attribute_taxonomies.attribute_name)
JOIN :terms AS terms
  ON terms.term_id = term_taxonomy.term_id