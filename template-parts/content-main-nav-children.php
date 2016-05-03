<ul>
  <li class="back">
    <a href="#" class="back">&larr; <?php echo $item['title'] ?></a>
  </li>

  <?php foreach($item['children'] as $child) {?>
  <li<?php if( $bread_crumb[1]['ID'] == $child['ID'] ) echo ' class="current"'; ?>>
    <a href="<?php echo $child['permalink'] ?>"><?php echo $child['title'] ?></a>

    <?php
    echo '<a href="' . $child['permalink']  . '">' . $child['title'] . ' !!! </a>';

    ?>
  </li>
  <?php } ?>
</ul>
