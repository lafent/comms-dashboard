<?php
if (isset($_POST['comments_dashboard'])) {
  $only_pending = $_POST['only_pending'] == 'on' ? '1' : '0';
  self::update_dashboard_widget_options( self::wid, array( 'only_pending' => $only_pending, ) );
} 
else {
  $only_pending = self::get_dashboard_widget_option(self::wid, 'only_pending');
}
?>

<div style="padding-bottom: 5px;">
  <input type="hidden" name="comments_dashboard" value="1" />
  <label for="only_pending">Only Show Blogs with Pending Comments: 
    <input type="checkbox" name="only_pending" <?php echo $only_pending == '1' ? 'checked' : '' ; ?> /> Yes
  </label>
</div>
