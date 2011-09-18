<?php


class Netstats {
	
	
	public function traceroute($fast) {
		/*if ($fast) {
		 $cmd = 'traceroute -q 3 -w 1 -N 40 -n '.escapeshellcmd($ip);
		$res = shell_exec($cmd);
		} else {
		$cmd = 'traceroute -q 3 -w 1 -N 40 -A '.escapeshellcmd($ip);
		$res = shell_exec($cmd);
		}*/
		echo $this->parseTraceroute($res);
	}
	
	private function parseTraceroute($data) {
		$data = 'traceroute to 46.4.113.142 (46.4.113.142), 30 hops max, 60 byte packets
			1  slipstream (192.168.25.208) [AS8151]  0.267 ms  0.253 ms  0.245 ms
			2  dslc-082-083-192-001.pools.arcor-ip.net (82.83.192.1) [AS3209]  14.647 ms  14.650 ms  18.621 ms
			3  145.254.11.141 (145.254.11.141) [AS3209]  66.621 ms  66.615 ms  70.589 ms
			4  92.79.213.138 (92.79.213.138) [AS3209]  34.573 ms  38.582 ms  38.582 ms
			5  * r1ams1.core.init7.net (195.69.144.210) [AS1200]  38.569 ms  50.529 ms
			6  r1fra1.core.init7.net (77.109.128.153) [AS13030]  46.520 ms  46.522 ms  46.515 ms
			7  gw-hetzner.init7.net (77.109.135.18) [AS13030]  54.492 ms gw-hetzner.init7.net (82.197.166.86) [AS13030]  50.496 ms  54.463 ms
			8  hos-bb1.juniper2.rz14.hetzner.de (213.239.240.247) [AS24940]  58.463 ms *  62.436 ms
			9  hos-tr3.ex3k11.rz14.hetzner.de (213.239.224.204) [AS24940]  66.440 ms hos-tr1.ex3k11.rz14.hetzner.de (213.239.224.140) [AS24940]  70.403 ms hos-tr3.ex3k11.rz14.hetzner.de (213.239.224.204) [AS24940]  70.400 ms
			10  stendhalgame.org (46.4.113.142) [AS24940]  70.386 ms  70.383 ms  74.362 ms';
	
		/*
			2  dslc-082-083-192-001.pools.arcor-ip.net (82.83.192.1)     [AS3209]   14.647 ms  14.650 ms  18.621 ms
		3  145.254.11.141                          (145.254.11.141)  [AS3209]   66.621 ms  66.615 ms  70.589 ms
		4  92.79.213.138                           (92.79.213.138)   [AS3209]   34.573 ms  38.582 ms  38.582 ms
		5  r1ams1.core.init7.net                   (195.69.144.210)  [AS1200]   38.576 ms  38.569 ms  50.529 ms
		6  r1fra1.core.init7.net                   (77.109.128.153)  [AS13030]  46.520 ms  46.522 ms  46.515 ms
		7  gw-hetzner.init7.net                    (77.109.135.18)   [AS13030]  54.492 ms
		gw-hetzner.init7.net                    (82.197.166.86)   [AS13030]  50.496 ms             54.463 ms
		8  hos-bb1.juniper2.rz14.hetzner.de        (213.239.240.247) [AS24940]  58.463 ms  *          62.436 ms
		9  hos-tr3.ex3k11.rz14.hetzner.de          (213.239.224.204) [AS24940]  66.440 ms
		hos-tr1.ex3k11.rz14.hetzner.de          (213.239.224.140) [AS24940]  70.403 ms
		hos-tr3.ex3k11.rz14.hetzner.de          (213.239.224.204) [AS24940]  70.400 ms
		10  stendhalgame.org                       (46.4.113.142)    [AS24940]  70.386 ms  70.383 ms  74.362 ms
		*/
	
		$lines = explode("\n", $data);
		$res = array();
		foreach ($lines as $line) {
			if (strpos($line, 'traceroute to') !== false) {
				continue;
			}
			var_dump(explode(" ", $line));
			//$res = array_merge($res, $this->parseLine($line));
		}
	
		return '';
	
		return '<table class="prettytable">
			<tr><td>2</td><td>dslc-082-083-192-001.pools.arcor-ip.net <br>(82.83.192.1)</td><td>[AS3209]</td><td>14.647 ms</td><td>14.650 ms</td><td>18.621 ms</td></tr>
			<tr><td>3</td><td>145.254.11.141                          <br>(145.254.11.141)</td><td>[AS3209] </td><td> 66.621 ms</td><td>66.615 ms</td><td>70.589 ms</td></tr>
			<tr><td>4</td><td>92.79.213.138                           <br>(92.79.213.138)</td><td>[AS3209] </td><td> 34.573 ms</td><td>38.582 ms</td><td>38.582 ms</td></tr>
			<tr><td>5</td><td>r1ams1.core.init7.net                   <br>(195.69.144.210)</td><td>[AS1200]</td><td>  38.576 ms</td><td>38.569 ms</td><td>50.529 ms</td></tr>
			<tr><td>6</td><td>r1fra1.core.init7.net                   <br>(77.109.128.153)</td><td>[AS13030]</td><td> 46.520 ms</td><td>46.522 ms</td><td>46.515 ms</td></tr>
			<tr><td rowspan="2">7</td><td>gw-hetzner.init7.net        <br>(77.109.135.18)</td><td>[AS13030]</td><td>54.492 ms</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>gw-hetzner.init7.net                              <br>(82.197.166.86)</td><td>[AS13030]</td><td>&nbsp;</td><td>50.496 ms</td><td>54.463 ms</td></tr>
			<tr><td>8</td><td>hos-bb1.juniper2.rz14.hetzner.de        <br>(213.239.240.247)</td><td>[AS24940]</td><td> 58.463 ms</td><td>*</td><td>62.436 ms</td></tr>
			<tr><td rowspan="3">9</td><td>hos-tr3.ex3k11.rz14.hetzner.de</br>(213.239.224.204)</td><td>[AS24940]</td><td>66.440 ms</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>hos-tr1.ex3k11.rz14.hetzner.de          </br>(213.239.224.140)</td><td>[AS24940]</td><td>&nbsp;</td><td>70.403 ms</td><td>&nbsp;</td></tr>
			<tr><td>hos-tr3.ex3k11.rz14.hetzner.de          </br>(213.239.224.204)</td><td>[AS24940]</td><td>&nbsp;</td><td>&nbsp;</td><td>70.400 ms</td></tr>
			<tr><td>10</td><td>stendhalgame.org                       </br>(46.4.113.142)</td><td>[AS24940]</td><td>70.386 ms</td><td>70.383 ms</td><td>74.362 ms</td></tr>
			</table>';
	
	
		return '<table class="prettytable">
			<tr><td>2</td><td>dslc-082-083-192-001.pools.arcor-ip.net </td><td>(82.83.192.1)</td><td>[AS3209]</td><td>14.647 ms</td><td>14.650 ms</td><td>18.621 ms</td></tr>
			<tr><td>3</td><td>145.254.11.141                          </td><td>(145.254.11.141)</td><td>[AS3209] </td><td> 66.621 ms</td><td>66.615 ms</td><td>70.589 ms</td></tr>
			<tr><td>4</td><td>92.79.213.138                           </td><td>(92.79.213.138)</td><td>[AS3209] </td><td> 34.573 ms</td><td>38.582 ms</td><td>38.582 ms</td></tr>
			<tr><td>5</td><td>r1ams1.core.init7.net                   </td><td>(195.69.144.210)</td><td>[AS1200]</td><td>  38.576 ms</td><td>38.569 ms</td><td>50.529 ms</td></tr>
			<tr><td>6</td><td>r1fra1.core.init7.net                   </td><td>(77.109.128.153)</td><td>[AS13030]</td><td> 46.520 ms</td><td>46.522 ms</td><td>46.515 ms</td></tr>
			<tr><td rowspan="2">7</td><td>gw-hetzner.init7.net        </td><td>(77.109.135.18)</td><td>[AS13030]</td><td>54.492 ms</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>gw-hetzner.init7.net                              </td><td>(82.197.166.86)</td><td>[AS13030]</td><td>&nbsp;</td><td>50.496 ms</td><td>54.463 ms</td></tr>
			<tr><td>8</td><td>hos-bb1.juniper2.rz14.hetzner.de        </td><td>(213.239.240.247)</td><td>[AS24940]</td><td> 58.463 ms</td><td>*</td><td>62.436 ms</td></tr>
			<tr><td rowspan="3">9</td><td>hos-tr3.ex3k11.rz14.hetzner.de</td><td>(213.239.224.204)</td><td>[AS24940]</td><td>66.440 ms</td><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr><td>hos-tr1.ex3k11.rz14.hetzner.de          </td><td>(213.239.224.140)</td><td>[AS24940]</td><td>&nbsp;</td><td>70.403 ms</td><td>&nbsp;</td></tr>
			<tr><td>hos-tr3.ex3k11.rz14.hetzner.de          </td><td>(213.239.224.204)</td><td>[AS24940]</td><td>&nbsp;</td><td>&nbsp;</td><td>70.400 ms</td></tr>
			<tr><td>10</td><td>stendhalgame.org                       </td><td>(46.4.113.142)</td><td>[AS24940]</td><td>70.386 ms</td><td>70.383 ms</td><td>74.362 ms</td></tr>
			</table>';
	}

