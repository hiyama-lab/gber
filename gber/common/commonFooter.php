<?php
include("./common/Constant.php");
echo '<p id="gber-version">Powered by <a href="https://github.com/hiyama-lab/gber">GBER</a> ' . Constant::VERSION . '</p>';
echo "<script type=\"text/javascript\">\n";
echo "var baseurl = \"" . $baseurl . "\";\n";
echo "var autosizebox = autosize(document.querySelectorAll('textarea'));";
//echo "(function(\$) {\$.extend({nl2br: function nl2br(str) {return str.replace(/[\\n\\r]/g, \"<br />\"); } }); })(jQuery);\n";
echo "setTimeout(function(){\$(\"*\").removeClass(\"ui-corner-all\");},100);\n";
echo "</script>\n";
?>