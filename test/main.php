<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title></title>
    <style>
        .iframe { height: 100%; width: 100%; border: 0; }
        .iframe-div { width: 480px; height: 500px; border: 3px solid black; background-color: lightgray; }
		.iframe-div-r { width: 480px; height: 800px; border: 3px solid black; background-color: lightgray; }
    </style>
</head>
<body>
    <h1>PRIMARY PAGE</h1>
    <div id="quote-tool" class="iframe-div">
        <iframe id="quote-tool-frame" class="iframe" src="http://quotegen.macmannis.com/index.php?s=2" sandbox="allow-forms allow-scripts" seamless></iframe>
    </div>
	<script type="text/javascript">
		//function resize() {
		//	var url = document.getElementById('quote-tool-frame').src;
		//	if(url.indexOf("http://quotegen.macmannis.com/register.php?q=") > -1) {
		//		document.getElementById('quote-tool').className = 'iframe-div-r';
		//	}
		//}
		
		//document.getElementById('quote-tool-frame').onload = resize;
	</script>
</body>