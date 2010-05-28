<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title><?php echo htmlentities($vars['shop_title']); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="<?php echo htmlentities($vars['meta_description']); ?>">
    <meta name="keywords" content="<?php echo htmlentities($vars['meta_keywords']); ?>">
    <style type="text/css">
      @import "<?php echo PX_COMMON_CSS_DIR; ?>/reset.css";
      @import "<?php echo PX_CSS_DIR; ?>/main.css";
    </style>
  </head>
  <body id="home">
    <div id="header">
      <h1><a href="<?php echo PX_HOME; ?>"><?php echo htmlentities($vars['shop_title']); ?></a></h1>
    </div>
    <div id="content">
<?php px_include_template('sidebar', $vars); ?>
<?php px_include_template('searchbar', $vars); ?>