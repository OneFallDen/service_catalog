<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
?>    
<?php

    use app\core\Router;

    spl_autoload_register(function ($class) {
        $path = str_replace('\\', '/', $class.'.php');
        if(file_exists($path)){
            require $path;
        }
    });

    session_start();

    $router = new Router;
    $router->run();
?>

    <main>
        <section>

            <?php

                require('fields.php');

                // Проверка наличия параметра 'parentSectionId' в запросе
                if (isset($_GET['mainSectionName'])&&isset($_GET['parentSectionId'])) {
                    require('parent_section.php'); 
                } else if (isset($_GET['mainSectionName'])&&isset($_GET['parentServiceId'])&&isset($_GET['catalogParentServiceId'])&&isset($_GET['serviceName'])&&isset($_GET['parentSectionName'])){
                    require('confirm.php'); 
                } else if (isset($_GET['personalAccount'])){
                    require('personal_account.php'); 
                } else if (isset($_GET['history'])) {
                    require('history.php'); 
                } else if (isset($_GET['self_service'])) {
                    require('ind.php'); 
                }

            ?>

        </section>
    </main>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Open+Sans:wght@400;700&family=Righteous&family=Ubuntu:wght@700&family=Urbanist:wght@500;600&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-size: 16px;
    font-family: Open Sans;
}

.workarea-content {
 background-color: #203A6E;
color: #fff;
}

.workarea-content-paddings {
    padding: 0;
}

a {
    text-decoration: none;
    color: #000;
}

ul {
    padding: 0;
}

ul li {
    list-style-type: none;
}

h2,
h3 {
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
}


main {
    min-height: calc(100vh - 320px);
}


/*navigation*/
nav {
    height: 150px;
}

.navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 60px;
    margin: 0;
    padding-inline: 40px;
    margin-bottom: 20px;
}

.navigation__link {
    display: flex;
    align-items: center;
    color: #fff;
    gap: 5px;
    font-family: Ubuntu;
    font-weight: 700;
    font-size: 32px;
    text-transform: uppercase;
}



.come-back {
    margin: 10px 40px;
    background-color: #203A6E;
    cursor: pointer;
    border-style: none;
}

/*footer*/
.footer {
    display: flex;
    justify-content: space-between;
    padding-inline: 100px;
    text-align: center;
    align-items: center;
    height: 200px;
    line-height: 1.5;
}

.footer__item {
    display: flex;
    gap: 30px;
    align-items: center;
}

.application {
    display: flex;
    align-items: center;
    border: solid 1px;
    border-radius: 20px;
    width: 239px;
    padding: 12px;
    justify-content: space-between;
    color: #fff;
}

.footer__block-text1 {
    color: #0070BA;
}

/* section order*/
.name-group {
    text-transform: uppercase;
    font-family: Ubuntu;
    font-weight: 700;
    font-size: 32px;
    margin: 30px 0 30px 40px;
}

.cards {
    display: grid;
    grid-template-columns: repeat(3, 4fr);
    grid-template-rows: 1fr;
    margin-inline: 40px;
    }

.cards a {
    margin: 10px auto;
    width: 90%;
}

.card {
    font-size: 14px;
    position: relative;
    color: #fff;
    font-size: Open Sans;
    font-weight: 400;
    height: 100%;
}

.card:hover {
    background-color: #6882B4;
}

.card__img {
    text-align: center;
}

.card__img-item {
    max-width: 100%;
}

.card__block {
    display: flex;
    margin: 11px 13px;
    justify-content: space-between;
}

.card__transition {
   width: 10%;
}

.card__name {
    width: 85%;
}

/*Section index (home)*/
.hello {
    margin: 0 129px;
}

.hello__text {
    font-family: Ubuntu;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 9px;
}

.block__list {
    margin: 0 auto;
    width: 691px;
    margin-top: 63px;
}

.chapter {
    display: flex;
    align-items: center;
    border-bottom: solid 2px;
    border-color: #0070BA;
    gap: 28px;
    font-weight: 700;
    color: #fff;
    padding: 9px 0 15px;
    margin-bottom: 12px;
}

