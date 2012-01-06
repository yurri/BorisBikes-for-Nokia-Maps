<?php
	require_once 'Zend/View/Helper/Abstract.php';
	class Zend_View_Helper_FormElement extends Zend_View_Helper_Abstract
	{
		public function formElement($form, $type, $element_name, $attributes = null) {
			// building additional attributes string if given
			$element_value = null;
			$additional_code = "";			
			if ($attributes) {
				if (is_array($attributes)) {
					foreach ($attributes as $name => $value) {
						if ($name == 'value') {
							$element_value = $value;
						}
						
						$additional_code .= $name . '="' . $value . '" ';
					}
				} else {
					$additional_code = $attributes;
				}
			}
			
			// building errors message 
			$error_message = "";
			$error_messages = $form->getMessages();
			if (isset($error_messages[$element_name])) {
				foreach ($error_messages[$element_name] as $message) {
					$error_message .= '<br/><label class="error">' . $message . '</label>' . "\n";
				}
			}			
			
			// calling the relevant function to print the element of the requested type
			switch ($type) {
			case 'select':
				$this->formElementSelect($form, $element_name, $additional_code, $error_message);
				break;
				
			case 'text':
				$this->formElementText($form, $element_name, $additional_code, $error_message);
				break;				
			
			case 'password':
				$this->formElementPassword($form, $element_name, $additional_code, $error_message);
				break;				
			
			case 'checkbox':
				$this->formElementCheckbox($form, $element_name, $element_value, $additional_code, $error_message);
				break;				
			
			case 'radio':
				$this->formElementRadio($form, $element_name, $element_value, $additional_code, $error_message);
				break;
				
			case 'hidden':
				$this->formElementHidden($form, $element_name, $element_value);
				break;
				
			case 'textarea':
				$this->formElementTextarea($form, $element_name, $element_value);
				break;

			default:
				// error here handling if needed
			}
		}
		
		// outputs hidden element
		protected function formElementHidden($form, $element_name, $element_value = null) {
			if ($element_value === null) {
				$element_value = $form->getElement($element_name)->getValue();
			}
			
			?>
			<input type="hidden" name="<?= $element_name; ?>" value="<?= htmlspecialchars($element_value); ?>">
			<?php
		}
		
		// outputs radio button element
		protected function formElementRadio($form, $element_name, $element_value, $additional_code = '', $error_message = '') {
			?>
			<input type="radio" name="<?= $element_name; ?>" <?= ($form->getElement($element_name)->getValue() == $element_value) ? 'checked' : ''; ?> <?= $additional_code ?>>
			<?= $error_message; ?>
			<?php
		}
		
		// outputs checkbox element
		protected function formElementCheckbox($form, $element_name, $element_value, $additional_code = '', $error_message = '') {
			?>
			<input type="checkbox" name="<?= $element_name; ?>" <?= ($form->getElement($element_name)->getValue() == $element_value) ? 'checked' : ''; ?> <?= $additional_code ?>>
			<?= $error_message; ?>
			<?php
		}		
		
		// outputs text element
		protected function formElementText($form, $element_name, $additional_code = '', $error_message = '') {
			?>
			<input type="text" name="<?= $element_name; ?>" value="<?= htmlspecialchars($form->getElement($element_name)->getValue()); ?>" <?= $additional_code ?>>			
			<?= $error_message; ?>
			<?php
		}

		// outputs text element
		protected function formElementTextarea($form, $element_name, $additional_code = '', $error_message = '') {
			?>
			<textarea name="<?= $element_name; ?>" <?= $additional_code ?>><?= htmlspecialchars($form->getElement($element_name)->getValue()); ?></textarea>
			<?= $error_message; ?>
			<?php
		}
		
		// outputs password element
		protected function formElementPassword($form, $element_name, $additional_code = '', $error_message = '') {
			?>
			<input type="password" name="<?= $element_name; ?>" value="<?= htmlspecialchars($form->getElement($element_name)->getValue()); ?>" <?= $additional_code ?>>
			<?= $error_message; ?>
			<?php
		}
		
		// outputs select element
		protected function formElementSelect($form, $element_name, $additional_code = '', $error_message = '') {
			// if the name of the element is "...[]"
			$element_form_name = $element_name;
			if (preg_match('/^(.+)\[\]$/', $element_name, $matches)) {
				$element_form_name = $matches[1];
			}
			
			?>
			<select name="<?= $element_name; ?>" <?= $additional_code; ?>>
			<?php
				$options = $form->getElement($element_form_name)->getMultiOptions();
				$field_value = $form->getElement($element_form_name)->getValue();

				foreach ($options as $option_value => $option_name) {
					if (is_array($field_value)) {
						?>
						<option value="<?= $option_value; ?>" <?= (in_array($option_value, $field_value)) ? 'selected' : '' ?>><?= $option_name; ?></option>
						<?php
					} else {
						?>
						<option value="<?= $option_value; ?>" <?= (strcmp($option_value, $field_value) == 0) ? 'selected' : '' ?>><?= $option_name; ?></option>
						<?php
					}
				}	
			?>
			</select>
			<?= $error_message; ?>
			<?php			
		}
	}
?>
