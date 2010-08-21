<?php
/**
 * Parses the output of /script DeepInspect.class
 *
 * @author hendrik
 */
class InspectParser {
	private $data;

	public function __construct($data) {
		$this->data = explode("\n", $data);
	}

	/**
	 * parses the chatlog which may contain any number of /script DeepInspect.class results
	 */
	public function parse() {
		$res = array();
		$inspectStartIndex = -1;
		for ($i = 0; $i < count($this->data); $i++) {
			$line = $this->data[$i];

			if ($inspectStartIndex < 0) {
				if (preg_match('/\[..:..\] Inspecting .*/', $line)) {
					$inspectStartIndex = $i;
				}
			} else {
				if (preg_match('/\[..:..\] Script "DeepInspect.class" was successfully executed./', $line)) {
					$this->parseDeepInspect($inspectStartIndex + 1, $i -1);
					$inspectStartIndex = -1;
				}
			}
		}
		
		return res;
	}

	private function parseDeepInspect($inspectStartIndex, $inspectEndIndex) {
		echo $inspectStartIndex .'-'. $inspectEndIndex;
	}
}