/*section card*/
.card-block {
    margin: 26px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-block__img {
    width: 28%;
}

.card-block__img img {
    width: 100%;
}

.card-block__info {
    width: 62%;
}

.card-block__info h2,
h3 {
    font-family: Ubuntu;
    font-size: 24px;
    color: #0070BA;
    font-weight: 700;
    text-transform: uppercase;
}

.card-block__info h3 {
    color: #fff;
    font-size: 35px;
    margin-bottom: 10px;
}

.order-block {
    margin: 100px auto;
    width: 486px;
    text-align: center;
}

.order-block__name {
    margin-bottom: 20px;
    font-family: Ubuntu;
    font-weight: 700;
    font-size: 32px;
}

.order-block__inputs input {
    width: 100%;
    height: 54px;
    border-radius: 8px;
    border-color: #fff;
    padding-inline: 12px;
    background-color: #203A6E;
    color: #fff;
    margin-bottom: 15px;
}

.order-block__inputs input::placeholder {
    color: var(--secondary-light-blue, #6882B4);
}

#button {
    background-color: #0070BA;
    color: #fff;
    border-style: none;
    cursor: pointer;
}

/*message*/
.message {
    text-align: center;
    padding-top: 12.5%;
}

.message__text1 {
    font-family: Ubuntu;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 24px;
    margin-bottom: 12px;
}

.nomber-id,
.lich-kab {
    color: #0070BA;
}

/* lich-kabinet */
.block-lk {
    margin: 0 40px;
    display: grid;
    grid-template-columns: repeat(12, 1fr);
}

.block-lk__order-list {
    grid-column: 3 / 9;
}

.block-lk__your-info {
    height: 10px;
    grid-column: 10 / 13;
    text-align: right;
}

.block-lk__your-info img {
    width: 285px;
    height: 299px;
}

.your-info__name {
    font-family: Ubuntu;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 24px;
    margin-top: 30px;
}

.history-orders,
.history-orders2 {
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: solid 1px;
    border-color: #fff;
    border-radius: 20px;
    color: #fff;
    width: 209px;
    margin: 0 0 27px auto;
}

.history-orders2 {
    width: 224px;
}

.history-orders2 img {
    width: 22px;
}

.order-list__item {
    display: flex;
    height: 213px;
    padding-bottom: 9px;
    border-bottom: solid 1px;
    border-color: #6882B4;
    margin-bottom: 10px;
}

.order-list__item img {
    width: 182px;
    height: 198px;
}

.item__info {
    padding-left: 28px;
    width: 100%;
    position: relative;
}

.service {
    font-family: Ubuntu;
    font-weight: 700;
    font-size: 24px;
    margin-top: 30px;
}

.item__info_status {
    margin-top: 5px;
}

.item__info_status img {
    width: 13px;
    height: 13px;
    margin-right: 10px;
}

.cancel {
    position: absolute;
    right: 5px;
    bottom: 5px;
    color: #fff;
    background-color: #0070BA;
    border-radius: 8px;
    padding: 12px;
    cursor: pointer;
}

#cancel-btn {
    border: solid 1px #fff;
    background-color: #203A6E;
}

#open-modal {
    border: none;
}

/* filter */

.filter {
    display: flex;
    width: 100%;
    justify-content: space-between;
    margin: 0 auto 20px;
    align-items: center;
}

.filter__list {
    padding: 12px;
    border: solid 1px #fff;
    border-radius: 10px;
    background-color: #203A6E;
    color: #fff;
    cursor: pointer;
}

.filter__btn {
    background-color: #0070BA;
    border: solid 1px #fff;
    color: #fff;
    padding: 15px;
    border-radius: 20px;
    cursor: pointer;
}

.filter__btn:hover {
    padding: 16px;
}

/* forma */
.modal {
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: #000000aa;
    top: 0;
    left: 0;
    opacity: 0;
    visibility: hidden;
    transition: all 0.8s ease 0s;
}

.modal.open {
    opacity: 1;
    visibility: visible;
}

.form-box {
    width: 800px;
    height: 600px;
    padding: 32px 50px;
    margin: 5% auto;
    background-color: #203A6E;
    border-radius: 10px;
    position: relative;
}

.name-form {
    border-bottom: solid 1px #0070BA;
    padding-bottom: 5px;
    display: flex;
    align-items: end;
    gap: 10px;
}

.name-form_1,
.question {
font-family: Ubuntu;
font-size: 32px;
font-weight: 700;
text-transform: uppercase;
}

.question {
    font-size: 24px;
    margin: 40px 0;
    text-transform: none;
}

.checked {
    margin-bottom: 20px;
    display: flex;
    gap: 15px;
}

.checked__radio_btn input[type=radio] {
    display: none;
}

.checked__radio_btn label {
	display: flex;
    align-items: center;
	cursor: pointer;
	padding: 5px 15px;
	line-height: 34px;
	border: 1px solid #fff;
	border-radius: 20px;
	user-select: none;
}

.checked__img {
    margin-left: 5px;
}

.checked__radio_btn input[type=radio]:checked + label {
	background: #0070BA;
}

.checked__radio_btn label:hover {
	color: #666;
}

.checked__radio_btn input[type=radio]:disabled + label {
	background: #efefef;
	color: #666;
}

#block_yes,
#block__result_no {
    display: none;
}

#block_yes input,
#other__input-text {
    width: 100%;
    background-color: #203A6E;
    border: solid 1px #fff;
    border-radius: 8px;
    padding: 6px;
}

#other__text {
    width: 88%;
    opacity: 0;
    transition:  .5s .3s, opacity .5s;
    margin: 0 0 0 auto;
}

#variant_4:checked ~ #other__text {
    opacity: 1;
}

.block__box {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
}

.box__item {
    display: block;
    height: 32px;
    gap: 5px;
}

.box__item label {
    cursor: pointer;
}

.box__item label:hover {
	color: #666;
}

#other {
    display: flex;
    align-items: center;
}

.button-form {
    pointer-events: none;
    position: absolute;
    right: 40px;
    bottom: 40px;
    padding: 16px;
    background-color: #6882B4;
    border: none;
    border-radius: 8px;
    color:#fff;
    cursor: pointer;
}

.no-click {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 200px;
    height: 200px;
    pointer-events: none;
    background: transparent;
}

.button_close {
    position: absolute;
    right: 20px;
    top: 20px;
    cursor: pointer;
    background-color: #203A6E;
    border: none;
}

.button_close img:hover {
    width: 23px;
}

.form-box__message {
    text-align: center;
    margin-top: 54px;
}

.message__title {
    font-family: Ubuntu;
    font-weight: 700;
    font-size: 24px;
    margin-bottom: 5px;
}

#noDisp {
    display: none;
}

</style>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>