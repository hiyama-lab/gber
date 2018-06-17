<?php
function echoWorkCell($workid, $groupno, $worktitle, $workday, $content, $match){
    $scoremsg = "";
    if($match != UNDEFINED_SCORE){
        $scoremsg = "<div class=\"score-box\"><h3>スコア</h3><span class=\"score\">$match</span></div>";
    }
    if(mb_strlen($worktitle) > 10){
        $worktitle = mb_substr($worktitle, 0, 10, "utf-8") . "...";
    }
    if(mb_strlen($content) > 14){
        $content = mb_substr($content, 0, 14, "utf-8") . "...";
    }
    echo "<li data-theme=\"c\"><a href=\"quotation.php?workid=$workid&groupno=$groupno\" rel=\"external\">"
        . "<div class=\"work-box\"><h2>" . h($worktitle) . "</h2>"
        . "<p><strong>$workday</strong></p>"
        . "<p>" . h($content) . "</p></div>"
        . $scoremsg
        .  "</a></li>\n";
}
?>