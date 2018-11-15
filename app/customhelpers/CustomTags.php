<?php


use Phalcon\Tag;

class CustomTags extends Tag
{
    public static function card($parameters){
        $title = null;
        $body = null;
        // Converting parameters to array if it is not
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        if (isset($parameters['class'])) {
            $class = $parameters['class'];
            unset($parameters['class']);
        }

        if (isset($parameters['body'])) {
            $body = $parameters['body'];
            unset($parameters['body']);
        } 
        if (isset($parameters['title'])) {
            $title = $parameters['title'];
            unset($parameters['title']);
        } 



        // Generate the tag code
        $code = '<div class = "card '. $class.'" ';

        foreach ($parameters as $key => $attributeValue) {
            if (!is_integer($key)) {
                $code.= $key . ' = "' . $attributeValue . '" ';
            }
        }

        $code.='><div class="card-body">';
        if($title!=null){
             $code.='<h5 class="card-title">'.$title.'</h5>';
        }
        if($body!=null){
             $code.=$body;
        }
        $code.=  '</div></div>';


        return $code;
    }
}