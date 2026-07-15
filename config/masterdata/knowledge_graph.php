<?php

declare(strict_types=1);

return
array (
  'edges' => 
  array (
    'ac712a25445b367d0265100b31ae28a3' => 
    array (
      'id' => 'ac712a25445b367d0265100b31ae28a3',
      'source' => 'business_roles',
      'target' => 'product_categories',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'category',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "category" to "product_categories" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '4dfd09e84d10cb9dd79f17736026908a' => 
    array (
      'id' => '4dfd09e84d10cb9dd79f17736026908a',
      'source' => 'buyer_segments',
      'target' => 'product_categories',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'category',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "category" to "product_categories" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '4d1d84c1623fa0c63c6ec447776d49e5' => 
    array (
      'id' => '4d1d84c1623fa0c63c6ec447776d49e5',
      'source' => 'buyer_segments',
      'target' => 'certifications',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'required_certifications',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "required_certifications" to "certifications" (certifications).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '2ae964d73d5a7e5f87d573a318e23aff' => 
    array (
      'id' => '2ae964d73d5a7e5f87d573a318e23aff',
      'source' => 'buyer_segments',
      'target' => 'certification_markets',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'typical_markets',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "typical_markets" to "certification_markets" (certification_markets).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '684e2cdf824909118f5dc9cdbd302446' => 
    array (
      'id' => '684e2cdf824909118f5dc9cdbd302446',
      'source' => 'buyer_segments',
      'target' => 'product_categories',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'typical_products',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "typical_products" to "products" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '5c4b5b31f8e67fd079bda9919dabbfb3' => 
    array (
      'id' => '5c4b5b31f8e67fd079bda9919dabbfb3',
      'source' => 'supplier_segments',
      'target' => 'product_categories',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'category',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "category" to "product_categories" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '7ecf0f96e9c8e598bf4b825aa6342996' => 
    array (
      'id' => '7ecf0f96e9c8e598bf4b825aa6342996',
      'source' => 'supplier_segments',
      'target' => 'business_roles',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'common_business_roles',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "common_business_roles" to "business_roles" (business_roles).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '62bf5f51eaf91ff6e8402087529622d4' => 
    array (
      'id' => '62bf5f51eaf91ff6e8402087529622d4',
      'source' => 'supplier_segments',
      'target' => 'buyer_segments',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'common_buyer_segments',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "common_buyer_segments" to "buyer_segments" (buyer_segments).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '96ab12842da1a87382cfb1a528607c76' => 
    array (
      'id' => '96ab12842da1a87382cfb1a528607c76',
      'source' => 'supplier_segments',
      'target' => 'certifications',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'common_certifications',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "common_certifications" to "certifications" (certifications).',
      ),
      'metadata' => 
      array (
      ),
    ),
    'f709a531095990aaf282dd6e64f47adc' => 
    array (
      'id' => 'f709a531095990aaf282dd6e64f47adc',
      'source' => 'supplier_segments',
      'target' => 'certification_markets',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'typical_markets',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "typical_markets" to "certification_markets" (certification_markets).',
      ),
      'metadata' => 
      array (
      ),
    ),
    'ab168061eb252e508947541bdad2b1e1' => 
    array (
      'id' => 'ab168061eb252e508947541bdad2b1e1',
      'source' => 'supplier_segments',
      'target' => 'product_categories',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'typical_products',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "typical_products" to "products" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '55dfff08e3c19f9812e60b4ee0c3fdbd' => 
    array (
      'id' => '55dfff08e3c19f9812e60b4ee0c3fdbd',
      'source' => 'certification_bodies',
      'target' => 'product_categories',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'category',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "category" to "product_categories" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    'cd33474229873769ecaeb8eeb99f2d2b' => 
    array (
      'id' => 'cd33474229873769ecaeb8eeb99f2d2b',
      'source' => 'certifications',
      'target' => 'product_categories',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'category',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "category" to "product_categories" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    'f44787bb8a445fc37e36b5e5706ddb87' => 
    array (
      'id' => 'f44787bb8a445fc37e36b5e5706ddb87',
      'source' => 'certifications',
      'target' => 'certification_markets',
      'relation' => 'many_to_many',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'recognized_markets',
        'collection' => true,
        'confidence' => 100,
        'reason' => 'Normalized "recognized_markets" to "certification_markets" (certification_markets).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '673698232f6d0b975377ce0c0fb6c5bf' => 
    array (
      'id' => '673698232f6d0b975377ce0c0fb6c5bf',
      'source' => 'certifications',
      'target' => 'certification_scopes',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'scope',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "scope" to "certification_scopes" (certification_scopes).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '030fa867caeb970530c093624e474429' => 
    array (
      'id' => '030fa867caeb970530c093624e474429',
      'source' => 'industry_segments',
      'target' => 'business_ecosystems',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'ecosystem',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "ecosystem" to "business_ecosystems" (business_ecosystems).',
      ),
      'metadata' => 
      array (
      ),
    ),
    'f3f3a38a62acfe3e8a4bd86394b0f5a4' => 
    array (
      'id' => 'f3f3a38a62acfe3e8a4bd86394b0f5a4',
      'source' => 'sustainability_tags',
      'target' => 'product_categories',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'category',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "category" to "product_categories" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
    '5e70f5cd1e619f3c8ccc5b58340adf6e' => 
    array (
      'id' => '5e70f5cd1e619f3c8ccc5b58340adf6e',
      'source' => 'technologies',
      'target' => 'product_categories',
      'relation' => 'belongs_to',
      'weight' => 1.0,
      'confidence' => 100.0,
      'collection' => false,
      'bidirectional' => false,
      'attributes' => 
      array (
        'field' => 'category',
        'collection' => false,
        'confidence' => 100,
        'reason' => 'Normalized "category" to "product_categories" (product_categories).',
      ),
      'metadata' => 
      array (
      ),
    ),
  ),
  'nodes' => 
  array (
    'business_ecosystems' => 
    array (
      'id' => 'business_ecosystems',
      'label' => 'Business ecosystems',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'business_ecosystems',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 5,
          'field_count' => 7,
          'reference_count' => 0,
          'type_count' => 7,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'color',
          2 => 'description',
          3 => 'icon',
          4 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'color' => 'string',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
        0 => '030fa867caeb970530c093624e474429',
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 1,
    ),
    'business_roles' => 
    array (
      'id' => 'business_roles',
      'label' => 'Business roles',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'business_roles',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 10,
          'field_count' => 12,
          'reference_count' => 1,
          'type_count' => 12,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'category',
          2 => 'color',
          3 => 'description',
          4 => 'downstream',
          5 => 'icon',
          6 => 'priority',
          7 => 'sustainability',
          8 => 'technologies',
          9 => 'upstream',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'category' => 'string',
          'color' => 'string',
          'description' => 'string',
          'downstream' => 'array',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
          'sustainability' => 'array',
          'technologies' => 'array',
          'upstream' => 'array',
        ),
        'references' => 
        array (
          'category' => 
          array (
            'field' => 'category',
            'target' => 'product_categories',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "category" to "product_categories" (product_categories).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
        0 => '7ecf0f96e9c8e598bf4b825aa6342996',
      ),
      'outgoing' => 
      array (
        0 => 'ac712a25445b367d0265100b31ae28a3',
      ),
      'degree' => 2,
    ),
    'buyer_segments' => 
    array (
      'id' => 'buyer_segments',
      'label' => 'Buyer segments',
      'type' => 'knowledge_node',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'buyer_segments',
        'type' => 'knowledge_node',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 8,
          'field_count' => 10,
          'reference_count' => 4,
          'type_count' => 10,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'category',
          1 => 'description',
          2 => 'icon',
          3 => 'preferred_sustainability',
          4 => 'priority',
          5 => 'required_certifications',
          6 => 'typical_markets',
          7 => 'typical_products',
        ),
        'types' => 
        array (
          'category' => 'string',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'preferred_sustainability' => 'array',
          'priority' => 'integer',
          'required_certifications' => 'array',
          'typical_markets' => 'array',
          'typical_products' => 'array',
        ),
        'references' => 
        array (
          'category' => 
          array (
            'field' => 'category',
            'target' => 'product_categories',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "category" to "product_categories" (product_categories).',
          ),
          'required_certifications' => 
          array (
            'field' => 'required_certifications',
            'target' => 'certifications',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "required_certifications" to "certifications" (certifications).',
          ),
          'typical_markets' => 
          array (
            'field' => 'typical_markets',
            'target' => 'certification_markets',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "typical_markets" to "certification_markets" (certification_markets).',
          ),
          'typical_products' => 
          array (
            'field' => 'typical_products',
            'target' => 'product_categories',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "typical_products" to "products" (product_categories).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
        0 => '62bf5f51eaf91ff6e8402087529622d4',
      ),
      'outgoing' => 
      array (
        0 => '4dfd09e84d10cb9dd79f17736026908a',
        1 => '4d1d84c1623fa0c63c6ec447776d49e5',
        2 => '2ae964d73d5a7e5f87d573a318e23aff',
        3 => '684e2cdf824909118f5dc9cdbd302446',
      ),
      'degree' => 5,
    ),
    'supplier_segments' => 
    array (
      'id' => 'supplier_segments',
      'label' => 'Supplier segments',
      'type' => 'knowledge_node',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'supplier_segments',
        'type' => 'knowledge_node',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 10,
          'field_count' => 12,
          'reference_count' => 6,
          'type_count' => 12,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'category',
          1 => 'common_business_roles',
          2 => 'common_buyer_segments',
          3 => 'common_certifications',
          4 => 'common_sustainability',
          5 => 'description',
          6 => 'icon',
          7 => 'priority',
          8 => 'typical_markets',
          9 => 'typical_products',
        ),
        'types' => 
        array (
          'category' => 'string',
          'common_business_roles' => 'array',
          'common_buyer_segments' => 'array',
          'common_certifications' => 'array',
          'common_sustainability' => 'array',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
          'typical_markets' => 'array',
          'typical_products' => 'array',
        ),
        'references' => 
        array (
          'category' => 
          array (
            'field' => 'category',
            'target' => 'product_categories',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "category" to "product_categories" (product_categories).',
          ),
          'common_business_roles' => 
          array (
            'field' => 'common_business_roles',
            'target' => 'business_roles',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "common_business_roles" to "business_roles" (business_roles).',
          ),
          'common_buyer_segments' => 
          array (
            'field' => 'common_buyer_segments',
            'target' => 'buyer_segments',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "common_buyer_segments" to "buyer_segments" (buyer_segments).',
          ),
          'common_certifications' => 
          array (
            'field' => 'common_certifications',
            'target' => 'certifications',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "common_certifications" to "certifications" (certifications).',
          ),
          'typical_markets' => 
          array (
            'field' => 'typical_markets',
            'target' => 'certification_markets',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "typical_markets" to "certification_markets" (certification_markets).',
          ),
          'typical_products' => 
          array (
            'field' => 'typical_products',
            'target' => 'product_categories',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "typical_products" to "products" (product_categories).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
        0 => '5c4b5b31f8e67fd079bda9919dabbfb3',
        1 => '7ecf0f96e9c8e598bf4b825aa6342996',
        2 => '62bf5f51eaf91ff6e8402087529622d4',
        3 => '96ab12842da1a87382cfb1a528607c76',
        4 => 'f709a531095990aaf282dd6e64f47adc',
        5 => 'ab168061eb252e508947541bdad2b1e1',
      ),
      'degree' => 6,
    ),
    'certification_bodies' => 
    array (
      'id' => 'certification_bodies',
      'label' => 'Certification bodies',
      'type' => 'knowledge_node',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'certification_bodies',
        'type' => 'knowledge_node',
        'statistics' => 
        array (
          'required_fields' => 1,
          'optional_fields' => 8,
          'field_count' => 9,
          'reference_count' => 1,
          'type_count' => 9,
        ),
        'required' => 
        array (
          0 => 'id',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'category',
          2 => 'country',
          3 => 'description',
          4 => 'name',
          5 => 'recognized_globally',
          6 => 'short_name',
          7 => 'website',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'category' => 'string',
          'country' => 'string',
          'description' => 'string',
          'id' => 'string',
          'name' => 'string',
          'recognized_globally' => 'boolean',
          'short_name' => 'string',
          'website' => 'string',
        ),
        'references' => 
        array (
          'category' => 
          array (
            'field' => 'category',
            'target' => 'product_categories',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "category" to "product_categories" (product_categories).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
        0 => '55dfff08e3c19f9812e60b4ee0c3fdbd',
      ),
      'degree' => 1,
    ),
    'certification_categories' => 
    array (
      'id' => 'certification_categories',
      'label' => 'Certification categories',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'certification_categories',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 5,
          'field_count' => 7,
          'reference_count' => 0,
          'type_count' => 7,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'color',
          2 => 'description',
          3 => 'icon',
          4 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'color' => 'string',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 0,
    ),
    'certification_markets' => 
    array (
      'id' => 'certification_markets',
      'label' => 'Certification markets',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'certification_markets',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 3,
          'field_count' => 5,
          'reference_count' => 0,
          'type_count' => 5,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'description',
          2 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'description' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
        0 => '2ae964d73d5a7e5f87d573a318e23aff',
        1 => 'f709a531095990aaf282dd6e64f47adc',
        2 => 'f44787bb8a445fc37e36b5e5706ddb87',
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 3,
    ),
    'certification_scopes' => 
    array (
      'id' => 'certification_scopes',
      'label' => 'Certification scopes',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'certification_scopes',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 3,
          'field_count' => 5,
          'reference_count' => 0,
          'type_count' => 5,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'description',
          2 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'description' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
        0 => '673698232f6d0b975377ce0c0fb6c5bf',
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 1,
    ),
    'certifications' => 
    array (
      'id' => 'certifications',
      'label' => 'Certifications',
      'type' => 'knowledge_node',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'certifications',
        'type' => 'knowledge_node',
        'statistics' => 
        array (
          'required_fields' => 1,
          'optional_fields' => 13,
          'field_count' => 14,
          'reference_count' => 3,
          'type_count' => 14,
        ),
        'required' => 
        array (
          0 => 'id',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'category',
          2 => 'description',
          3 => 'full_name',
          4 => 'issuer',
          5 => 'name',
          6 => 'recognized_markets',
          7 => 'renewal_years',
          8 => 'scope',
          9 => 'supports_esg',
          10 => 'supports_quality',
          11 => 'supports_sustainability',
          12 => 'supports_traceability',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'category' => 'string',
          'description' => 'string',
          'full_name' => 'string',
          'id' => 'string',
          'issuer' => 'string',
          'name' => 'string',
          'recognized_markets' => 'array',
          'renewal_years' => 'integer',
          'scope' => 'array',
          'supports_esg' => 'boolean',
          'supports_quality' => 'boolean',
          'supports_sustainability' => 'boolean',
          'supports_traceability' => 'boolean',
        ),
        'references' => 
        array (
          'category' => 
          array (
            'field' => 'category',
            'target' => 'product_categories',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "category" to "product_categories" (product_categories).',
          ),
          'recognized_markets' => 
          array (
            'field' => 'recognized_markets',
            'target' => 'certification_markets',
            'relation' => 'many_to_many',
            'collection' => true,
            'confidence' => 100,
            'reason' => 'Normalized "recognized_markets" to "certification_markets" (certification_markets).',
          ),
          'scope' => 
          array (
            'field' => 'scope',
            'target' => 'certification_scopes',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "scope" to "certification_scopes" (certification_scopes).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
        0 => '4d1d84c1623fa0c63c6ec447776d49e5',
        1 => '96ab12842da1a87382cfb1a528607c76',
      ),
      'outgoing' => 
      array (
        0 => 'cd33474229873769ecaeb8eeb99f2d2b',
        1 => 'f44787bb8a445fc37e36b5e5706ddb87',
        2 => '673698232f6d0b975377ce0c0fb6c5bf',
      ),
      'degree' => 5,
    ),
    'machinery_categories' => 
    array (
      'id' => 'machinery_categories',
      'label' => 'Machinery categories',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'machinery_categories',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 5,
          'field_count' => 7,
          'reference_count' => 0,
          'type_count' => 7,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'color',
          2 => 'description',
          3 => 'icon',
          4 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'color' => 'string',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 0,
    ),
    'product_applications' => 
    array (
      'id' => 'product_applications',
      'label' => 'Product applications',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'product_applications',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 5,
          'field_count' => 7,
          'reference_count' => 0,
          'type_count' => 7,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'color',
          2 => 'description',
          3 => 'icon',
          4 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'color' => 'string',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 0,
    ),
    'product_categories' => 
    array (
      'id' => 'product_categories',
      'label' => 'Product categories',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'product_categories',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 5,
          'field_count' => 7,
          'reference_count' => 0,
          'type_count' => 7,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'color',
          2 => 'description',
          3 => 'icon',
          4 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'color' => 'string',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
        0 => 'ac712a25445b367d0265100b31ae28a3',
        1 => '4dfd09e84d10cb9dd79f17736026908a',
        2 => '684e2cdf824909118f5dc9cdbd302446',
        3 => '5c4b5b31f8e67fd079bda9919dabbfb3',
        4 => 'ab168061eb252e508947541bdad2b1e1',
        5 => '55dfff08e3c19f9812e60b4ee0c3fdbd',
        6 => 'cd33474229873769ecaeb8eeb99f2d2b',
        7 => 'f3f3a38a62acfe3e8a4bd86394b0f5a4',
        8 => '5e70f5cd1e619f3c8ccc5b58340adf6e',
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 9,
    ),
    'product_statuses' => 
    array (
      'id' => 'product_statuses',
      'label' => 'Product statuses',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'product_statuses',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 5,
          'field_count' => 7,
          'reference_count' => 0,
          'type_count' => 7,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'color',
          2 => 'description',
          3 => 'icon',
          4 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'color' => 'string',
          'description' => 'string',
          'icon' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
      ),
      'degree' => 0,
    ),
    'industry_segments' => 
    array (
      'id' => 'industry_segments',
      'label' => 'Industry segments',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'industry_segments',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 4,
          'field_count' => 6,
          'reference_count' => 1,
          'type_count' => 6,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'description',
          2 => 'ecosystem',
          3 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'description' => 'string',
          'ecosystem' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
          'ecosystem' => 
          array (
            'field' => 'ecosystem',
            'target' => 'business_ecosystems',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "ecosystem" to "business_ecosystems" (business_ecosystems).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
        0 => '030fa867caeb970530c093624e474429',
      ),
      'degree' => 1,
    ),
    'sustainability_tags' => 
    array (
      'id' => 'sustainability_tags',
      'label' => 'Sustainability tags',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'sustainability_tags',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 4,
          'field_count' => 6,
          'reference_count' => 1,
          'type_count' => 6,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'category',
          2 => 'description',
          3 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'category' => 'string',
          'description' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
          'category' => 
          array (
            'field' => 'category',
            'target' => 'product_categories',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "category" to "product_categories" (product_categories).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
        0 => 'f3f3a38a62acfe3e8a4bd86394b0f5a4',
      ),
      'degree' => 1,
    ),
    'technologies' => 
    array (
      'id' => 'technologies',
      'label' => 'Technologies',
      'type' => 'lookup',
      'confidence' => 100.0,
      'attributes' => 
      array (
        'schema' => 'technologies',
        'type' => 'lookup',
        'statistics' => 
        array (
          'required_fields' => 2,
          'optional_fields' => 4,
          'field_count' => 6,
          'reference_count' => 1,
          'type_count' => 6,
        ),
        'required' => 
        array (
          0 => 'id',
          1 => 'label',
        ),
        'optional' => 
        array (
          0 => 'active',
          1 => 'category',
          2 => 'description',
          3 => 'priority',
        ),
        'types' => 
        array (
          'active' => 'boolean',
          'category' => 'string',
          'description' => 'string',
          'id' => 'string',
          'label' => 'string',
          'priority' => 'integer',
        ),
        'references' => 
        array (
          'category' => 
          array (
            'field' => 'category',
            'target' => 'product_categories',
            'relation' => 'belongs_to',
            'collection' => false,
            'confidence' => 100,
            'reason' => 'Normalized "category" to "product_categories" (product_categories).',
          ),
        ),
        'validation' => 
        array (
          'minimum_records' => 1,
          'allow_duplicate_id' => false,
          'allow_unknown_fields' => false,
        ),
        'has_validation' => true,
      ),
      'metadata' => 
      array (
      ),
      'incoming' => 
      array (
      ),
      'outgoing' => 
      array (
        0 => '5e70f5cd1e619f3c8ccc5b58340adf6e',
      ),
      'degree' => 1,
    ),
  ),
);
