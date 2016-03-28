<?
include($DOCUMENT_ROOT . "/local/modules/tzepart.proporder/lib/PropertiesOrder.php");
include($DOCUMENT_ROOT . "/local/modules/tzepart.proporder/lib/ParameterProperties.php");
include($DOCUMENT_ROOT . "/local/modules/tzepart.proporder/lib/globalmenu.php");

use \Bitrix\Main\EventManager;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager;

/*
 * Подключаем языковые файлы
 * */
Loc::loadMessages(__FILE__);

if (class_exists("tzepart_proporder"))
    return;


Class tzepart_proporder extends CModule
{
    var $MODULE_ID = "tzepart.proporder";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;


    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__)."/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION      = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME        = GetMessage('TZEPART_PROPORDER_INSTALL_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('TZEPART_PROPORDER_INSTALL_DESCRIPTION');
    }

    function DoInstall()
    {
        global $APPLICATION, $step, $errors;

        $FORM_RIGHT = $APPLICATION->GetGroupRight("tzepart.proporder");
        if ($FORM_RIGHT >= "W") {
            $step = IntVal($step);
            if ($step < 2) {
                $APPLICATION->IncludeAdminFile(
                    GetMessage('TZEPART_PROPORDER_INSTALL_MODULE'),
                    dirname(__FILE__)."/step1.php"
                );
            } elseif ($step == 2) {
                $errors = false;
                $this->InstallFiles();
                $eventManager = EventManager::getInstance();
                $eventManager->registerEventHandlerCompatible("iblock", "OnAfterIBlockElementUpdate", "tzepart.proporder");
                ModuleManager::registerModule($this->MODULE_ID);

                CModule::IncludeModule("tzepart.proporder");
                $parametersProperty = new ParameterProperties;
                $parametersProperty->saveParameterPropertyFromRequest();

                $PropertiesOrder   = new PropertiesOrder();
                $PropertiesOrder->savePropertyOrderFromRequest();
                EventManager::getInstance()->registerEventHandler("main", "OnBuildGlobalMenu", $this->MODULE_ID, 'GlobalMenu', "AddGlobalMenuButton");

                $APPLICATION->IncludeAdminFile(GetMessage('TZEPART_PROPORDER_INSTALL_MODULE'), dirname(__FILE__)."/step2.php");
            }
        }
    }


    function DoUninstall()
    {
        global $APPLICATION;
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler("iblock", "OnAfterIBlockElementUpdate", "tzepart.proporder");
        EventManager::getInstance()->unRegisterEventHandler("main", "OnBuildGlobalMenu", $this->MODULE_ID, 'GlobalMenu', "AddGlobalMenuButton");

        $this->UnInstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(GetMessage('TZEPART_PROPORDER_UNINSTALL_MODULE'), dirname(__FILE__)."/unstep.php");

        return true;
    }

    /*
     * method for copy admin files in /bitrix/admin
     * */
    function InstallFiles()
    {
        CopyDirFiles(dirname(__FILE__)."/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true);
        return true;
    }

    /*
     * method for delete admin files from /bitrix/admin
     * */
    function UnInstallFiles()
    {
        DeleteDirFiles(dirname(__FILE__)."/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
        return true;
    }

}