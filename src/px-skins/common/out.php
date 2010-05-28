<?php

  if(array_key_exists('id', $_GET))
  {
    px_redirect(px_decode_affiliate_url($_GET['id']));
  }
  else
  {
    px_redirect(PX_RELATIVE_ROOT.PX_SCRIPT_NAME);
  }
