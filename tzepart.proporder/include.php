<?php
CModule::IncludeModule("tzepart.proporder");
global $DBType;

$arClasses = array(
    'ParameterProperties' => 'lib/ParameterProperties.php',
    'PropertiesOrder' => 'lib/PropertiesOrder.php',
    'GlobalMenu' => 'lib/globalmenu.php',
);

//CModule::AddAutoloadClasses("proporder", $arClasses);
\Bitrix\Main\Loader::registerAutoLoadClasses("tzepart.proporder", $arClasses);

$parametersProperty = new ParameterProperties;
$PropertiesOrder   = new PropertiesOrder();
$arrayCurrentOrderProperties = $parametersProperty->getNameCurrentParameters();


$context = \Bitrix\Main\Application::getInstance()->getContext();
/** @var \Bitrix\Main\HttpRequest $request */
$order_id = $context->getRequest()->get("ORDER_ID");

/*
 * if in request exists necessary parameters save it in $_SESSION
 * */
foreach ($arrayCurrentOrderProperties as $index => $nameProperty) {
    $prop_value = $context->getRequest()->get($nameProperty);
    if(!empty($prop_value)){
        $_SESSION["USER_ORDER_PROPERTIES"][$nameProperty] = $prop_value;
    }
}

if(!empty($order_id)){
    foreach ($_SESSION["USER_ORDER_PROPERTIES"] as $nameProperty => $value) {
        if(!empty($value))
        $PropertiesOrder->savePropertyOrderByOrderId($order_id,$nameProperty,$value);
    }
}


