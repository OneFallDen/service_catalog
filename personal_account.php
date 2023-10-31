<?php

    echo '<div class="block-lk">';

    if (CModule::IncludeModule("crm")) {

        if ($USER->IsAuthorized()) {
            // Получаем данные пользователя
            $currentUserId = $USER->GetID();
            $arUser = CUser::GetByID($currentUserId)->Fetch();
        }
        
        if (isset($_POST['checked__radio'])){
            if ($_POST['checked__radio'] == 'checked__yes') {
                if (isset($_POST['review'])) {
                    $review = addslashes(strip_tags(trim($_POST['review'])));
                    if (isset($_POST['service_id'])){
                        $service_id = addslashes(strip_tags(trim($_POST['service_id'])));
                        $item = $factory->getItem($service_id);
                        $item->set($review_field , $review);
                        $item->set('STAGE_ID' , $smartStatus.'SUCCESS');
                        $item->save();
    
                         // Отправка заказчику
                        $currentUserId = $USER->GetID();
                        $arUser = CUser::GetByID($currentUserId)->Fetch();
    
                        $to = $arUser["EMAIL"];
                        $subject = "Услуга получена";
                        $message = "Спасибо за отзыв по заявке".$item["ID"].".";
    
                        mail($to, $subject, $message);
    
                        // Отправка исполнителю
                        if (CModule::IncludeModule("main")) {
                            $userData = CUser::GetByID($item['ASSIGNED_BY_ID'])->Fetch();
                            if ($userData) {
                                $userEmail = $userData["EMAIL"];
                            }
                        }
    
                        $to = $userEmail;
                        $subject = "Заявка обработана";
                        $message = "Заявка с №".$item["ID"]." принята и перенесена в архив.\nОтзыв получателя: $review";
    
                        mail($to, $subject, $message);
                    }
                }
            } else if ($_POST['checked__radio'] == 'checked__no') {
                if(isset($_POST['service_id'])){
                    $service_id = addslashes(strip_tags(trim($_POST['service_id'])));
                    if (isset($_POST['variant'])) {
                        if ($_POST['variant'] == 'Другое') {
                            $review = addslashes(strip_tags(trim($_POST['other-input'])));
                        } else {
                            $review = addslashes(strip_tags(trim($_POST['variant'])));
                        }
                    }
                    $item = $factory->getItem($service_id);
                    $item->set($review_field , $review);
                    $item->set('STAGE_ID' , $smartStatus.':PREPARATION');
                    $item->save();
    
                    // Отправка заказчику
                    $currentUserId = $USER->GetID();
                    $arUser = CUser::GetByID($currentUserId)->Fetch();
    
                    $to = $arUser["EMAIL"];
                    $subject = "Заявка в работе";
                    $message = "Спасибо за отзыв по заявке".$item["ID"].".\nМы скоро рассмотрим Вашу заявку.";
    
                    mail($to, $subject, $message);
    
                    // Отправка исполнителю
                    if (CModule::IncludeModule("main")) {
                        $userData = CUser::GetByID($item['ASSIGNED_BY_ID'])->Fetch();
                        if ($userData) {
                            $userEmail = $userData["EMAIL"];
                        }
                    }
    
                    $to = $userEmail;
                    $subject = "Заявка в работе";
                    $message = "Заявка с №".$item["ID"]." не принята и перенесена в работу.\nОтзыв получателя: $review";
    
                    mail($to, $subject, $message);
    
                }
            }
        }

        $emailToFind = $arUser['EMAIL'];

        echo '<div class="block-lk__order-list">';

        echo '
                <a class="history-orders" href="?history=1">
                    <p>К истории заказов</p>
                    <img src="img/история.svg" alt="">
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
                <select class="filter__list" name="status_sort">
                    <option value="ALL" selected>Выбрать статус</option>
                    <option value="NEW">Не обработан</option>
                    <option value="PREPARATION">В работе</option>
                    <option value="CLIENT">Обработан</option>
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
                <select class="filter__list" name="section_sort">
                    <option value="По умолчанию" selected>Выбрать раздел</option>
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
                <input id='noDisp' name='personalAccount' type='text' value='1'>
                <input class='filter__btn' id='button' type='submit' value='Применить'>
            ";
        
        echo '</form>';


        echo '<div class="order-list__block">';

        if (isset($_GET['date_sort'])) {
            $arDateSort = array('ID' => addslashes(strip_tags(trim($_GET['date_sort']))));
        } else {
            $arDateSort = array('ID' => 'DESC');
        }
        
        $arFilter = array($email_field => $emailToFind);

        if (isset($_GET['status_sort']) && addslashes(strip_tags(trim($_GET['status_sort']))) != "ALL") {
            $arFilter['STAGE_ID'] = $smartStatus.addslashes(strip_tags(trim($_GET['status_sort'])));
        }

        if (isset($_GET['section_sort']) && $_GET['section_sort'] != "По умолчанию") {
            $arFilter[$mainSectionName_field] = addslashes(strip_tags(trim($_GET['section_sort'])));
        }

        $items = $factory->getItems([
            'filter' => $arFilter,//тут задаем фильтр для выборки, можно по полям элемента
            'select' => ['ID', $email_field, $comment_field, 'TITLE', 'STAGE_ID', "ASSIGNED_BY_ID"],//Какие поля получить, можно указать * если нужны все
            'order' => $arDateSort,//Указываем поле по которому будет сортироваться выборка и направление сортировки
            'limit'=>1000,//Сколько элементов выбрать за запрос
            'offset' =>0//С какого элемента по счету начать выборку
        ]);

        foreach($items as $arLead){
            $parts = explode(':', $arLead['STAGE_ID']);

            if ($parts[1] == 'CLIENT') {
                $action = '<button class="cancel confirm">Подтвердить</button>'; 
                $status_picture = 'статус зелёный.svg';
            } else if ($parts[1] == 'NEW') {
                $action = "<a class='cancel' href='?delete=".$arLead["ID"]."&personalAccount=1'>Отменить заказ</a>";
                $status_picture = 'статус оранжевый.svg';
            } else {
                $action = "<a class='cancel' href='?delete=".$arLead["ID"]."&personalAccount=1'>Отменить заказ</a>";
                $status_picture = 'статус синий.svg';
            }

            $comment = "<p class='item__info_status'>Комментарий: ".$arLead[$comment_field]."</p>";

            if (isset($_GET['delete'])){
                if ($arLead[$email_field] == $emailToFind) {
                    if (($parts[1] == 'CLIENT') || ($parts[1] == 'NEW') || ($parts[1] == 'PREPARATION')) {

                        $leadId = $_GET['delete'];
                        
                        if ($leadId == $arLead["ID"]){

                            if (($parts[1] == 'NEW') || ($parts[1] == 'PREPARATION')) {

                                $newStatus = "FAIL";

                                $item = $factory->getItem($leadId);

                                $value = $smartStatus.$newStatus;

                                $item->set('STAGE_ID', $value);

                                $item->save();

                                $currentUserId = $USER->GetID();
                                $arUser = CUser::GetByID($currentUserId)->Fetch();

                                $to = $arUser["EMAIL"];
                                $subject = "Отмена заявки";
                                $message = "Отменена заявка с №$leadId.";

                                mail($to, $subject, $message);

                                $userId = $arLead["ASSIGNED_BY_ID"];

                                if (CModule::IncludeModule("main")) {
                                    $userData = CUser::GetByID($userId)->Fetch();
                                    if ($userData) {
                                        $userEmail = $userData["EMAIL"];
                                    }
                                }

                                $to = $userEmail;
                                $subject = "Отмена заявки";
                                $message = "Отменена заявка с №$leadId.";

                                mail($to, $subject, $message);
                            }
                        }

                        if ($arLead["ID"] != $leadId) {
                            if ($parts[1] != 'CLIENT'){
                                echo "
                                    <div class='order-list__item'>
                                        <img src='img/img-card.jpg' alt=''>
                                        <div class='item__info'>
                                            <p><span class='service'>".$arLead["TITLE"]." </span>".$arLead["ID"]."</p>
                                            <p class='item__info_status'><img src='img/$status_picture' alt=''>".$statuses[$parts[1]]."</p>
                                            $comment
                                            $action
                                        </div>
                                    </div>
                                ";
                            } else {
                                echo '
                                        <div class="order-list__item">
                                            <img src="img/img-card.jpg" alt="">
                                            <div class="item__info">
                                                <div class="item__info-text1">
                                                    <p class="service">'.$arLead["TITLE"].'</p>
                                                </div>
                                                <div class="item__info-text2">
                                                    <p class="text__service"></p>'.$arLead["ID"].'</p>
                                                </div>
                                                <p class="item__info_status"><img src="img/'.$status_picture.'" alt="">'.$statuses[$parts[1]].'</p>
                                                '.$action.'
                                            </div>
                                        </div>
                                    ';
                            }
                        }

                    }
                }
            } else if (($parts[1] == 'NEW') || ($parts[1] == 'PREPARATION')){
                
                echo "
                    <div class='order-list__item'>
                        <img src='img/img-card.jpg' alt=''>
                        <div class='item__info'>
                            <p><span class='service'>".$arLead["TITLE"]." </span>".$arLead["ID"]."</p>
                            <p class='item__info_status'><img src='img/$status_picture' alt=''>".$statuses[$parts[1]]."</p>
                            $comment
                            $action
                        </div>
                    </div>
                ";

            } else if ($parts[1] == 'CLIENT') {
                echo '
                        <div class="order-list__item">
                            <img src="img/img-card.jpg" alt="">
                            <div class="item__info">
                                <div class="item__info-text1">
                                    <p class="service">'.$arLead["TITLE"].'</p>
                                </div>
                                <div class="item__info-text2">
                                    <p class="text__service"></p>'.$arLead["ID"].'</p>
                                </div>
                                <p class="item__info_status"><img src="img/'.$status_picture.'" alt="">'.$statuses[$parts[1]].'</p>
                                '.$action.'
                            </div>
                        </div>
                    ';
            }

        }

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

    echo '<iframe name="votar" style="display:none;"></iframe>';

?>

<div class="modal" id="modal-form">
        <form action="" method="POST" id="form" class="form-box">
            <div class="name-form">
                <div class="name-form_1"></div>
                <div class="name-form_2"></div>
            </div>
            <input id="noDisp" type="text" name="service_id" class="service_id">
            <p class="question">Мы решили вашу проблему?</p>
            <div class="checked">
                <div class="checked__radio_btn">
                    <input type="radio" name="checked__radio" onClick="Show(1)" value="checked__yes" id="checked__input_yes">
                    <label for="checked__input_yes">Да, завершить заказ<img class="checked__img" src="img/оценка.svg"
                            alt=""></label>
                </div>

                <div class="checked__radio_btn">
                    <input type="radio" name="checked__radio" onClick="Show(0)" value="checked__no" id="checked__input_no">
                    <label for="checked__input_no">Нет, повторить заказ <img class="checked__img"
                            src="img/повтор заказа.svg" alt=""></label>

                </div>
            </div>


            <div class="block__result" id="block_yes">
                <input type="text" name="review" placeholder="Оставить отзыв">
            </div>

            <div class="block__result" id="block__result_no">
                <p>Выберите одну из причин:</p>
                <div class="block__box">
                    <div class="box__item">
                        <input type="radio" name="variant" onClick="Show(2)" value="С услугой возникла проблема"
                            id="variant_1">
                        <label for="variant_1">С услугой возникли проблемы</label>
                    </div>

                    <div class="box__item">
                        <input type="radio" name="variant" onClick="Show(2)" value="Не устроило качество услуги"
                            id="variant_2">
                        <label for="variant_2">Не устроило качество услуги</label>
                    </div>

                    <div class="box__item">
                        <input type="radio" name="variant" onClick="Show(2)" value="Услуга не была оказана"
                            id="variant_3">
                        <label for="variant_3">Услуга не была оказана</label>
                    </div>

                    <div class="box__item" id="other">
                        <input type="radio" name="variant" onClick="Show(2)" value="Другое" id="variant_4">
                        <label for="variant_4">Другое</label>
                        <div id="other__text">
                            <input type="text" id="other__input-text" name="other-input" placeholder="Введите текст">
                        </div>
                    </div>
                </div>


            </div>

            <button type="submit" id="button-form" class="button-form" >Отправить</button>
            <div class="no-click"></div>
            <button type="button" class="button_close" id="close__modal"><img src="img/крестик.svg" alt=""></button>


        </form>
    </div>

    <div class="modal" id="modal__message_yes">
        <div id="form" class="form-box">
            <div class="form-box__message">
                <p class="message__title">Заказ завершён</p>
                <p>Мы сохраним его в истории заказов</p>
            </div>
            <button type="button" class="button_close" id="close__modal_yes" ><img src="img/крестик.svg" alt=""></button>
        </div>
    </div>

    <div class="modal" id="modal__message_no">
        <div id="form" class="form-box">
            <div class="form-box__message">
                <p class="message__title">Спасибо за ответ!</p>
                <p>Мы скоро рассмотрим Вашу заявку</p>
            </div>
            <button type="button" class="button_close" id="close__modal_no"><img src="img/крестик.svg" alt=""></button>
        </div>
    </div>


<script>
    function Show(a) {
  obj_1 = document.getElementById("block_yes");
  obj_2 = document.getElementById("block__result_no");
  btn_form = document.getElementById("button-form");
  if (a == 1) {
    obj_1.style.display = "block";
    obj_2.style.display = "none";
    btn_form.style = ("pointer-events: all; background-color: #0070BA");
  } else if (a == 0) {
    obj_1.style.display = "none";
    obj_2.style.display = "block";
    btn_form.style = "pointer-events: none; background-color: #6882B4";
  } else if (a == 2) {
    btn_form.style = ("pointer-events: all; background-color: #0070BA");
  }

}

var buttons = document.getElementsByClassName("confirm");
var form = document.getElementById("modal-form");
 
for (var i = 0; i < buttons.length; i++) {
  buttons[i].addEventListener("click", function() {
    form.classList.add('open');
  });
}

document.querySelectorAll(".confirm").forEach(button => {
  button.addEventListener("click", () => {
    const text1 = button.parentElement.querySelector(".item__info-text1").innerText;
    const text2 = button.parentElement.querySelector(".item__info-text2").innerText;
    document.querySelector(".name-form_1").innerText = text1;
    document.querySelector(".name-form_2").innerText = text2;
    document.querySelector(".service_id").value = text2;
  });
});

document.getElementById("button-form").addEventListener("click", function() {
  if(document.getElementById('checked__input_yes').checked){
    document.getElementById("modal__message_yes").classList.add("open");
    
  } else document.getElementById("modal__message_no").classList.add("open");
  document.getElementById("modal-form").classList.remove("open");
});

document.querySelector('#close__modal').addEventListener('click', function() {
  document.querySelector('#modal-form').classList.remove('open');
  });

  document.querySelector('#close__modal_yes').addEventListener('click', function() {
    document.querySelector('#modal__message_yes').classList.remove('open');
    });

    document.querySelector('#close__modal_no').addEventListener('click', function() {
      document.querySelector('#modal__message_no').classList.remove('open');
      });
  
</script>