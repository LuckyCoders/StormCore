<?php
switch ($requested_page){
    case 'main':
        $meta = array(
            'title'=>'Главная страница',
            'desc'=>'Основная страница движка, на ней вы можете разместить свой контект и проверить как всё работает.',
            'keywords'=>array('Главная','страница',ProjectName)
        );
        $template = 'site_main';
        break;
    case '404':
        $meta = array(
            'title'=>'Ошибка 404',
            'desc'=>'Запрашиваемая вами страница не найдена.'
        );
        $template = 'site_404';
        break;
    default:
        $template = 'site_404';
}