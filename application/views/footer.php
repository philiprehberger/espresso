<?php

/**
 * @file       /application/views/footer.php
 * 
 * @project    unitingteachers.com 
 * @category   Uniting Teachers
 * @package    m_lessonplans
 * @link       http://dcsuniverse.com
 * @link       http://unitingteachers.com
 * @author     Philip Rehberger <me@philiprehberger.com>
 * @copyright  ©2016 DCS Universe
 * @version    2.0.2.0
 * @since      1.0.0.0
 * @date       Oct 12, 2014
 * 
 */

?>
</body>
<script>
<?php
if (isset($products) === TRUE) {
    echo '   var products=' . json_encode($products) . ';' . "\n";
}
if (isset($sessionDATA) === TRUE) {
    echo '   var sessionDATA=' . json_encode($sessionDATA) . ';' . "\n";
}
if (isset($scriptDATA) === TRUE) {
    echo '   var scriptDATA=' . json_encode($scriptDATA) . ';' . "\n";
}
if (isset($userDATA) === TRUE) {
    echo '   var userDATA=' . json_encode($userDATA) . ';' . "\n";
}
if (isset($baseURL) === TRUE) {
    echo '   var baseURL="' . $baseURL . '";' . "\n";
}

?>
</script>
<!--<script src="http://unitingteachers.com/public/plugins/jquery.mobile/jquery.mobile-1.4.5.min.js" type="text/javascript"></script>-->
</html>