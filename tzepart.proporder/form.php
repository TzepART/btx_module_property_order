<?php
/**
 * Created by PhpStorm.
 * User: Ri
 * Date: 26.03.2016
 * Time: 20:49
 */
/*
 * Подключаем языковые файлы
 * */
\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
?>
<form action="<?=$APPLICATION->GetCurPageParam()?>" method="post">
    <div id="parentId">
        <?=bitrix_sessid_post()?>
        <input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
        <input type="hidden" name="id" value="tzepart.proporder">
        <input type="hidden" name="install" value="Y">
        <input type="hidden" name="step" value="2">
        <?
        $i = 1;
        foreach ($arParameters as $key => $name) { ?>
            <div>
                <nobr>
                    <input name="name[<?= $i ?>]" type="text" style="width:300px;" value="<?= $key ?>"/>
                    <input name="descript[<?= $i ?>]" type="text" style="width:300px;" value="<?= $name ?>"/>
                    <a class="adm-table-btn-delete" onclick="return deleteField(this)" href="#"></a><br>
                </nobr>
            </div>
            <?
            $i++;
        } ?>
    </div>
    <a class="adm-btn adm-btn-save adm-btn-add" onclick="return addField()" href="#"><?=GetMessage('TZEPART_PROPORDER_PROPERTY_ADD')?></a>
    <input type="submit" value="SAVE">
</form>
<br>
<form action="<?=$APPLICATION->GetCurPageParam()?>" method="post">
    <input type="hidden" name="default_value" value="Y">
    <input type="submit" value="Свойства по умолчанию">
</form>
<script src="/local/modules/tzepart.proporder/js/script.js"></script>

