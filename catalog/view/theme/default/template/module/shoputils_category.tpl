<style type="text/css">
#shoputils_category ul{padding:0;  margin: 0;}
#shoputils_category ul li{list-style:none;padding: 5px 5px 5px 0;}
#shoputils_category a{text-decoration: none; color: #000000;}
#shoputils_category a:hover{text-decoration: underline; color: #000000;}
#shoputils_category > ul > li + li {border-top: 1px solid #EEEEEE;}
#shoputils_category > ul > li > ul > li + li {border-top: 1px solid #EEEEEE;}
#shoputils_category > ul > li > ul > li > ul > li + li {border-top: 1px solid #EEEEEE;}
</style>

<?php
/*
* Shoputils
 *
 * ПРИМЕЧАНИЕ К ЛИЦЕНЗИОННОМУ СОГЛАШЕНИЮ
 *
 * Этот файл связан лицензионным соглашением, которое можно найти в архиве,
 * вместе с этим файлом. Файл лицензии называется: LICENSE.1.5.x.RUS.txt
 * Так же лицензионное соглашение можно найти по адресу:
 * http://opencart.shoputils.ru/LICENSE.1.5.x.RUS.txt
 * 
 * =================================================================
 * OPENCART 1.5.x ПРИМЕЧАНИЕ ПО ИСПОЛЬЗОВАНИЮ
 * =================================================================
 *  Этот файл предназначен для Opencart 1.5.x. Shoputils не
 *  гарантирует правильную работу этого расширения на любой другой 
 *  версии Opencart, кроме Opencart 1.5.x. 
 *  Shoputils не поддерживает программное обеспечение для других 
 *  версий Opencart.
 * =================================================================
*/

function is_expanded($parent){
    if ($parent){
        return $parent['expanded'] == 1;
    } else {
        return true;
    }
}

function renderCategories($parent, $categories, $expand_by_button, $expand_by_text){
    if (count($categories) > 0){
        if (is_expanded($parent)){
            echo '<ul ' . ($parent ? 'style="padding-left:12px;"' : '') . 'id="category_'.$parent['id'].'">';
        } else {
            echo '<ul style="display:none;' . ($parent ? 'padding-left:12px;"' : '') . '" id="category_'.$parent['id'].'">';
        }
        foreach ($categories as $category){

            if ((count($category['children']) > 0) && ($expand_by_button)){
                echo '<li>';
                if (is_expanded($category)){
                    echo '<img src="/catalog/view/theme/default/image/btn-collapse.png" class="category_button" style="cursor:pointer; padding-right:2px;" id="image-'.$category['id'].'">';
                } else {
                    echo '<img src="/catalog/view/theme/default/image/btn-expand.png" class="category_button" style="cursor:pointer; padding-right:2px;" id="image-'.$category['id'].'">';
                }
            } else {
                echo '<li style="padding-left:12px">';
            }

            if ($category['is_current']) echo '<b>';

            if (count($category['children']) > 0){
                if ($expand_by_text){
                    echo '<a class="category_id" style="cursor:pointer;margin-top:-14px; padding-left:12px; display: inline-block" href="#" title="'.$category['alt'].'" alt="'.$category['alt'].'" id="button-'.$category['id'].'">'.$category['name'].'</a>';
                } else if ($expand_by_button){
                    if ($category['is_current']){
                        echo $category['name'];
                    } else {
                        echo '<a href="'.$category['href'].'" title="'.$category['alt'].'" alt="'.$category['alt'].'">'.$category['name'].'</a>';
                    }
                } else {
                    echo '<a class="category_id" style="cursor:pointer" href="#" title="'.$category['alt'].'" alt="'.$category['alt'].'" id="button-'.$category['id'].'">'.$category['name'].'</a>';
                }
            } else {
                if ($category['is_current']){
                    echo $category['name'];
                } else {
                    echo '<a href="'.$category['href'].'" title="'.$category['alt'].'" alt="'.$category['alt'].'">'.$category['name'].'</a>';
                }
            }

            if ($category['is_current']) echo '</b>';

            renderCategories($category, $category['children'], $expand_by_button, $expand_by_text);
            echo '</li>';
        }
        echo '</ul>';
    }

}
?>
<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div id="shoputils_category" class="middle"><?php renderCategories(null, $categories, $expand_by_button == 1, $expand_by_text == 1) ?></div>
  </div>
</div>

<script language="JavaScript">
    if(!Array.indexOf){
	    Array.prototype.indexOf = function(obj){
	        for(var i=0; i<this.length; i++){
	            if(this[i]==obj){
	                return i;
	            }
	        }
	        return -1;
	    }
	}
    function categoryAdd(id) {
        var ids = new String($.cookie('expanded')).split(',');
        if (ids.indexOf(id) == -1){
            ids.push(id);
            $.cookie('expanded', ids.join(','), {path: '/'});
        }
    }
    function categoryRemove(id) {
        var ids = new String($.cookie('expanded')).split(',');
        // bug #7654 fixed
        while (ids.indexOf(id) != -1) {
            ids.splice(ids.indexOf(id), 1);
        }
         $.cookie('expanded', ids.join(','), {path: '/'});
    }

     <?php if($expand_by_button) :?>
    $('.category_button').click(function(){
        var button = $(this);
        var category_id = button.attr('id').split('-')[1];
        if ($('#category_'+category_id).css('display') == 'none'){
            button.attr('src', 'catalog/view/theme/default/image/btn-collapse.png');
            categoryAdd(category_id);
        } else {
            button.attr('src', 'catalog/view/theme/default/image/btn-expand.png');
            categoryRemove(category_id);
        }
        $('#category_'+category_id).toggle(200);
    });
    <?php endif;?>
    <?php if($expand_by_text) :?>
    $('.category_id').click(function(e){
        e.preventDefault();
        var anchor = $(this);
        var category_id = anchor.attr('id').split('-')[1];
        var button = $('#image-'+category_id);
        if ($('#category_'+category_id).css('display') == 'none'){
            button.attr('src', 'catalog/view/theme/default/image/btn-collapse.png');
            categoryAdd(category_id);
        } else {
            button.attr('src', 'catalog/view/theme/default/image/btn-expand.png');
            categoryRemove(category_id);
        }
        $('#category_'+category_id).toggle(200);
    });
    <?php endif;?>
</script>
