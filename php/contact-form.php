<?php
class Database
{
    private static $dbName = 'revelation' ;
    private static $dbHost = 'localhost' ;
    private static $dbUsername = 'revelation';
    private static $dbUserPassword = 'revelation123';

    private static $cont  = null;

    public function __construct() {
        die('Init function is not allowed');
    }

    public static function connect()
    {
       // One connection through whole application
       if ( null == self::$cont )
       {
        try
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);
        }
        catch(PDOException $e)
        {
          die($e->getMessage());
        }
       }
       return self::$cont;
    }

    public static function disconnect()
    {
        self::$cont = null;
    }
}
?>
<?php
$dbcon1=@mysql_connect("localhost","revelation","revelation123",true);
	mysql_select_db('revelation',$dbcon1);
if(!empty($_POST))
{
	$food = 'No Preferences';
	$acc = 'No';
	$name = $_POST['name'];
	$college = $_POST['college'];
	$year = $_POST['year'];
	$dept = $_POST['dept'];
	$email = $_POST['email'];
  $phone = $_POST['tele'];
	if(!empty($_POST['fp']))
	{
  $food = $_POST['fp']; }
	if(!empty($_POST['ac']))
	{
	$acc = $_POST['ac']; }
	$valid = null;
	$token =  substr(md5(microtime()),rand(0,26),5);
	$valid = true;
	$sql2 = "SELECT email from members where email = '$email'";
    	$result = mysql_query($sql2);
    	$num = mysql_num_rows($result);
    	if($num >= 1)
    	{
    		$valid = false;
    	}

    	if($valid === false)
    	{
    	header("location:../index.php?name=$name#error");
    	}
	if ($valid) {
    		$pdo = Database::connect();
    		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		$sql = "INSERT INTO members (name,college,year,contact,email,token,dept,food,ac) values(?,?,?,?,?,?,?,?,?)";
    		$q = $pdo->prepare($sql);
    		$q->execute(array($name,$college,$year,$phone,$email,'ER'.$token,$dept,$food,$acc));
    		Database::disconnect();
    		header("Location:../index.php?name=$name#complete");
    	}

}
?>
