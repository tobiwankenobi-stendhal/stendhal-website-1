<?php
/**
 * Parses the output of /script DeepInspect.class
 *
 * @author hendrik
 */
class InspectParser {
	private $data;
	private $inspectEndIndex;
	private $inspectStartIndex;
	private $currentIndex;
	private $current;
	private $res;

	public function __construct($data) {
		$this->data = explode("\n", $data);
	}

	/**
	 * parses the chatlog which may contain any number of /script DeepInspect.class results
	 */
	public function parse() {
		$this->res = array();
		$this->inspectStartIndex = -1;
		for ($i = 0; $i < count($this->data); $i++) {
			$line = $this->data[$i];

			if ($this->inspectStartIndex < 0) {
				if (preg_match('/\[..:..\] Inspecting .*/', $line)) {
					$this->inspectStartIndex = $i;
				}
			} else {
				if (preg_match('/\[..:..\] Script "DeepInspect.class" was successfully executed./', $line)) {
					$this->current = array();
					$this->inspectEndIndex = $i -1;

					$this->parseDeepInspect();

					$this->res[] = $this->current;
					$this->inspectStartIndex = -1;
				}
			}
		}

		return $this->res;
	}

	/**
	 * Parses one single deep inspect invokation
	 */
	private function parseDeepInspect() {
		$this->currentIndex = $this->inspectStartIndex + 1;
		$this->parseTopLevelAttributes();
		$this->parseSlots();
	}

	/**
	 * parses the top level attributes
	 */
	private function parseTopLevelAttributes() {
		for ($i = $this->currentIndex; $i < $this->inspectEndIndex; $i++) {
			$line = $this->data[$i];
			if ($line == '') {
				break;
			}
			$temp = explode(': ', $line);
			$this->current[$temp[0]] = $temp[1];
		}
	}


	/**
	 * parses the slots
	 */
	private function parseSlots() {
		$slotName = '';
		for ($i = $this->currentIndex; $i < $this->inspectEndIndex; $i++) {
			$line = preg_replace('/\[..:..\] /', '', $this->data[$i]);
			if (preg_match('/^Slot /', $line)) {
				$slotName = substr($line, 5, strlen($line) - 7);
			} else if (preg_match('/^   (Item, )?RPObject with Attributes of /', $line)) {
				$this->current[$slotName][] = $this->parseRPObject($line);
			}
		}
	}

	private function parseRPObject($line) {
		$res = array();
		$res['key'] = 'value';
		return $res;
	}
}