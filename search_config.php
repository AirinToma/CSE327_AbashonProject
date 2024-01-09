<?php
error_reporting(0);
class db{
	var $con;
	function __construct(){
		$this->$con=mysqli_connect("localhost","root","") or die(mysqli_error());
		mysqli_select_db($this->$con,"abashon") or die(mysqli_error());
	}
	public function getRecords(){
		$query="SELECT * from house where availability = \"Yes\"";
		$result=mysqli_query($this->$con,$query);
		return $result;
	}
	public function getRecordsWhere($rent){
		$rent = $rent + 1;
		$query="SELECT * from house where availability = \"Yes\" and rent < ".$rent."";
		$result=mysqli_query($this->$con,$query);
		return $result;
	}

	public function getRecordsWhereFac($fac){
		//Option names get passed, not their values
			if($fac == "Generator"){
				$query="SELECT * from house where availability = \"Yes\" and generator = \"Yes\"";
			}elseif($fac == "Lift"){
				$query="SELECT * from house where availability = \"Yes\" and lift = \"Yes\"";
			}else{
				$query="SELECT * from house where availability = \"Yes\" and generator = \"Yes\" and lift = \"Yes\"";
			}
		$result=mysqli_query($this->$con,$query);
		return $result;
	}

	public function getRecordsWhereBlock($block){
		$query="SELECT * from house where availability = \"Yes\" and block = \"$block\"";
		$result=mysqli_query($this->$con,$query);
		return $result;
	}
	public function closeCon(){
		mysqli_close($this->$con);
	}
}
