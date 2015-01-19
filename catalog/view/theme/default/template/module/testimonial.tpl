<!--noindex-->
<div class="box">
  <div class="box-heading">
  <?php if ($testimonial_title) { ?> 
  <h2><?php echo $testimonial_title; ?></h2>
  <?php } ?>
  </div>
  <div class="box-content">

<a href="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2508/*http://market.yandex.ru/shop/110686/reviews"><img src="http://clck.yandex.ru/redir/dtype=stred/pid=47/cid=2507/*http://grade.market.yandex.ru/?id=110686&action=image&size=3" border="0" width="190" alt="Читайте отзывы покупателей и оценивайте качество магазина на Яндекс.Маркете" /></a>

<br /><br />

    <div class="testimonial_module">

    <table cellpadding="2" cellspacing="0" style="width: 100%">
      <?php foreach ($testimonials as $testimonial) { ?>
      <tr><td>

          <div class="name"><?php echo $testimonial['title']; ?></div>
                   
           <div class="thumb">
          
          <?php if ($testimonial['video']) { ?>
          <a rel="nofollow" class="fancybox iframe" href="http://www.youtube.com/embed/<?php echo $testimonial['video']; ?>?autoplay=1;rel=0"><span class="video"></span></a>
      <a rel="nofollow" class="fancybox iframe" href="http://www.youtube.com/embed/<?php echo $testimonial['video']; ?>?autoplay=1;rel=0"><img style="width: 80px" src="http://img.youtube.com/vi/<?php echo $testimonial['video']; ?>/default.jpg" alt="" /></a>
      	  <?php } elseif ($testimonial['image']) { ?>          
          <a rel="nofollow" href="<?php echo $testimonial['image']; ?>" class="fancybox"><img src="<?php echo $testimonial['thumb']; ?>" alt="" /></a>
          <?php } ?>
          
          </div>
                   
          <div class="otziv">&laquo;<?php echo $testimonial['description']; ?>&raquo;</div>

          <div class="bottom">

	<?php if ($testimonial['audio']) { ?> 
      <div class="audio">
 <script type="text/javascript" src="catalog/view/javascript/audio/audio-player.js"></script>
<object type="application/x-shockwave-flash" data="catalog/view/javascript/audio/player.swf" id="audioplayer<?php echo $testimonial['id']; ?>" height="20" width="180">
<param name="movie" value="catalog/view/javascript/audio/player.swf">
<param name="FlashVars" value="playerID=<?php echo $testimonial['id']; ?>&amp;soundFile=<?php echo $testimonial['audio']; ?>">
<param name="quality" value="high">
<param name="menu" value="false">
<param name="wmode" value="transparent"></object>
      </div>
     <?php } ?>
     
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

	     <br />
	</div>
     </td>
 </tr>

      <?php } ?>
</table>

	<a rel="nofollow" href="<?php echo $isitesti; ?>" class="buttongreen" style="margin-bottom:20px" ><?php echo $isi_testimonial; ?></a>
				<a rel="nofollow" href="<?php echo $showall_url; ?>" style="display: block; font-style: italic;font-size: 12px;margin: 0 0 0 50px;color:#699043"><?php echo $show_all; ?> >></a>

    </div>
  </div>
</div>
<!--/noindex-->
