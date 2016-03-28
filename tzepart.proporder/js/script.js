/**
 * Created by Ri on 26.03.2016.
 */
var countOfFields = 5; // Текущее число полей
var curFieldNameId = 5; // Уникальное значение для атрибута name
var maxFieldLimit = 25; // Максимальное число возможных полей
function deleteField(a) {
    //if (countOfFields > 0) {
        var arrInput = a.parentNode.getElementsByTagName('input');
        var name = arrInput[0].value;

        BX.ajax.post(window.location.href, {delete_name : name} ,function(){});
        // Получаем доступ к ДИВу, содержащему поле
        var contDiv = a.parentNode;
        // Удаляем этот ДИВ из DOM-дерева
        contDiv.parentNode.removeChild(contDiv);
        // Уменьшаем значение текущего числа полей
        countOfFields--;
    //}
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
