<?php $this->output('header');?>
<body>
<h1 class="logo"><img alt="Logo" src="<?php echo $this->uri->baseUri;?>assets/img/logo.png" /></h1>
<div id="konten">
    <h1>Panada has been installed successfully!</h1>
    <p>This is sample page view. You find this file at:</p>
    <code><?php echo APP;?>views/index.php</code>
    <p>The controller of this page is located it:</p>
    <code><?php echo APP;?>Controllers/Home.php</code>
    <p>Base URL for this application is:</p>
    <code><?php echo $this->uri->baseUri;?></code>

    <h1>Documentation and resource</h1>
    <ul>
        <li>To check your system minimum requirements, please click this <a href="check.php">Checking Tools</a>.</li>
        <li>To see Hello World page from a module sample, <a href="<?php echo $this->location('modSample'); ?>">click here.</a></li>
        <li>To get latest update, follow our twitter <a target="_blank" href="http://twitter.com/panadaframework">@panadaframework</a>.</li>
        <li>To contribute the project, fork on github <a target="_blank" href="https://github.com/panada/Panada">https://github.com/panada/Panada</a>.</li>
        <li>For help and bug report, submit to <a target="_blank" href="https://github.com/panada/Panada/issues">issues</a> page.</li>
    </ul>
    <p>
        To get more hint how to use Panada, download offline <a href="https://github.com/panada/documentation/zipball/master">documentation</a> page from Github.
    </p>
    <p>
        Panada licensed under <a href="<?php echo $this->uri->baseUri; ?>LICENSE">BSD License</a>    
    </p>
</div>

<div id="foot">Powered by <a href="http://panadaframework.com/">Panada</a> version 1.0.0-alfa
</div>
<a href="http://github.com/panada/Panada">
<img src="<?php echo $this->uri->baseUri; ?>assets/img/forkgithub.png" style="position: absolute; top: 0; right: 0; border: 0;" alt="Fork me on GitHub"></a>	
</body>
</html>