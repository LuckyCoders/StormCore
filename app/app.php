<?php

if($module == 'site') {
    $text_to_output = "Text " . (3 - 2) . (3 - 1) . (3 + 0) . '...';
    $this->replacer("{test_tag}", $text_to_output);
}

