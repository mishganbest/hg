<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>  
<div id="content"><?php echo $content_top; ?>

   
    <?php if ($testimonials) { ?>
    
    <div class="testimonials">
    
      <?php foreach ($testimonials as $testimonial) { ?>
<div class="testimonial_block">

      <div class="thumb">
          
          <?php if ($testimonial['video']) { ?>
       <a class="fancybox iframe" href="http://www.youtube.com/embed/<?php echo $testimonial['video']; ?>?autoplay=1;rel=0"><span class="video"></span></a>  
      <a class="fancybox iframe" href="http://www.youtube.com/embed/<?php echo $testimonial['video']; ?>?autoplay=1;rel=0"><img style="width: 100px" src="http://img.youtube.com/vi/<?php echo $testimonial['video']; ?>/default.jpg" alt="" /></a>
      	  <?php } elseif ($testimonial['image']) { ?>          
          <a href="<?php echo $testimonial['image']; ?>" class="fancybox"><img src="<?php echo $testimonial['thumb']; ?>" alt="" /></a>
          <?php } ?>
          
          </div>
        
      <?php if ($testimonial['video'] || $testimonial['image']) { ?>   
      <div class="middle">&laquo;<?php echo $testimonial['description']; ?>&raquo;</div>
      <?php } else { ?>	
      <div class="middle_nothumb">&laquo;<?php echo $testimonial['description']; ?>&raquo;</div>
      <?php } ?>
      
      <div class="middle-bottom">
      
    <?php if ($testimonial['audio']) { ?> 
   <?php if ($testimonial['image']) { ?>
      <div class="audio2">
<?php } else { ?>
<div class="audio">
<?php } ?>
 <script type="text/javascript" src="catalog/view/javascript/audio/audio-player.js"></script>
<object type="application/x-shockwave-flash" data="catalog/view/javascript/audio/player.swf" id="audioplayer<?php echo $testimonial['id']; ?>" height="24" width="290">
<param name="movie" value="catalog/view/javascript/audio/player.swf">
<param name="FlashVars" value="playerID=<?php echo $testimonial['id']; ?>&amp;soundFile=<?php echo $testimonial['audio']; ?>">
<param name="quality" value="high">
<param name="menu" value="false">
<param name="wmode" value="transparent"></object>
      </div>
     <?php } ?>
      
    <div class="info">  
      <span>
      <?php if ($testimonial['name'] && $testimonial['city']) { ?> 
		<?php echo $testimonial['name']; ?>, 
	<?php } else { ?>	
		<?php echo $testimonial['name']; ?>
	<?php } ?>
	<?php if ($testimonial['city']) { ?> 
		г. <?php echo $testimonial['city']; ?>
	<?php } ?>
      </span>
	<?php if ($testimonial['phone']) { ?>
      <span>Телефон: <?php echo $testimonial['phone']; ?></span>
      <?php } ?>
      <?php if ($testimonial['email']) { ?>
      <span><?php echo $testimonial['email']; ?></span>
      <?php } ?>
      <?php if ($testimonial['vk']) { ?>
      <span><a href="<?php echo $testimonial['vk']; ?>" target="_blank"><?php echo $testimonial['vk']; ?></a></span>
      <?php } ?>
      <?php if ($testimonial['odnoklass']) { ?>
      <span><a href="<?php echo $testimonial['odnoklass']; ?>" target="_blank"><?php echo $testimonial['odnoklass']; ?></a></span>
      <?php } ?> 
      </div>
      </div>
      
 </div>     
      <?php } ?>
      
</div>
    	<?php if ( isset($pagination)) { ?>
    		<div class="pagination"><?php echo $pagination;?></div>
    		<div class="buttons" align="right"><a class="button" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><span><?php echo $write;?></span></a></div>
    	<?php }?>

    	<?php if (isset($showall_url)) { ?>
    		<div class="buttons" align="right"><a class="button" href="<?php echo $write_url;?>" title="<?php echo $write;?>"><span><?php echo $write;?></span></a> &nbsp;<a class="button" href="<?php echo $showall_url;?>" title="<?php echo $showall;?>"><span><?php echo $showall;?></span></a></div>
    	<?php }?>
    <?php } ?>
 
<?php echo $content_bottom; ?></div>
<?php echo $footer; ?> 