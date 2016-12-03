<?php 
$short_name = $tokens[1];
$location_info = get_location_info($short_name);
$date = "2016-12-5"; //date('Y-m-j', time());
$menu = get_menu($date, $location_info['location_id']);

$periods = $menu->menu->periods;

// Header
get_header("Menu");
?>

<h1><?=$location_info['name']?></h1>

<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">

    <?php foreach($periods as $key => $period): ?>
        <li class="<?php if($key == 0) echo 'active'; ?>" role="presentation">
            <a href="#<?=$period->id?>" data-toggle="tab"><?=$period->name?></a>
        </li>
    <?php endforeach; ?>

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <?php foreach($periods as $key => $period): ?>
        <div role="tabpanel" class="tab-pane <?php if($key == 0) echo 'active'; ?>" id="<?=$period->id?>">

            <?php foreach($period->categories as $category): ?>

                <h3><?=$category->name?></h3>

                <ul>
                <?php foreach($category->items as $item): ?>
                    <li>
                        <b><?=$item->name?>, <?=$item->portion?></b>
                        <p><?=$item->desc?></p>
                    </li>
                <?php endforeach; ?>
                </ul>

            <?php endforeach; ?>

        </div>
    <?php endforeach; ?>
  </div>

</div>

<?php get_footer() ?>