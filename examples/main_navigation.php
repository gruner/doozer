// exclude 'Contact Us' and 'Site Map' from the main navigation
<?php main_navigation($exclusions = array('Contact Us','Site Map')); ?>

// outputs
<div id="nav">
  <ul>
    <li id="about-our-office"><a href="about-our-office.php">About Our Office</a></li>
    <li id="about-orthodontics"><a href="about-orthodontics.php">About Orthodontics</a></li>
    <li id="braces-101" class="active"><a href="braces-101.php">Braces 101</a></li>
    <li id="orthodontic-technologies"><a href="orthodontic-technologies.php">Orthodontic Technologies</a></li>
    <li id="emergency-care"><a href="emergency-care.php">Emergency Care</a></li>
  </ul>
</div>