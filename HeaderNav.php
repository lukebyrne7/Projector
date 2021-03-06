<?php
require_once('Globals.php');
if (!isset($selectedNav)) $selectedNav = "NavGallery";
?>
<!-- Header -->
<div id="HeaderBgDiv">
  <a href="index.php">
  <div id="HeaderDiv">
    <div id="HeaderImg">
      <?php if (isset($PROJECTOR['logo'])) : ?>
      <img src="<? echo $PROJECTOR['logo'] ?>" width="48" height="24" alt="Projector Home"/>
      <?php endif; ?>
      <h1><? echo $PROJECTOR['thename'] ?></h1>
    </div>
  </div>
  </a>
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
  	<!-- SEARCH -->
    <div id="NavSearchTextContainer">
    <!--input type="text" id="NavSearchText" placeholder="Search ..."-->
    </div>
    <!--input type="submit" class="searchButton" id="submit" value=""-->
  </div>
</div>
<div class="clearFloat"></div>
