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

    public static function comment($parameters) {
        $class = null;
        $username = null;
        $time = null;
        $usertype = null;
        $commenttext = null;

        // Converting parameters to array if it is not
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }

        if (isset($parameters['class'])) {
            $class = $parameters['class'];
            unset($parameters['class']);
        }
        if (isset($parameters['username'])) {
            $username = $parameters['username'];
            unset($parameters['username']);
        }
        if (isset($parameters['time'])) {
            $time = $parameters['time'];
            unset($parameters['time']);
        }
        if (isset($parameters['usertype'])) {
            $usertype = $parameters['usertype'];
            unset($parameters['usertype']);
        }
        if (isset($parameters['commenttext'])) {
            $commenttext = $parameters['commenttext'];
            unset($parameters['commenttext']);
        }

        // Generate the tag code
        $code = '<div class = "card '. $class.'"';
        $code .= '><div class="card-body"><h5 class="card-title">';
        if ($username != null) {
            $code .= $username;
        }
        if ($time != null) {
            $code .= ', <span class="small text-mute">' . $time . '</span>';
        }
        if ($usertype != null) {
            $code .= ' <span class="small text-secondary">' . $usertype . '</span>';
        }
        $code .= '</h5>';
        if ($commenttext != null) {
            $code .= '<p class="card-text">' . $commenttext . '</p>';
        }
        $code .= '</div></div>';

        return $code;
    }
    //пагинация, паря
    public static function pagination($parameters){
        $next = null;
        $before = null;
        $current = null;
        $count = null;
        //ссылка, для перехода, пишется обычная ссылка, и на месте, где ложен быть номер страницы, ставится %d
        //например "/sdkfsdf/%d"
        $link = null;
        // Converting parameters to array if it is not
        if (!is_array($parameters)) {
            $parameters = [$parameters];
        }
        if (isset($parameters['count'])) {
            $count = $parameters['count'];
            unset($parameters['count']);
        }
        if($count<2){
            return '';
        }

        if (isset($parameters['next'])) {
            $next = $parameters['next'];
            unset($parameters['next']);
        }

        if (isset($parameters['before'])) {
            $before = $parameters['before'];
            unset($parameters['before']);
        }

        if (isset($parameters['current'])) {
            $current = $parameters['current'];
            unset($parameters['current']);
        } 

        if (isset($parameters['link'])) {
            $link = $parameters['link'];
            unset($parameters['link']);
        }

        // Generate the tag code
        $code = '<nav aria-label="Page navigation example"><ul class="pagination">';
        $linkRes = sprintf($link,$before);
        $code.='<li class="page-item">'.
                Phalcon\Tag::linkTo(
                    [
                        $linkRes,
                        '<',
                        "class" => "page-link",
                    ]).'</li>';

        for ( $i=1; $i<=$count; $i++) {
                $linkRes = sprintf($link,$i);
                if($i!=$current)
                    $code.='<li class="page-item">'.
                    Phalcon\Tag::linkTo(
                        [
                            $linkRes,
                            $i,
                            "class" => "page-link",
                        ]).'</li>';
                else
                    $code.='<li class="page-item active"> <a class="page-link text-light">'.$i.'</a></li>';
        }       
        $linkRes = sprintf($link,$next);
        $code.='<li class="page-item">'.
                Phalcon\Tag::linkTo(
                    [
                        $linkRes,
                        '>',
                        "class" => "page-link",
                    ]).'</li>';

        $code.=  '</ul></nav>';
        return $code;
    }
}