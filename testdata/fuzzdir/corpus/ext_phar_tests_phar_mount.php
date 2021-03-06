<?php
$fname = dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.phar.php';
$pname = 'phar://' . $fname;
$fname2 = dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.phar.tar';

$a = new Phar($fname);
$a['index.php'] = '<?php
Phar::mount("testit", "' . addslashes(__FILE__) . '");
try {
Phar::mount("testit", "' . addslashes(__FILE__) . '");
} catch (Exception $e) {
echo $e->getMessage() . "\n";
}
try {
Phar::mount("' . addslashes($pname) . '/testit1", "' . addslashes(__FILE__) . '");
} catch (Exception $e) {
echo $e->getMessage() . "\n";
}
?>';
$a->setStub('<?php
set_include_path("phar://" . __FILE__);
include "index.php";
__HALT_COMPILER();');
Phar::mount($pname . '/testit1', __FILE__);
include $fname;
// test copying of a phar with mounted entries
$b = $a->convertToExecutable(Phar::TAR);
$b->setStub('<?php
set_include_path("phar://" . __FILE__);
include "index.php";
__HALT_COMPILER();');
try {
include $fname2;
} catch (Exception $e) {
echo $e->getMessage(),"\n";
}
try {
Phar::mount($pname . '/oops', '/home/oops/../../etc/passwd:');
} catch (Exception $e) {
echo $e->getMessage(),"\n";
}
Phar::mount($pname . '/testit2', $pname . '/testit1');
echo substr($a['testit2']->getContent(),0, 50),"\n";
?>
===DONE===
