<?php

function render_view(String $template) {
    echo file_get_contents(VIEW_FOLDER . $template . ".view");
}
