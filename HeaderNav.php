<?php
if (!isset($selectedNav)) $selectedNav = "NavGallery";
?>
<!-- Header -->
<div id="HeaderBgDiv">
  <div id="HeaderDiv">
    <div id="HeaderImg">
      <a href="#"><img src="images/headerlogo.png" width="48" height="24"></a>
    <!--<span class="projectorTitle">The Projector</span> -->
    	<h1>The Projector</h1>
    </div>
  </div>
</div>

<!-- Navigation -->
<div id="NavDiv">
    <a <?php ($selectedNav == 'NavHome') ? print 'class="navDown"' : print 'class="navUp"'; ?> href="index.php">
        <div id="NavHome" <?php ($selectedNav == 'NavHome') ? print 'class="NavItemDown"' : print 'class="NavItemUp"'; ?>>
        HOME
        </div>
    </a>
    <a <?php ($selectedNav == 'NavGallery') ? print 'class="navDown"' : print 'class="navUp"'; ?> href="Gallery.php">
        <div id="NavGallery" <?php ($selectedNav == 'NavGallery') ? print 'class="NavItemDown"' : print 'class="NavItemUp"'; ?>>
        PROJECT GALLERY
        </div>
    </a>
    <a <?php ($selectedNav == 'NavAbout') ? print 'class="navDown"' : print 'class="navUp"'; ?> href="About.php">
        <div id="NavAbout" <?php ($selectedNav == 'NavAbout') ? print 'class="NavItemDown"' : print 'class="NavItemUp"'; ?>>
        ABOUT
        </div>
    </a>
  <div id="NavSearchContainer">
    <div id="NavSearchTextContainer">
      <input type="text" id="NavSearchText" placeholder="Search ...">
    </div>
    <!--             <div id="NavSearch">
                   Search ...--> 
    <!--form id="searchbox" action="">
                    <input type="text" id="NavSearch" value="Search ...">
        
                    </form-->
    <input type="submit" class="searchButton" id="submit" value="">
  </div>
</div>
<div class="clearFloat" />