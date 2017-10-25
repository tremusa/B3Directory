
<?php

//HTML output for Admin Menu
function b3_menu_html(){
  if (!current_user_can('update_core')) {
        return;
    }
    ?>
    <div class="b3menuwrapper">
    <h1> B3 Directory settings </h1><br><br>
    <h2> Your Business Tags </h2><br><br>
    <h2> Your Business Categories </h2><br><br>
    <h2> Search Your Businesses </h2><br><br>
    <form action="<?php echo admin_url('admin-post.php') ?>" method="get">
    <input type="hidden" name="action" value="b3_admin_search">
    <input type="text" name="query" value="Your Search Query">
    <input type="submit" value="Submit">
    </form>
    <?php
}
?>
