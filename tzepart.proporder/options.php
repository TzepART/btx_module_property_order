<?
include($_SERVER['DOCUMENT_ROOT']. "/local/modules/tzepart.proporder/lib/ParameterProperties.php");
include($_SERVER['DOCUMENT_ROOT']. "/local/modules/tzepart.proporder/lib/PropertiesOrder.php");


$parameterProperties = new ParameterProperties();

if(!empty($_POST["name"]) && !empty($_POST["descript"])){
    foreach ($_POST["name"] as $key => $name) {
        $parameterProperties->createParameterProperty($name, $_POST["descript"][$key]);
    }

    $PropertiesOrder   = new PropertiesOrder();
    $arPropertiesOrder = array();
    foreach ($_POST["name"] as $key => $name) {
        $arPropertiesOrder[] = array("NAME" => $_POST["descript"][$key], "CODE" => strtoupper("FR_".$name));
    }
    $PropertiesOrder->createPropertiesOrder($arPropertiesOrder);
}

$arParameters = $parameterProperties->getArrayParameters();
?>
<form action="<?=$APPLICATION->GetCurPageParam()?>" method="post">
    <div id="parentId">
    <?
    $i = 1;
    foreach ($arParameters as $key => $value) { ?>
            <div>
                <nobr>
                    <input name="name[<?= $i ?>]" type="text" style="width:300px;" value="<?= $value["NAME"] ?>"/>
                    <input name="descript[<?= $i ?>]" type="text" style="width:300px;" value="<?= $value["DESCRIPTION"] ?>"/>
                    <a class="adm-table-btn-delete" onclick="return deleteField(this)" href="#"></a><br>
                </nobr>
            </div>
        <?
        $i++;
    } ?>
    </div>
    <a class="adm-btn adm-btn-save adm-btn-add" onclick="return addField()" href="#">Добавить</a>
    <input type="submit" value="SAVE">
</form>

<script>
    var countOfFields = document.getElementById("parentId").children.length; // Текущее число полей
    var curFieldNameId = document.getElementById("parentId").children.length; // Уникальное значение для атрибута name
    var maxFieldLimit = 25; // Максимальное число возможных полей
    function deleteField(a) {
        if (countOfFields > 1) {
            // Получаем доступ к ДИВу, содержащему поле
            var contDiv = a.parentNode;
            // Удаляем этот ДИВ из DOM-дерева
            contDiv.parentNode.removeChild(contDiv);
            // Уменьшаем значение текущего числа полей
            countOfFields--;
        }
        // Возвращаем false, чтобы не было перехода по сслыке
        return false;
    }
    function addField() {
        // Проверяем, не достигло ли число полей максимума
        if (countOfFields >= maxFieldLimit) {
            alert("Число полей достигло своего максимума = " + maxFieldLimit);
            return false;
        }
        // Увеличиваем текущее значение числа полей
        countOfFields++;
        // Увеличиваем ID
        curFieldNameId++;
        // Создаем элемент ДИВ
        var div = document.createElement("div");
        // Добавляем HTML-контент с пом. свойства innerHTML
        div.innerHTML = "<nobr><input name=\"name[" + curFieldNameId + "]\" type=\"text\" style=\"width:300px;\"/>" +
            "<input name=\"descript[" + curFieldNameId + "]\" type=\"text\" style=\"width:300px;\" />" +
            " <a class=\"adm-table-btn-delete\" onclick=\"return deleteField(this)\" href=\"#\"></a><br>";
        // Добавляем новый узел в конец списка полей
        document.getElementById("parentId").appendChild(div);
        // Возвращаем false, чтобы не было перехода по сслыке
        return false;
    }
</script>