<!DOCTYPE html>
<html>
<head>
<title>Error 404 - Page Not Found!</title>
<style type="text/css">
html{background:#ddd;;}
body{background:#fff;color:#333;font-family:"Lucida Grande",Verdana,Arial,sans-serif;margin:1em auto;width:700px;padding:1em 2em;border-radius:11px;border:1px solid #999;}
a{color:#2583ad;text-decoration:none;}
a:hover{color:#d54e21;}
a img{border:0;}
h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px Georgia,"Times New Roman",Times,serif;margin:5px 0 0 -4px; padding-top:5px;}
h1.logo{margin:7px 0 20px 0;border-bottom:none; text-align:center;}
h2{font-size:16px;}p,li,dd,dt,td{padding-bottom:2px;font-size:12px;line-height:18px;}
h3{font-size:14px;}
ul,ol,dl{padding:5px 5px 5px 22px; }
code{font:14px Consolas,Courier New,Verdana;background:#ddf;color:#c00;border:1px solid #D0D0D0;display:block;margin:14px 0;padding:10px;border-radius:8px;}
#foot {text-align: left; margin-top: 1.8em; border-top: 1px solid #dadada; padding-top: 1em; font-size: 0.7em;}
#foot span.right {float:right;}
</style>
</head>
<body>
    <div id="konten">
        <h1>Page not found!</h1>
        <?php if( error_reporting() ): ?>
        <p>Message: <?php echo $message;?></p>
        <?php else: ?>
        <p>The requested URL <?php echo $_SERVER['REQUEST_URI'];?> was not found on this server.</p>
        <?php endif; ?>
    </div>
    <div id="foot">&nbsp;</div>
</body>
</html>
