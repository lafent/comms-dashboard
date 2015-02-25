<?php
  $only_pending = self::get_dashboard_widget_option(self::wid, 'only_pending');
?>
<div class="communications-dashboard-container">
  <?php
    if (is_multisite()) {
      $sites = wp_get_sites();
      $found_comments = false;
      foreach ($sites as $site) {
        switch_to_blog($site['blog_id']);
        $details = get_blog_details($site['blog_id']);
        $comments = wp_count_comments();
        if ($only_pending == '1') {
          if ($comments->moderated > 0) {
            $found_comments = true;
            echo "<div style=\"padding-bottom: 5px;\">";
            echo "<div style=\"background-color: #cbcbcb;\"><strong>{$details->blogname}</strong></div>";
            echo "<div><strong>Waiting: {$comments->moderated}</strong> ";
            echo "[<a href=\"{$details->siteurl}/wp-admin/edit-comments.php\">View Comments</a>]</div>";
            echo "<div>Approved: {$comments->approved}</div>";
            echo "<div>Spam: {$comments->spam}</div>";
            echo "<div>Trash: {$comments->trash}</div>";
            echo "<div>Total Comments: {$comments->total_comments}</div>";
            echo "</div>";
          }
        }
        else {
          $found_comments = true;
          echo "<div style=\"padding-bottom: 5px;\">";
          echo "<div style=\"background-color: #cbcbcb;\"><strong>{$details->blogname}</strong></div>";
          echo "<div><strong>Waiting: {$comments->moderated}</strong> ";
          echo "[<a href=\"{$details->siteurl}/wp-admin/edit-comments.php\">View Comments</a>]</div>";
          echo "<div>Approved: {$comments->approved}</div>";
          echo "<div>Spam: {$comments->spam}</div>";
          echo "<div>Trash: {$comments->trash}</div>";
          echo "<div>Total Comments: {$comments->total_comments}</div>";
          echo "</div>";
        }
      }
      if ( ! $found_comments ) {
        echo "<h4>No Comments Need Moderation</h4>";
      }
    } 
    else {
      echo "<h4>This Plugin only works for Multisite Networks</h4>";
    }
  ?>
</div>
