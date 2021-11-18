<?php

// подключаем все файлы в /includes
foreach (glob("./includes/*/*.php") as $filename) {
    include $filename;
}

// подключаем страницу с html-разметкой
require ("./partials/home.html");