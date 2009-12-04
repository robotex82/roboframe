<? include('../config/bootstrap.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>default</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="stylesheets/default.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div id="content">
    <h1>Framework Settings</h1>
    <hr />
    <h2>Environment</h2>
    <b>Roboframe:</b> <?= getenv('ROBOFRAME_ENV') ?>
    <hr />
    <h2>Constants</h2>
    <table>
      <tr>
        <th>Name</th>
        <th>Value</th>
        <th>Defined in</th>
      </tr>
      <tr>
        <td>APP_BASE</td>
        <td><?= APP_BASE ?></td>
        <td>config/bootstrap.php</td>
      </tr>
      <tr>
        <td>DATA_BASE</td>
        <td><?= DATA_BASE ?></td>
        <td>config/bootstrap.php</td>
      </tr>
      <tr>
        <td>LIBRARY_PATH</td>
        <td><?= LIBRARY_PATH ?></td>
        <td>config/bootstrap.php</td>
      </tr>
      <tr>
        <td>FRAMEWORK_PATH</td>
        <td><?= FRAMEWORK_PATH ?></td>
        <td>config/bootstrap.php</td>
      </tr>
      <tr>
        <td>APPLICATION_ROOT</td>
        <td><?= APPLICATION_ROOT ?></td>
        <td>config/bootstrap.php</td>
      </tr>
      <tr>        
        <td>PAGE_ROOT</td>
        <td><?= PAGE_ROOT ?></td>
        <td>config/bootstrap.php</td>
      </tr>    
    </table>
  </div>
</body>
</html>