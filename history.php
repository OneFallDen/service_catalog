<?php

echo '<div class="block-lk">';

if (CModule::IncludeModule("crm")) {

    if ($USER->IsAuthorized()) {
        // Получаем данные пользователя
        $currentUserId = $USER->GetID();
        $arUser = CUser::GetByID($currentUserId)->Fetch();
    }

    $emailToFind = $arUser['EMAIL'];

    echo '<div class="block-lk__order-list">';

    echo '
            <a class="history-orders2" href="?personalAccount=1">
                <p>К активным заказам</p>
                <img src="img/стрелка.svg" alt="">
            </a>
        ';

        echo "<form class='filter' action='?personalAccount=personalAccount&date_sort=date_sort&status_sort=status_sort&section_sort=section_sort'>";
        
        echo '
                <div>
                    <strong>Дата:</strong><select class="filter__list" name="date_sort">
                        <option value="DESC" selected>От нового к старому</option>
                        <option value="ASC">От старого к новому</option>
                    </select>
                </div>
            ';

        echo '
            <div>
                <strong>Статус</strong><select class="filter__list" name="status_sort">
                    <option value="ALL" selected>Все</option>
                    <option value="FAIL">Отменён</option>
                    <option value="SUCCESS">Получен</option>
                </select>
            </div>
        ';

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
                "NAME",
            );

            // Получаем список разделов инфоблока
            $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);

            echo '
            <div>
                <strong>Раздел</strong><select class="filter__list" name="section_sort">
                    <option value="По умолчанию" selected>По умолчанию</option>
            ';

            // Выводим разделы
            while ($arSection = $rsSections->GetNext()) {
                $secName = $arSection['NAME'];
                echo "<option value='$secName'>$secName</option>";
            }
            echo '
                    <option value="Индивидуальная заявка">Индивидуальная заявка</option>
                    </select>
                    </div>
                ';
        }

        echo "
                <input id='noDisp' name='history' type='text' value='1'>
                <input class='filter__btn' id='button' type='submit' value='Отсортировать'>
            ";
        
        echo '</form>';

        echo '<div class="order-list__block">';

        if (isset($_GET['date_sort'])) {
            $arDateSort = array('ID' => addslashes(strip_tags(trim($_GET['date_sort']))));
        } else {
            $arDateSort = array('ID' => 'DESC');
        }
        
        $arFilter = array($email_field => $emailToFind);

        if (isset($_GET['status_sort']) && $_GET['status_sort'] != "ALL") {
            $arFilter['STAGE_ID'] = $smartStatus.addslashes(strip_tags(trim($_GET['status_sort'])));
        }

        if (isset($_GET['section_sort']) && $_GET['section_sort'] != "По умолчанию") {
            $arFilter[$mainSectionName_field] = addslashes(strip_tags(trim($_GET['section_sort'])));
        }

        $items = $factory->getItems([
            'filter' => $arFilter,//тут задаем фильтр для выборки, можно по полям элемента
            'select' => ['ID', $email_field, $review_field, 'TITLE', 'STAGE_ID', "ASSIGNED_BY_ID"],//Какие поля получить, можно указать * если нужны все
            'order' => ['ID' => 'DESC'],//Указываем поле по которому будет сортироваться выборка и направление сортировки
            'limit'=>1000,//Сколько элементов выбрать за запрос
            'offset' =>0//С какого элемента по счету начать выборку
        ]);

    echo '<div class="order-list__block">';

    foreach($items as $arLead){
        $parts = explode(':', $arLead['STAGE_ID']);

        if ($parts[1] == 'SUCCESS') {
            $status_picture = 'статус зелёный.svg';
        } else if ($parts[1] == 'FAIL') {
            $status_picture = 'статус красный.svg';
        }
        
            if (($parts[1] == 'SUCCESS') || ($parts[1] == 'FAIL')){
                
                echo "
                    <div class='order-list__item'>
                        <img src='img/img-card.jpg' alt=''>
                        <div class='item__info'>
                            <p><span class='service'>".$arLead["TITLE"]." </span>".$arLead["ID"]."</p>
                            <p class='item__info_status'><img src='img/$status_picture' alt=''>".$statuses[$parts[1]]."</p>
                            <p class='item__info_status'>Отзыв: ".$arLead[$review_field]."</p>
                        </div>
                    </div>
                ";

            }  

    }

    echo '</div>';

    echo '</div>';

    echo '</div>';

    echo "
        <div class='block-lk__your-info'>
            <img src='img/img-card.jpg' alt=''>
            <p class='your-info__name'>".$arUser['NAME']." ".$arUser['LAST_NAME']."</p>
            <p class='your-info__mail'>".$emailToFind."</p>
        </div> 
        ";

} else {
    echo "Модуль 'crm' не подключен.";
}

echo '</div>';

?>