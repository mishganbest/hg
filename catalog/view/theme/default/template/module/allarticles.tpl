<div class="box">
<div class="box-heading"><?php echo $heading_title; ?></div>
<div class="box-content">
<div class="box-product">
  
<?php foreach ($allarticles as $allarticles) { ?>
<div>
      <div class="image"><a href="<?php echo $allarticles['href']; ?>"><img src="<?php echo $allarticles['thumb']; ?>" title="<?php echo $allarticles['name']; ?>" alt="<?php echo $allarticles['name']; ?>" /></a></div>
      <div class="name"><a href="<?php echo $allarticles['href']; ?>"><?php echo $allarticles['name']; ?></a></div>
</div>
<?php } ?>
</div>
</div>
</div>