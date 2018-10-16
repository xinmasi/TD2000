<?php
	class Wage{
		// 用户uid
		private $id;
		// 年
		private $year;
		//月
		private $month;
		//历史工资 总条数
		private $length;
		
		public function __construct($uid = null) {
        $this->id = $uid ? $uid : $_SESSION['LOGIN_UID'];
		}
		
		public function getWage($flow_id = null,$time){
				if(!isset($time)){
					echo  _("时间不对");
					exit;
				}
				if($flow_id){
					$query = "select sal_year,sal_month from sal_flow where flow_id = '$flow_id'";
					$cursor1= exequery(TD::conn(),$query);
					if($row = mysql_fetch_array($cursor1)){
						$this -> year = $row["sal_year"];
						$this -> month = $row["sal_month"];
					}
				}else{
					$query = "select flow_id,sal_year,sal_month from sal_flow where BEGIN_DATE like '%$time%' order by END_DATE desc";
					$cursor1= exequery(TD::conn(),$query);
					if($row = mysql_fetch_array($cursor1)){
						$flow_id = $row["flow_id"];
						$this -> year = $row["sal_year"];
						$this -> month = $row["sal_month"];
					}
				}	
					//查询出建名
					$sql = "select item_name from sal_item order by item_num";
					$a= exequery(TD::conn(),$sql);
					$salary =array();
					while($b = mysql_fetch_row($a)){
						$salary[]= $b[0];
					}
					/*根据flow_id查出来对应的工资*/	
					$query = "select * from SAL_DATA where flow_id='$flow_id' and USER_ID='$this->id'";
					$cursor1 = exequery(TD::conn(),$query);
					if(mysql_num_rows($cursor1)){
						if($row = mysql_fetch_assoc($cursor1)){
							foreach($row as $k=> $v){
								$salary[$k] = $v;
							}
						}
					}else{
						echo _("没有这个月工资");
						exit;
					}
					$salary ["year"] = $this->year;
					$salary["month"] = $this->month;
				
			return ($salary);
			
		}
		
	public function historyWage(){
			$query = "select flow_id,s1 from sal_data where user_id='$this->id'";
			$cursor= exequery(TD::conn(),$query);
			$datalist = array();
			while($row = mysql_fetch_assoc($cursor)){
				$flow_id = $row["flow_id"];
				$s1		 = $row["s1"];
				$query = "select sal_year,content,end_date from sal_flow where flow_id = $flow_id";
				$cursor1 = exequery(TD::conn(),$query);
				if($row1 = mysql_fetch_assoc($cursor1)){
					$time =	substr($row1["end_date"],0,7);
					$title = $row1["content"];
					$groupName = $row1["sal_year"];
				}else{
					echo _("没有历史工资");
					exit;
				}
				$datalist[$groupName][] = array(
						"flow_id" => iconv("utf-8", "gbk", $flow_id),
						"title" => iconv("utf-8", "gbk", $title),
						"number" => $s1
						);
			}
			foreach($datalist as $k => $v){
				$data[] = array(
					"groupName"  => iconv("utf-8", "gbk", $k),
					"salaryList" => $datalist[$k]
				);
				
			}
			//var_dump($datalist);
			//$datalist = json_encode($data);
			return $data;
	}
	
	public function getWageCount($startpage = 1,$limit = 10){
			/*查出历史工资总条数*/
			$query1 = "select count(flow_id) from sal_data where user_id='$this->id'";
			$cursor2 = exequery(TD::conn(),$query1);
			if($row = mysql_fetch_assoc($cursor2)){
				$length = $row["count(flow_id)"];
			}
			$start = ( $startpage - 1 ) * $limit;
			$data =array(
				"curpage" => $startpage,
				"start" => $start,
				"totalpage" => round($length/$limit),
				"datalist" => $this -> historyWage()
			);
			$salarydata =array(
					/*"status" => "1",
					"message" => _("123"),*/
					"data" => $data
			);
			$data = json_encode($salarydata);
			return ($data);		
	}
}
?>