<?

    function get_upper_level() {

        $iblockCode = require 'app/config/iBlock.php';
        if (CModule::IncludeModule("iblock")) {
            $iblockId = CIBlock::GetList([], ["CODE" => $iblockCode['iblockCode']])->Fetch()["ID"];
            // Фильтр для выборки разделов инфоблока
            $arFilter = array(
                "IBLOCK_ID" => $iblockId,
                "DEPTH_LEVEL" => 1
            );
    
            // Сортировка разделов
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
    
            return $rsSections;
        }

    }

    function get_parent_levels() {

        if (isset($_GET['mainSectionName'])&&isset($_GET['parentSectionId'])) {
            
            $parentSectionId = addslashes(strip_tags(trim($_GET['parentSectionId'])));
            $mainSectionName = addslashes(strip_tags(trim($_GET['mainSectionName'])));

            if (CModule::IncludeModule("iblock")) {
    
                // Фильтр для выборки подразделов
                $arFilter = array(
                    "IBLOCK_ID" => $iblockId, // ID вашего инфоблока
                    "SECTION_ID" => $parentSectionId, // ID родительского раздела
                    "ACTIVE" => "Y", // Можно добавить другие фильтры
                );
            
                $arSelect = array("ID", "NAME", "DESCRIPTION"); // Выбираем необходимые свойства раздела
            
                $rsSections = CIBlockSection::GetList(array("SORT" => "ASC"), $arFilter, false, $arSelect);
                // Получение названия подраздела
        
                $aF = array(
                    "IBLOCK_ID" => $iblockId,
                    "ID" => $parentSectionId
                );
            
                $aS = array("NAME"); // Выбираем только название раздела
            
                $rsS = CIBlockSection::GetList(array(), $aF, false, $aS);
                if ($arS = $rsS->GetNext()) {
                    $sectionName = $arS["NAME"];
                }

                $arFilter = array(
                    "IBLOCK_ID" => $iblockId, // ID вашего инфоблока
                    "SECTION_ID" => $parentSectionId,
                    "ACTIVE" => "Y",
                );
        
                $arSelect = array("ID", "NAME", "PREVIEW_TEXT", "CODE"); // Выбираем необходимые свойства услуги
        
                $rsElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, $arSelect);

                $result = Array (
                    'sectionName' => $sectionName,
                    'sections' => $rsSections,
                    'elements' => $rsElements,
                );

                return $result;
        
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

?>