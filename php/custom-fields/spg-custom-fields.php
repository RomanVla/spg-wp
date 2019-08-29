<?php

// ACF Builder
// https://github.com/StoutLogic/acf-builder

// Quickly create, register, and reuse ACF configurations, and keep them in your source code repository.
// To read more about registering ACF fields via php consult https://www.advancedcustomfields.com/resources/register-fields-via-php/

require_once(__DIR__ . '/custom-fields-builder/Transform/Transform.php');
require_once(__DIR__ . '/custom-fields-builder/Transform/RecursiveTransform.php');
require_once(__DIR__ . '/custom-fields-builder/Transform/IterativeTransform.php');
require_once(__DIR__ . '/custom-fields-builder/Transform/ConditionalField.php');
require_once(__DIR__ . '/custom-fields-builder/Transform/ConditionalLogic.php');
require_once(__DIR__ . '/custom-fields-builder/Transform/FlexibleContentLayout.php');
require_once(__DIR__ . '/custom-fields-builder/Transform/NamespaceFieldKey.php');


require_once(__DIR__ . '/custom-fields-builder/Builder.php');
require_once(__DIR__ . '/custom-fields-builder/NamedBuilder.php');

require_once(__DIR__ . '/custom-fields-builder/FieldNameCollisionException.php');
require_once(__DIR__ . '/custom-fields-builder/FieldNotFoundException.php');
require_once(__DIR__ . '/custom-fields-builder/ModifyFieldReturnTypeException.php');

require_once(__DIR__ . '/custom-fields-builder/ParentDelegationBuilder.php');

require_once(__DIR__ . '/custom-fields-builder/FieldManager.php');
require_once(__DIR__ . '/custom-fields-builder/ConditionalBuilder.php');
require_once(__DIR__ . '/custom-fields-builder/LocationBuilder.php');

require_once(__DIR__ . '/custom-fields-builder/FieldsBuilder.php');
require_once(__DIR__ . '/custom-fields-builder/FieldBuilder.php');
require_once(__DIR__ . '/custom-fields-builder/GroupBuilder.php');
require_once(__DIR__ . '/custom-fields-builder/RepeaterBuilder.php');
require_once(__DIR__ . '/custom-fields-builder/FlexibleContentBuilder.php');
require_once(__DIR__ . '/custom-fields-builder/ChoiceFieldBuilder.php');

require_once(__DIR__ . '/custom-fields-builder/TabBuilder.php');
require_once(__DIR__ . '/custom-fields-builder/AccordionBuilder.php');

require_once(__DIR__ . '/spg-custom-field.php');
require_once(__DIR__ . '/spg-custom-field-builder.php');