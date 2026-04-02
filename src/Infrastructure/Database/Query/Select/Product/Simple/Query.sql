WITH cte_posts AS (
	SELECT
		posts.ID AS id,
		posts.post_title AS name,
		posts.post_name AS slug

	FROM :posts AS posts

	JOIN :term_relationships AS term_relationships
		ON term_relationships.object_id = posts.ID
	JOIN :term_taxonomy AS term_taxonomy
		ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
	JOIN :terms AS terms
		ON terms.term_id = term_taxonomy.term_id

	WHERE posts.post_type = 'product'
		AND term_taxonomy.taxonomy = 'product_type'
		AND terms.slug = 'simple'
		:AND posts.ID
		:AND posts.post_status
),

cte_all_terms AS (
	SELECT
		term_relationships.object_id AS post_id,
		term_taxonomy.taxonomy,
		term_taxonomy.term_id,
		terms.slug AS term_slug

	FROM cte_posts

	JOIN :term_relationships AS term_relationships
		ON term_relationships.object_id = cte_posts.id
	JOIN :term_taxonomy AS term_taxonomy
		ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
	JOIN :terms AS terms
		ON terms.term_id = term_taxonomy.term_id

	WHERE term_taxonomy.taxonomy IN (
		'product_brand',
		'product_cat',
		'product_tag'
	)
		OR term_taxonomy.taxonomy LIKE 'pa_%'
),

cte_term_taxonomy AS (
	SELECT
		post_id,
		taxonomy,
		COALESCE(JSON_ARRAYAGG(
			JSON_OBJECT('id', term_id)
		), JSON_ARRAY()) AS terms

	FROM cte_all_terms

	WHERE taxonomy IN (
		'product_brand',
		'product_cat',
		'product_tag'
	)

	GROUP BY
		post_id,
		taxonomy
),

cte_pa AS (
	SELECT
		post_id,
		COALESCE(JSON_ARRAYAGG(
			JSON_OBJECT(
				'slug', slug,
				'terms', terms
			)
		), JSON_ARRAY()) AS attributes
	FROM (
		SELECT
			post_id,
			TRIM(LEADING 'pa_' FROM taxonomy) AS slug,
			COALESCE(JSON_ARRAYAGG(
				JSON_OBJECT('slug', term_slug)
			), JSON_ARRAY()) AS terms

		FROM cte_all_terms

		WHERE taxonomy LIKE 'pa_%'

		GROUP BY
			post_id,
			taxonomy
	) AS attributes

	GROUP BY
		post_id
),

cte_postmeta AS (
	SELECT
		post_id,
		CAST(MAX(CASE WHEN meta_key = '_price' THEN meta_value END) AS DECIMAL(10,2)) AS price,
		CAST(MAX(CASE WHEN meta_key = '_stock' THEN meta_value END) AS SIGNED)        AS stock,
		MAX(CASE WHEN meta_key = '_global_unique_id' THEN meta_value END)              AS global_unique_id,
		MAX(CASE WHEN meta_key = '_product_attributes' THEN meta_value END)            AS product_attributes

	FROM :postmeta

	JOIN cte_posts
		ON cte_posts.id = post_id

	WHERE meta_key IN (
		'_price',
		'_stock',
		'_global_unique_id',
		'_product_attributes'
	)

	GROUP BY
		post_id
)

SELECT
  COALESCE(JSON_ARRAYAGG(
    JSON_OBJECT(
      'id', id,
      'name', name,
      'path', path,
      'price', price,
      'stock', stock,
      'global_unique_id', global_unique_id,
      'product_attributes', product_attributes,
      'brands', brands,
      'categories', categories,
      'tags', tags,
      'attributes', attributes
    )
  ), JSON_ARRAY()) AS products
FROM (
  SELECT
    cte_posts.id,
    cte_posts.name,
    cte_posts.slug AS path,
    meta.price,
    meta.stock,
    meta.global_unique_id,
    meta.product_attributes,
    COALESCE(brands.terms, JSON_ARRAY())      AS brands,
    COALESCE(categories.terms, JSON_ARRAY())  AS categories,
    COALESCE(tags.terms, JSON_ARRAY())        AS tags,
    COALESCE(cte_pa.attributes, JSON_ARRAY()) AS attributes

  FROM cte_posts
  LEFT JOIN cte_postmeta AS meta
    ON meta.post_id = cte_posts.id

  LEFT JOIN cte_term_taxonomy AS brands
    ON brands.post_id = cte_posts.id
		AND brands.taxonomy = 'product_brand'
  LEFT JOIN cte_term_taxonomy AS categories
    ON categories.post_id = cte_posts.id
		AND categories.taxonomy = 'product_cat'
  LEFT JOIN cte_term_taxonomy AS tags
    ON tags.post_id = cte_posts.id
		AND tags.taxonomy = 'product_tag'
  LEFT JOIN cte_pa
    ON cte_pa.post_id = cte_posts.id
) AS json;