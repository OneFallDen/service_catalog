<header>
        <nav>
            <div class="navigation">
                <a class="navigation__link" href=""><img src="img/каталог.svg" alt="Каталог">каталог</a>
                <a class="navigation__link" href="?personalAccount=1"><img src="img/пользователь.svg"
                    alt="Личный кабинет"></a>
            </div>
            <button onclick="history.go(-1);" class="come-back"><img src="img/стрелка.svg" alt="назад"></button>
        </nav>
    </header>

    <main>
        <section>

            <?php

                echo $content;

            ?>

        </section>
    </main>

    <footer>
        <div class="footer">
            <div>
                <a class=" application" href="?self_service">
                    <p>Оставить свою заявку</p>
                    <img src="img/заполнить форму.svg" alt="">
                </a>
            </div>

            <div class="footer__item">
                <div class="footer__block">
                    <img src="img/телефон.svg" alt="">
                    <p class="footer__block-text1">НОМЕР ТЕЛЕФОНА</p>
                    <p class="footer__block-text2">+79517829209</p>
                </div>
                <div class="footer__block">
                    <img src="img/почта.svg" alt="">
                    <p class="footer__block-text1">EMAIL</p>
                    <p class="footer__block-text2">email@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>