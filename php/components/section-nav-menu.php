<?php

// Register Section Nav_Menu

if (!class_exists('SectionNavMenu')) {

    class SectionNavMenu
    {
        function __construct($data)
        {
            $this->theme_location = $data['theme_location'];
            $this->logo = $data['logo'];
            $this->menu_items = $data['menu_items'];

        }

        public function get_menu_items_html() {
            $menu_items_html = '';
            if (get_field('hide_nav_buttons') == false) {
                $menu_items = $this->menu_items;
                foreach ($menu_items as $menu_item) {
                    if ($menu_item->menu_item_parent == 0) {
                        $menu_items_html .= '
                        <li class="nav-item">
                            <a class="nav-link btn-custom" href="{{menu_item_url}}"> {{menu_item_title}} </a>
                        </li>                    
                    ';

                        $menu_items_html = str_replace('{{menu_item_url}}', $menu_item->url, $menu_items_html);
                        $menu_items_html = str_replace('{{menu_item_title}}', $menu_item->title, $menu_items_html);
                    }
                }
            }

            return '<ul class="navbar-nav mr-auto">' .$menu_items_html. '</ul>';
        }

        public function get_html_section()
        {

            $menu_form = '';

            $menu_list = '
            <nav class="navbar navbar-expand-lg navbar-light px-0 mb-0">
                <a id="home-logo" class="navbar-brand home-logo" href="{{home_url}}">
                    <img class="d-inline-block align-top is-max-width-150-mobile" width="282" height="86" src="{{home_logo_src}}" alt="Softwareplanetgroup">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                
                    {{menu_items_html}}
                    
                    <form class="form-inline my-2 my-lg-0" style="flex-flow: nowrap" action="' . home_url( '/' ) . '">
                    <input class="form-control mr-sm-2" type="search" placeholder="" aria-label="Search" value="' . get_search_query() . '" name="s">
                    <input id="search" class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Search" disabled="disabled">
                    </form>
                    
                </div>
                
            </nav>
            ';

            $menu_list = str_replace('{{home_url}}', get_home_url(), $menu_list);
            $menu_list = str_replace('{{home_logo_src}}', $this->logo, $menu_list);
            $menu_list = str_replace('{{menu_items_html}}', $this->get_menu_items_html(), $menu_list);
            $menu_list = str_replace('{{menu_form}}', $menu_form, $menu_list);

            return $menu_list;

        }

    }

}
