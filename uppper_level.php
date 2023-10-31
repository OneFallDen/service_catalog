<ul class="block__list">
<?
// Получаем ID инфоблока по его символьному коду
if (CModule::IncludeModule("iblock")) {
    $iblockId = CIBlock::GetList([], ["CODE" => $iblockCode])->Fetch()["ID"];

    if ($iblockId) {
        // Фильтр для выборки разделов инфоблока
        $arFilter = array(
            "IBLOCK_ID" => $iblockId,
            "DEPTH_LEVEL" => 1, // Выбирайте только разделы верхнего уровня
        );

        // Сортировка разделов (пример - по названию, по возрастанию)
        $arSort = array(
            "NAME" => "ASC",
        );

        // Выбираем необходимые свойства разделов
        $arSelect = array(
            "ID",
            "NAME",
            "DESCRIPTION"
        );

        // Получаем список разделов инфоблока
        $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);

        // Выводим разделы
        while ($arSection = $rsSections->GetNext()) {
            $mainSectionName = $arSection["NAME"];
            $parentSectionId = $arSection['ID'];
            echo '
                <li class="block__item">
                    <a class="chapter" href="?mainSectionName='.$mainSectionName.'&parentSectionId='.$parentSectionId.'">
                        <p>'.$arSection["NAME"].'</p>
                    </a>
                </li>
                ';
        }
    } else {
        echo "Инфоблок с символьным кодом '$iblockCode' не найден.";
    }
} else {
    echo "Модуль 'iblock' не подключен.";
}
?>
</ul>