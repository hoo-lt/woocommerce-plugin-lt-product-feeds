WITH cte_products AS (
	SELECT
		posts.ID AS id,
		posts.post_title AS name,
		posts.post_name AS slug

	FROM :posts AS posts

	JOIN :term_relationships AS term_relationships
		ON term_relationships.object_id = posts.ID
	JOIN :term_taxonomy AS term_taxonomy
		ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
		AND term_taxonomy.taxonomy = 'product_type'
	JOIN :terms AS terms
		ON terms.term_id = term_taxonomy.term_id
		AND terms.slug = 'simple'

	WHERE posts.post_type = 'product'
		:AND posts.post_status
		:AND posts.ID
),

cte_term_taxonomy AS (
	SELECT
		cte_products.id AS product_id,
		term_taxonomy.term_taxonomy_id,
		term_taxonomy.term_id,
		term_taxonomy.taxonomy

	FROM cte_products

	JOIN :term_relationships AS term_relationships
		ON term_relationships.object_id = cte_products.id
	JOIN :term_taxonomy AS term_taxonomy
		ON term_taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id
)

SELECT
	cte_products.id,
	cte_products.name,
	cte_products.slug AS path,

	price.meta_value AS price,
	stock.meta_value AS stock,
	global_unique_id.meta_value AS global_unique_id,

	brand.term_taxonomy_id AS brand_id,
	category.term_taxonomy_id AS category_id,
	tag.term_taxonomy_id AS tag_id,

	product_attributes.meta_value AS product_attributes,

	attribute.taxonomy AS attribute_slug,
	attribute.term_id AS term_id

FROM cte_products

JOIN :postmeta AS price
	ON price.post_id = cte_products.id
	AND price.meta_key = '_price'

LEFT JOIN :postmeta AS stock
	ON stock.post_id = cte_products.id
	AND stock.meta_key = '_stock'
LEFT JOIN :postmeta AS global_unique_id
	ON global_unique_id.post_id = cte_products.id
	AND global_unique_id.meta_key = '_global_unique_id'

LEFT JOIN cte_term_taxonomy AS brand
	ON brand.product_id = cte_products.id
	AND brand.taxonomy = 'product_brand'
LEFT JOIN cte_term_taxonomy AS category
	ON category.product_id = cte_products.id
	AND category.taxonomy = 'product_cat'
LEFT JOIN cte_term_taxonomy AS tag
	ON tag.product_id = cte_products.id
	AND tag.taxonomy = 'product_tag'

LEFT JOIN :postmeta AS product_attributes
	ON product_attributes.post_id = cte_products.id
	AND product_attributes.meta_key = '_product_attributes'

LEFT JOIN cte_term_taxonomy AS attribute
	ON attribute.product_id = cte_products.id
	AND attribute.taxonomy NOT IN (
		'product_brand',
		'product_cat',
		'product_tag'
	)