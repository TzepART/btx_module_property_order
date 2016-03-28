<? if (!check_bitrix_sessid()) return; ?>
<?
include($DOCUMENT_ROOT . "/local/modules/tzepart.proporder/lib/ParameterProperties.php");
include($DOCUMENT_ROOT . "/local/modules/tzepart.proporder/lib/PropertiesOrder.php");
use \Bitrix\Main\Application;

/*
 * Подключаем языковые файлы
 * */
\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

$parametersProperty = new ParameterProperties;
$PropertiesOrder   = new PropertiesOrder();

$arParameters = $parametersProperty->getParameters();

$context = Application::getInstance()->getContext();
$delName = $context->getRequest()->getPost("delete_name");
$default_settings = $context->getRequest()->getPost("default_value");


if($default_settings == "Y"){
    foreach ($arParameters as $name => $description) {
        $parametersProperty->deleteParameterProperty($name);
        $PropertiesOrder->deletePropertyOrder($name);
    }
    $arParameters = $parametersProperty->getParameters();
}

if(!empty($delName)){
    $parametersProperty->deleteParameterProperty($delName);
    $PropertiesOrder->deletePropertyOrder($delName);
}

?>
<?
include($DOCUMENT_ROOT . "/local/modules/tzepart.proporder/form.php");
?>