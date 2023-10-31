<?php

    if (isset($_GET['ind_serv'])) {
        if (CModule::IncludeModule("crm")) {
            global $USER;
            
            $comment = addslashes(strip_tags(trim($_GET['comment'])));

            // Проверяем, авторизован ли пользователь в Битрикс24
            if ($USER->IsAuthorized()) {
                // Получаем данные пользователя
                $currentUserId = $USER->GetID();
                $arUser = CUser::GetByID($currentUserId)->Fetch();


                // Выбор случайного пользователя саппорта
                if (CModule::IncludeModule("main")) {
                
                    $groupFilter = array("STRING_ID" => $groupName);
                    $groupDb = CGroup::GetList($by = "c_sort", $order = "asc", $groupFilter);
                    if ($group = $groupDb->Fetch()) {
                        $groupId = $group["ID"];
                
                        $userList = array();
                        $userFilter = array("GROUPS_ID" => array($groupId));
                        $userDb = CUser::GetList(($by = "name"), ($order = "asc"), $userFilter);
                        while ($user = $userDb->GetNext()) {
                            $userList[] = $user;
                        }
                
                        if (!empty($userList)) {
                
                            $randomUser = $userList[array_rand($userList)];
                
                            $rndUser = $randomUser["ID"];
                        }
                    }
                }

                $data = [

                    'TITLE' => "Индивидуальная заявка",
                    'ASSIGNED_BY_ID'=>$rndUser,
                    $name_field => $arUser["NAME"],
                    $last_name_field => $arUser["LAST_NAME"],
                    $email_field => $arUser["EMAIL"],
                    $comment_field => $comment,
                    $mainSectionName_field => "Индивидуальная заявка",
                    "STAGE_ID"  =>  $smartStatus."NEW",
     
                ];
                
                $item = $factory ->createItem($data); 
                
                $item->save();

                $leadId =  $item['ID'];
        
                // Создайте лида
        
                if ($leadId) {

                    $to = $arUser["EMAIL"];
                    $subject = "Создание новой заявки";
                    $message = "Успешно создана заявка на индивидуальную услугу с №$leadId.\nПри изменении статуса заявки Вам придёт письмо.\nТакже отслеживать статус заявки можете в Личном кабинете.";

                    mail($to, $subject, $message);

                    if (CModule::IncludeModule("main")) {
                        $userData = CUser::GetByID($$rndUser)->Fetch();
                        if ($userData) {
                            $userEmail = $userData["EMAIL"];
                        }
                    }

                    $to = $userEmail;
                    $subject = "Создание новой заявки";
                    $message = "Создана заявка на индивидуальную услугу с №$leadId.";

                    mail($to, $subject, $message);

                    echo '
                        <div class="message">
                            <p class="message__text1">Мы получили ваш заказ</p>
                            <p class="message__text2">Номер заказа <span class="nomber-id">'.$leadId.'</span> будет отображаться в <a class="lich-kab" href="?personalAccount=1">личном кабинете</a></p>
                        </div>
                    ';
                    
                }
            }
        }
    } else {

        echo '
        <div class="card-block">
            <div class="card-block__info">
                <h3>Индивидуальная заявка</h3>
                <p>Пожалуйста, опишите свою проблему в форме ниже.</p>
            </div>
        </div>
        ';

        echo '
            <div class="order-block">
                <p class="order-block__name">Заказ услуги</p>
                <form class="order-block__inputs" action="?self_service=ind_serv&comment=comment&ind_serv=ind_serv">
                    <input id="noDisp" name="self_service" type="text" value="1">
                    <input id="noDisp" name="ind_serv" type="text" value="1">
                    <input name="comment" type="text" placeholder="Комментарий">
                    <input id="button" type="submit" value="Отправить">
                </form>
            </div>
        ';

    }

?>