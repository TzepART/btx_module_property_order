<? if (!check_bitrix_sessid()) return; ?>
<?
/*
 * Подключаем языковые файлы
 * */
\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
echo CAdminMessage::ShowNote(GetMessage("TZEPART_PROPORDER_INSTALL_STEP2_SUCCESS"));
?>
