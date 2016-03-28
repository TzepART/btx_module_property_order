<?php
/**
 * Created by PhpStorm.
 * User: TzepART
 * Date: 06.12.2015
 * Time: 19:02
 */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;


/*
 * Подключаем языковые файлы
 * */
Loc::loadMessages(__FILE__);


class ParameterProperties
{
    protected $MODULE_ID         = "tzepart.proporder";
    protected $defaultParameters = array();

    function __construct()
    {
        $this->defaultParameters = array(
            "utm_source" => GetMessage('TZEPART_PROPORDER_LIB_PARAMETR_UTM_SOURCE'),
            "utm_medium" => GetMessage('TZEPART_PROPORDER_LIB_PARAMETR_UTM_MEDIUM'),
            "utm_campaign" => GetMessage('TZEPART_PROPORDER_LIB_PARAMETR_UTM_CAMPAIGN'),
            "utm_term" => GetMessage('TZEPART_PROPORDER_LIB_PARAMETR_UTM_TERM'),
            "utm_content" => GetMessage('TZEPART_PROPORDER_LIB_PARAMETR_UTM_CONTENT'),
        );
    }

    /**
     * @param string $MODULE_ID
     */
    public function setMODULEID($MODULE_ID)
    {
        $this->MODULE_ID = $MODULE_ID;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return $this->defaultParameters;
    }

    public function getArrayParameters()
    {
        global $DB;
        $parameters = array();
        $strSql     = "
        SELECT NAME, VALUE
        FROM b_option
        WHERE
            MODULE_ID='$this->MODULE_ID'
        ";
        $obj        = $DB->Query($strSql, false, "getArrayParameters" . __LINE__);
        $i          = 0;
        while ($result = $obj->GetNext()) {
            $parameters[$i]["NAME"]        = $result["NAME"];
            $parameters[$i]["DESCRIPTION"] = $result["VALUE"];
            $i++;
        }
        return $parameters;
    }

    public function getParameters()
    {
        $parameters = array();
        $arParams   = $this->getArrayParameters();
        if (!empty($arParams)) {
            foreach ($arParams as $index => $arParam) {
                $parameters[$arParam["NAME"]] = $arParam["DESCRIPTION"];
            }
        } else {
            $parameters = $this->getDefaultParameters();
        }

        return $parameters;
    }

    public function getNameCurrentParameters()
    {
        $currentParameters = array();
        $arParams          = $this->getArrayParameters();
        if (!empty($arParams)) {
            foreach ($arParams as $index => $arParam) {
                $currentParameters[] = $arParam["NAME"];
            }
        }

        return $currentParameters;
    }

    /**
     * @method ParameterProperties checked existing parameter with name $parameterName
     * @param string $parameterName
     * @return bool
     */
    public function existsParameterProperty($parameterName)
    {
        $result = false;
        if (COption::GetOptionString($this->MODULE_ID, $parameterName)) {
            $result = true;
        }
        return $result;
    }

    /**
     * @method ParameterProperties create default parameters
     * @return bool
     */
    public function createDefaultParametersProperty()
    {
        foreach ($this->defaultParameters as $name => $value) {
            if (!$this->existsParameterProperty($name)) {
                $this->createParameterProperty($name, $value);
            }
        }
        return true;
    }

    /**
     * @method ParameterProperties create new parameter with name $parameterName
     * @param string $parameterName
     * @param string $parameterValue
     * @return bool
     */
    public function createParameterProperty($parameterName, $parameterValue)
    {
        $result = false;
//        if(COption::GetOptionString($this->MODULE_ID, $parameterName) != $parameterValue){
        $result = COption::SetOptionString($this->MODULE_ID, $parameterName, $parameterValue);
//        }
        return $result;
    }


    /**
     * @method ParameterProperties delete parameter with name $parameterName
     * @param string $parameterName
     * @return bool
     */
    public function deleteParameterProperty($parameterName)
    {
        COption::RemoveOption($this->MODULE_ID, $parameterName);
        return true;
    }

    /**
     * @method ParameterProperties update parameter with name $parameterName
     * @param string $parameterName
     * @param string $parameterValue
     * @return bool
     */
    public function updateParameterProperty($parameterName, $parameterValue)
    {
        $result = COption::SetOptionString($this->MODULE_ID, $parameterName, $parameterValue);
        return $result;
    }

    public function saveParameterPropertyFromRequest()
    {
        $context    = Application::getInstance()->getContext();
        $arName     = $context->getRequest()->getPost("name");
        $arDescript = $context->getRequest()->getPost("descript");
        if (!empty($arName) && !empty($arDescript)) {
            $this->setMODULEID("tzepart.proporder");
            foreach ($arName as $key => $name) {
                if (!empty($name) && !empty($arDescript[$key])) {
                    $this->createParameterProperty($name, $arDescript[$key]);
                }
            }
            return true;
        } else {
            return false;
        }
    }

}