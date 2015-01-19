<?php echo $header; ?><h1><?php echo $heading_title; ?></h1><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
  <?php if ($thumb || $description) { ?>
  <div class="category-info">
    <?php if ($thumb) { ?>
    <div class="image"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
    <?php } ?>
    <?php if ($description) { ?>
    <?php echo $description; ?>
    <?php } ?>
  </div>
  <?php } ?>
  <?php if ($articles) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="category-list">
    <?php if (count($articles) <= 5) { ?>
    <ul>
      <?php foreach ($articles as $article) { ?>
      <li><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($articles);) { ?>
    <ul>
      <?php $j = $i + ceil(count($articles) / $column); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($articles[$i])) { ?>
      <li><a href="<?php echo $articles[$i]['href']; ?>"><?php echo $articles[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>
  
  <?php echo $content_bottom; ?></div>

<?php echo $footer; ?>