<?php

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

        echo '<h1 class="name-group">'.$sectionName.'</h1>';

        // Заполнение карточек подразделов 
            
            echo '<div class="cards">';

            while ($arSection = $rsSections->GetNext()) {
                $parentSectionId = $arSection["ID"];
                echo '
                    <a href="?parentSectionId='.$parentSectionId.'&mainSectionName='.$mainSectionName.'">
                        <div class="card">
                            <div class="card__img">
                                <img class="card__img-item" src="img/img-card.jpg" alt="..."> 
                            </div> 
                            <div class="card__block">
                                <h2 class="card__name">'.$arSection["NAME"].'</h2>
                                <img  src="img/стрелка далее.svg" alt="">
                            </div>
                        </div>
                    </a>
                ';
            }

            echo "</div>";      

    } else {
        echo "Модуль 'iblock' не подключен.";
    }

    // Получаем услуги из указанного раздела
    if (CModule::IncludeModule("iblock")) {
        
        $parentSectionId = addslashes(strip_tags(trim($_GET['parentSectionId'])));

        $arFilter = array(
            "IBLOCK_ID" => $iblockId, // ID вашего инфоблока
            "SECTION_ID" => $parentSectionId,
            "ACTIVE" => "Y", // Можно добавить другие фильтры
        );

        $arSelect = array("ID", "NAME", "PREVIEW_TEXT", "CODE"); // Выбираем необходимые свойства услуги

        $rsElements = CIBlockElement::GetList(array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
        
        echo '<div class="cards">';

        while ($arElement = $rsElements->GetNext()) {
            $parentServiceId = $arElement['CODE'];
            $catalogParentServiceId = $arElement["ID"];
            $serviceName = $arElement["NAME"];

            $arFilter = array(
                "IBLOCK_ID" => $iblockId, // ID вашего инфоблока
                "ID" => $parentSectionId, // ID родительского раздела
            );
        
            $arSelect = array("ID", "NAME"); // Выбираем название раздела
        
            $rsSections = CIBlockSection::GetList(array(), $arFilter, false, $arSelect);
        
            if ($arSection = $rsSections->GetNext()) {
                $parentSectionName = $arSection["NAME"];
            } 

            echo "
                    <a href='?parSecId=$parentSectionId&mainSectionName=$mainSectionName&parentServiceId=$parentServiceId&catalogParentServiceId=$catalogParentServiceId&serviceName=$serviceName&parentSectionName=$parentSectionName'>
                        <div class='card'>
                            <div class='card__img'>
                                <img class='card__img-item' src='img/img-card.jpg' alt='...'> 
                            </div> 
                            <div class='card__block'>
                                <h2 class='card__name'>".$arElement["NAME"]."</h2>
                                <img  src='img/стрелка далее.svg' alt=''>
                            </div>
                        </div>
                    </a>
                ";
            
                
        }
        echo "</div>";

    } else {
        echo "Модуль 'iblock' не подключен.";
    }
?>