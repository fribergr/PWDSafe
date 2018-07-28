<?php
namespace DevpeakIT\PWDSafe\Traits;

trait ClickableLinks {
    # Grabbed from https://stackoverflow.com/questions/5341168/best-way-to-make-links-clickable-in-block-of-text
    private function makeLinksClickable($text){
        return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1">$1</a>', $text);
    }
}