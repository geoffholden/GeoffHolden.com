  <div id="sidebar">
    <ul>
      <li id="searchform">
        <h2>Search</h2>
        <form method="get" action="http://www.google.com/custom">
          <input name="q" type="text" />
          <input name="sitesearch" value="<?php echo preg_replace('/http:\/\/(www.)?/i', "", get_bloginfo('url')); ?>" type="hidden" />
          <p><small><a href="http://www.google.com/search">Powered by Google</a></small></p>
        </form>
      </li>

      <li class="about-me">
        <h2><a href="<?php $page = get_page_by_title('About Me'); echo get_permalink($page->ID); ?>" title="About Me">About</a></h2>
        <p>Name: <a href="http://resume.geoffholden.com/" title="My Resum&eacute;">Geoff Holden</a></p>
        <p>Location:
          <a href="http://maps.google.ca/?z=12&amp;q=St.%20John%27s,%20NL">St. John's, Newfoundland, Canada</a>
        </p>
        <p>Occupation: <a href="http://resume.geoffholden.com/" title="My Resum&eacute;">Software Engineer</a></p>
      </li>

      <?php if (!function_exists('dynamic_sidebar')
            || !dynamic_sidebar()) : ?>
      <?php endif; ?>
    </ul>
  </div>

