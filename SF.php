<?php

	/**
	 * KMP算法辅助函数
 	 */
	function getNext($str)
	{
		$next =array();
		$m = 0;
		$next[0] = 0;

		for ($i=1; $i < strlen($str); $i++) { 
			if($str[$i] == $str[$m])
			{
				$next[$i] = $next[$i-1] +1;
				$m++;
			}else{
				$m = 0;
				$next[$i] = $next[$m];
			}
		}
		return $next;
	}
	//KMP查找算法
	function KMP($str, $par)
	{
		$next = $this->getNext($par);
		$str_len = strlen($str);
		$par_len = strlen($par);
		for ($i=0,$j=0 ; $i < $str_len AND $j < $par_len; ) { 
			if($str[$i] == $par[$j])
			{
				$i++;
				$j++;
			}else{
				if($j == 0)
				{
					$i ++;
				}
				$j = $next[($j-1) >= 0 ? ($j-1) : 0];
			}
		}
		if($j == strlen($par)) return $i-$j+1;
		return false;
	}