	public function parseLine($line) {
		$pingCount = 3;

		$pre = 0;   // the number of times that have been filed in the previous line
		$iPre = 0;  // number of times that have been printed in this line before the IP address
		$idx = 0;   // line index
		$res = array();
		$tokens = explode(" ", trim($line));
		var_dump($tokens);

		$i = 2;

		while (true) {
			$res[$idx] = new TracerouteLine();

			// parse line number, IP, name and AS
			$res[$idx]->no = $tokens[0];
			$res[$idx]->ip = $tokens[$i];
			$i++;
			if (strpos($tokens[$i], '(') !== false) {
				$res[$idx]->name = $res[$idx]->ip;
				$res[$idx]->ip = $tokens[$i];
				$i++;
			}
			if (strpos($tokens[$i], '[') !== false) {
				$res[$idx]->as = $tokens[$i];
				$i++;
			}
			$i++;

			// parse time, and detect new internal line because of an answer from a different host
			$time = $tokens[$i];
			$res[$idx]->pre = $pre;
			$res[$idx]->times[] = $time;
			$pre++;
			$i++;
			$i++;

			if ($pre+$iPre == $pingCount) {
				break;
			}
			if ($tokens[$i] != "") {
				$idx++;
				continue;
			}

			$i++;
			$time = $tokens[$i];
			$res[$idx]->times[] = $time;
			$pre++;
			$i++;
			$i++;
			if ($pre+$iPre == $pingCount) {
				break;
			}
			if ($tokens[$i] != "") {
				$idx++;
				continue;
			}

			$i++;
			$time = $tokens[$i];
			$res[$idx]->times[] = $time;
			$pre++;
			$i++;
			$i++;
			break;
		}
		var_dump($res);
		echo '____________________________';
		return $res;
	}
}


class TracerouteLine {
	public $no;
	public $name;
	public $ip;
	public $as;
	public $pre;
	public $times = array();

	public static function create($no, $name, $ip, $as,  $pre, $times) {
		$res = new TracerouteLine();
		$res->no = $no;
		$res->name = $name;
		$res->ip = $ip;
		$res->as = $as;
		$res->pre = $pre;
		$res->times = $times;
		return $res;
	}

	public function __toString() {
		return var_export($this, true);
	}
}