  <?php
  #remove the directory path we don't want
  //$request  = str_replace("/envato/pretty/php/", "", $_SERVER['REQUEST_URI']);

include('modules/functions.php');

  $request  = $_SERVER['REQUEST_URI'];
 
  #split the path by '/'
  $path = parse_url($request);
  $params     = explode("/", $path['path']);

  if(!empty($params[2]))
  {
    $page = $params[1].'/'.$params[2];
  }
  else {
    $page = $params[1];
  }
  if($page == '') {
    $page = 'home';
  }
  $path = 'pages/'.$page.'.php';
  if(file_exists($path)) {
    include($path);
    exit();
  }
  else {
    //echo $page;
    http_response_code(404);
    include('pages/404.php');
    //exit();
  }
?>
