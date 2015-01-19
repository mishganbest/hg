<?php echo $header; ?><h1><?php echo $heading_title; ?></h1><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="contact">
    
    <div class="contact-info">
      <div class="content">
   
     <?php if ($contact_description) { ?>
    <h2><?php echo $text_contact_info; ?></h2>
    <div style="overflow: hidden; padding-top: 15px"><?php echo $contact_description; ?></div>
    <?php } ?>
      
    </div></div>
  </form>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>