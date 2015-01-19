<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
      <ul>
        <?php foreach ($articles as $article) { ?>
        <li style="padding: 4px 0">
          <?php if ($article['article_id'] == $article_id) { ?>
          <a style="font-size: 12px" href="<?php echo $article['href']; ?>" class="active"><?php echo $article['name']; ?></a>
          <?php } else { ?>
          <a style="font-size: 12px" href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a>
          <?php } ?>
          <?php if ($article['children']) { ?>
          <ul>
            <?php foreach ($article['children'] as $child) { ?>
            <li style="padding: 6px 0 0 15px">
              <?php if ($child['article_id'] == $child_id) { ?>
              <a style="font-size: 12px" href="<?php echo $child['href']; ?>" class="active"> - <?php echo $child['name']; ?></a>
              <?php } else { ?>
              <a style="font-size: 12px" href="<?php echo $child['href']; ?>"> - <?php echo $child['name']; ?></a>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</div>
