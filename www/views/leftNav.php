<?php
//1 deep presentation of categories
$navCategories = array();
Categories::getHierarchy($navCategories, 1);
?>
<div class="span3">
  <div class="well sidebar-nav">
    <ul class="nav nav-list">
      <?php foreach ($navCategories as $categoryHeader): ?>
        <li class="nav-header"><a href="category.php?cid=<?php echo $categoryHeader->id; ?>"><?php echo $categoryHeader->category; ?></a></li>
        <?php if (isset($categoryHeader->subCategories)): ?>
          <?php foreach ($categoryHeader->subCategories as $categorySub): ?>
             <li><a href="category.php?cid=<?php echo $categorySub->id; ?>"><?php echo $categorySub->category; ?></a></li>
          <?php endforeach; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>
  </div><!--/.well -->
</div><!--/span-->
