<?php
/**
 * Created by PhpStorm.
 * User: TzepArt
 * Date: 13.03.2016
 * Time: 20:49
 */

/*
 * Класс для добавления кнопки в главное меню Административного раздела
 * и раздела модуля
 * */

class GlobalMenu
{
    function AddGlobalMenuButton(&$adminMenu, &$moduleMenu)
    {
        $moduleMenu[] = array(
            "parent_menu" => "global_menu_store", // поместим в раздел "Магазин"
            "sort" => 100,                    // сортировка пункта меню
            "url" => "/bitrix/admin/proporder_tzepart_table.php",  // ссылка на пункте меню
            "text" => 'Свойства заказа',       // текст пункта меню
            "title" => 'Свойства заказа', // текст всплывающей подсказки
            "icon" => "default_menu_icon", // малая иконка
            "page_icon" => "default_page_icon", // большая иконка
        );

//        $arRes = array(
//            "global_menu_store" => array(
//                "menu_id" => "store",
//                "page_icon" => "service_title_icon",
//                "index_icon" => "service_page_icon",
//                "text" => 'Свойства заказа',
//                "title" => 'Свойства заказа',
//                "sort" => 400,
//                "items_id" => "global_menu_store",
//                "help_sections" => "store",
//                "items" => array()          // остальные уровни меню сформируем ниже.
//            )
//        );
//
//        return $arRes;
    }
}