<?php

class InspectPage extends Page {
	private static $KEYED_SLOTS = array("!quests", "!features", "!tutorial", "skills", "!kills", "!visited");
	private static $CHARACTER_SLOTS = array("head", "rhand", "armor", "lhand", "finger", "armor", "cloak", "legs", "feet");
	
	public function writeHtmlHeader() {
		echo '<title>Inspect'.STENDHAL_TITLE.'</title>';
		echo '<script type="text/javascript" src="'.STENDHAL_FOLDER.'/css/overlib.js"></script>';
	}

	function writeContent() {

		if(getAdminLevel() < 5000) {
			die("Ooops!");
		}

		$this->writeInputForm();

		$data = $_POST['data'];

		if (isset($data)) {
			$parsedData = $this->parse($data);
			foreach ($parsedData as $inspectData) {
				$this->renderInspectResult($inspectData);
			}
		}
	}

	private function writeInputForm() {
		startBox("Upload Form");
		?>
		<form action="#result" method="POST">
			<label for="data">Paste result of /script DeepInspect.class:</label>
			<textarea name="data" id="data" cols="75" rows="20"><?php echo htmlspecialchars($_POST['data'])?></textarea>
			<input type="submit">
		</form>
		<?php
		endBox();
	}

	private function parse($data) {
		$parser = new InspectParser($data);
		$res = $parser->parse();
		return $res;
	}

	/**
	 * renders the result of a deep inspect
	 *
	 * @param $inspectData
	 */
	private function renderInspectResult($inspectData) {
		echo '<h1><a name="result">Deep inspect of '.htmlspecialchars($inspectData['name']).'</a></h1>';
		$this->renderTopLevelAttributes($inspectData);
		$this->renderCharacterItemSlots($inspectData);
		$this->renderNonCharacterItemSlots($inspectData);
		$this->renderKeyedSlots($inspectData);
	}

	/**
	 * renders the top level attributes
	 *
	 * @param $inspectData data of an deep inspect
	 */
	private function renderTopLevelAttributes($inspectData) {
		echo '<table class="prettytable"><tr><th>key</th><th>value</th></tr>';
		foreach ($inspectData as $key => $value) {
			if (is_array($value)) {
				continue;
			}
			echo '<tr><td>'.htmlspecialchars($key).'</td><td>'.htmlspecialchars($value).'</td></tr>';
		}
		echo '</table>';
	}


	/**
	 * renders a slot with items
	 *
	 * @param $inspectData data of an deep inspect
	 */
	private function renderCharacterItemSlots($inspectData) {
		echo '<h2>character</h2>';

		echo 'head: ';
		$this->renderItemSlot($inspectData['head']);
		echo '<br>';
		echo 'rhand: ';
		$this->renderItemSlot($inspectData['rhand']);
		echo ' armor: ';
		$this->renderItemSlot($inspectData['armor']);
		echo ' lhand: ';
		$this->renderItemSlot($inspectData['lhand']);
		echo '<br>';
		echo 'finger: ';
		$this->renderItemSlot($inspectData['finger']);
		echo ' legs: ';
		$this->renderItemSlot($inspectData['legs']);
		echo ' cloak: ';
		$this->renderItemSlot($inspectData['cloak']);
		echo '<br>';
		echo 'feet: ';
		$this->renderItemSlot($inspectData['feet']);
	}


	/**
	 * renders a slot with items
	 *
	 * @param $inspectData data of an deep inspect
	 */
	private function renderNonCharacterItemSlots($inspectData) {
		foreach ($inspectData as $slotName => $slot) {
			if (in_array($slotName, InspectPage::$KEYED_SLOTS)) {
				continue;
			}
			if (in_array($slotName, InspectPage::$CHARACTER_SLOTS)) {
				continue;
			}
			if (!is_array($slot)) {
				continue;
			}

			echo '<h2>'.htmlspecialchars($slotName).'</h2>';
			$this->renderItemSlot($slot);
		}
	}


	private function renderItemSlot($slot) {
		if (!isset($slot)) {
			echo '<span style="display: inline-block; width: 32px; height: 32px; background-color: white; border: 2px solid blue"></span>';
			return;
		}

		$first = true;
		foreach ($slot as $item) {
			if ($first) {
				$first = false;
			} else {
				echo ', ';
			}

			echo $item['quantity'];

			$link = rewriteURL('/item/'.surlencode($item['class']).'/'.surlencode($item['name']).'.html');
			$html = $this->getItemTableHtml($item);
			echo ' <a href="' . $link . '"'
				. ' onmouseover="return overlib(\''.rawurlencode($html).'\', FGCOLOR, \'#000\', BGCOLOR, \'#FFF\','
				. 'DECODE, FULLHTML'
				. ');" onmouseout="return nd();" class="' . $cssclass . '">';

			$imglink = rewriteURL('/images/item/'.surlencode($item['class']).'/'.surlencode($item['subclass'].'.png'));
			echo '<img style="background-color: #FFF" src="'.htmlspecialchars($imglink).'" alt="'.htmlspecialchars($item['name']).'"></a>';
			echo '</a>';
		}
	}


	private function getItemTableHtml($item) {
		$res = '<table class="prettytable" style="text-align: left"><tr><th>key</th><th>value</th></tr>';
		foreach ($item as $key => $value) {
			$res .= '<tr><td>'.htmlspecialchars($key).'</td><td>'.htmlspecialchars($value).'</td></tr>';
		}
		$res .= '</table>';
		return $res;
	}

	/**
	 * renders a slot with an object that is a map
	 *
	 * @param $inspectData data of an deep inspect
	 */
	private function renderKeyedSlots($inspectData) {
		foreach (InspectPage::$KEYED_SLOTS as $keyedSlot) {
			if (!isset($inspectData[$keyedSlot])) {
				continue;
			}
			echo '<h2>'.htmlspecialchars($keyedSlot).'</h2>';
			echo '<table class="prettytable"><tr><th>key</th><th>value</th></tr>';
			foreach ($inspectData[$keyedSlot][0] as $key => $value) {
				echo '<tr><td>'.htmlspecialchars($key).'</td><td>'.htmlspecialchars($value).'</td></tr>';
			}
			echo '</table>';
		}
	}
}

$page = new InspectPage();
?>