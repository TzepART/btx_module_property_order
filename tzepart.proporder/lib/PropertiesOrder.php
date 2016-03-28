<?php
/**
 * Created by PhpStorm.
 * User: TzepART
 * Date: 06.12.2015
 * Time: 19:40
 */
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;


/*
 * Подключаем языковые файлы
 * */
Loc::loadMessages(__FILE__);



class PropertiesOrder
{
    protected $orderPropertyObject = false;
    protected $orderPropsValueObject = false;
    protected $PREFIX = "TZEPART_";
    protected $arPropertiesOrderDefault      = array(
        "TYPE" => "TEXT",
        "REQUIED" => "Y",
        "DEFAULT_VALUE" => "F",
        "SORT" => 100,
        "PERSON_TYPE_ID" => 1,
        "USER_PROPS" => "N",
        "IS_LOCATION" => "N",
        "IS_LOCATION4TAX" => "N",
        "PROPS_GROUP_ID" => 1,
        "SIZE1" => 0,
        "SIZE2" => 0,
        "DESCRIPTION" => "",
        "IS_EMAIL" => "N",
        "IS_PROFILE_NAME" => "N",
        "IS_PAYER" => "N"
    );

    /**
     * PropertiesOrder constructor.
     */
    public function __construct()
    {
        if (CModule::IncludeModule("sale")) {
            $this->orderPropertyObject = new CSaleOrderProps;
            $this->orderPropsValueObject = new CSaleOrderPropsValue;
        } else {
            echo Loc::getMessage('TZEPART_PROPORDER_LIB_PROPERTIES_ERRORMESSAGE');
        }
    }

    public function savePropertyOrderByOrderId($order_id,$propertyName,$value = ""){

        $result = false;
        $propertyCode = strtoupper($this->PREFIX.$propertyName);

        if($arProps = $this->returnInfoPropertyOrderByCode($propertyCode)){
            $arFields = array(
                "ORDER_ID" => $order_id,
                "ORDER_PROPS_ID" => $arProps["ID"],
                "NAME" => $arProps["NAME"],
                "CODE" => $arProps["CODE"],
                "VALUE" => $value
            );
            $result = $this->orderPropsValueObject->Add($arFields);
        }

        return $result;
    }

    public function createPropertiesOrder($arProperties)
    {//if module SALE was successful connected create Properties, if they don't exists
        if ($this->orderPropertyObject) {
            foreach ($arProperties as $key => $prop) {
                if (!$this->existsPropertyOrderByCode($prop["CODE"])) {
                    $resArrayProperty = array_merge($prop, $this->arPropertiesOrderDefault);
                    if (!empty($resArrayProperty)) {
                        $this->createPropertyOrder($resArrayProperty);
                    }
                }
            }
        }
    }

    /**
     * @method PropertiesOrder delete properties order with name $propertyName
     * @param string $propertyName
     * @return bool
     */
    public function deletePropertyOrder($propertyName)
    {
        $result = false;
        $propertyCode = strtoupper($this->PREFIX.$propertyName);
        
        if($id_props = $this->existsPropertyOrderByCode($propertyCode)){
            $result = $this->orderPropertyObject->Delete($id_props);
        }
        return $result;
    }

    /**
 * @method PropertiesOrder checked existing properties order with name $propertyName
 * @param string $propertyCode
 * @return false if prop doesn't exist or id props
 */
    protected function existsPropertyOrderByCode($propertyCode)
    {

        $db_props = $this->orderPropertyObject->GetList(
            array("SORT" => "ASC"),
            array(
                "CODE" => $propertyCode
            ),
            false,
            false,
            array("ID")
        );

        if ($result = $db_props->GetNext()) {
            return $result["ID"];
        } else {
            return false;
        }
    }

    /**
     * @method PropertiesOrder checked existing properties order with name $propertyName
     * @param string $propertyCode
     * @return false if prop doesn't exist or id props
     */
    protected function returnInfoPropertyOrderByCode($propertyCode)
    {

        $db_props = $this->orderPropertyObject->GetList(
            array("SORT" => "ASC"),
            array(
                "CODE" => $propertyCode
            ),
            false,
            false,
            array()
        );

        if ($result = $db_props->GetNext()) {
            return $result;
        } else {
            return false;
        }
    }

    /**
     * @method PropertiesOrder update properties order with $id
     * @param int $id
     * @param array $arFields
     * @return bool
     */
    protected function updatePropertyOrder($id,$arFields)
    {
        $result = $this->orderPropertyObject->Update($id, $arFields);
        return $result;
    }


    /**
     * @method PropertiesOrder create new properties order with name $propertyName
     * @param array $arFields
     * @return bool
     */
    protected function createPropertyOrder($arFields)
    {
        $result = $this->orderPropertyObject->Add($arFields);
        return $result;
    }


    function savePropertyOrderFromRequest()
    {
        $context = Application::getInstance()->getContext();
        $arName = $context->getRequest()->getPost("name");
        $arDescript = $context->getRequest()->getPost("descript");

        if (!empty($arName)) {
            $arPropertiesOrder = array();
            foreach ($arName as $key => $name) {
                $arPropertiesOrder[] = array("NAME" =>  $arDescript[$key], "CODE" => strtoupper($this->PREFIX.$name));
            }
            $this->createPropertiesOrder($arPropertiesOrder);
            return true;
        } else {
            return false;
        }
    }
}
