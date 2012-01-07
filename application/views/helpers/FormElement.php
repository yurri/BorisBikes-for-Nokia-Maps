<?php
class Zend_View_Helper_FormElement extends Zend_View_Helper_Abstract {
  // the only public method which is used to place the form element on a page
  public function formElement(
    $element,  // form object which is used to retrieve the element parameters
    $attributes = null  // attributes which are not defined with the object but are still needed
  ) {
    // building additional attributes string if given
    $value = null;
    $additionalHTML = '';
    
    if ($attributes) {
      // if an element value has been supplied explicitly, store it
      // we cannot process it with other elements as for different elements value might be defined differently
      // (e.g. for text area)
      if (isset($attributes['value'])) {
        $value = $attributes->value;
        unset($attributes['value']);
      }
      
      // all other parameters supplied will be just added as tag attributes
      foreach ($attributes as $attrName = > $attrValue) {
        $additionalHTML .= $attrName . '="' . htmlspecialchars($attrValue) . '" ';
      }
    }
    
    // get the element class lineage to determine its type
    $className = get_class($element);
    $parents = array_values(class_parents($className));
    $parents = array_merge(array($className), $parents);
    
    // now calling a "printing" function corresponding to the element type
    if (in_array('Zend_Form_Element_Hidden', $parents)) {
      $this->formElementHidden($element, $value, $additionalHTML, $errorsHTML);
      
    } else if (in_array('Zend_Form_Element_Text', $parents)) {
      $this->formElementText($element, $value, $additionalHTML, $errorsHTML);
      
    } else if (in_array('Zend_Form_Element_Password', $parents)) {
      $this->formElementPassword($element, $value, $additionalHTML, $errorsHTML);
      
    } else if (in_array('Zend_Form_Element_Select', $parents)) {
      $this->formElementSelect($element, $value, $additionalHTML, $errorsHTML);
      
    } else if (in_array('Zend_Form_Element_Checkbox', $parents)) {
      $this->formElementCheckbox($element, $value, $additionalHTML, $errorsHTML);
      
    } else if (in_array('Zend_Form_Element_Radio', $parents)) {
      $this->formElementRadio($element, $value, $additionalHTML, $errorsHTML);
      
    } else if (in_array('Zend_Form_Element_Textarea', $parents)) {
      $this->formElementTextarea($element, $value, $additionalHTML, $errorsHTML);
      
    } else {
      throw new Exception('Unknown form element supplied!');
    }    
    
    // building error message for the element, if any exist
    $errors = $form->getMessages();
    if (isset($errors[$element->getName()])) {
      // validation failed for the element provided
      echo '<label class="error">' . implode('</label><label class="error">', $errors[$element->getName()]) . '</label>';
    }
  }
  
  // outputs hidden element
  protected function formElementHidden($element, $value = null, $additionalHTML = '', $errorsHTML = '') {
    if ($value === null) {
      $value = $element->getValue();
    }
    
    ?>
    <input type="hidden" name="<?php echo $element->getName(); ?>" value="<?php echo htmlspecialchars($value); ?>" <?php echo $additionalHTML; ?>>    
    <?php
  }
  
  // outputs radio button element
  protected function formElementRadio($element, $value = null, $additionalHTML = '', $errorsHTML = '') {
    ?>
    <input type="radio" name="<?php echo $element->getName(); ?>" value="<?php echo htmlspecialchars($value); ?>" <?php echo ($element->getValue() == $value) ? 'checked' : ''; ?> <?php echo $additionalHTML; ?>>
    <?php
  }
  
  // outputs checkbox element
  protected function formElementCheckbox($element, $value = null, $additionalHTML = '', $errorsHTML = '') {
    ?>
    <input type="checkbox" name="<?php echo $element->getName(); ?>" value="<?php echo htmlspecialchars($value); ?>" <?php echo ($element->getValue() == $element_value) ? 'checked' : ''; ?> <?php echo $additionalHTML ?>>
    <?php
  }		
  
  // outputs text element
  protected function formElementText($element, $value = null, $additionalHTML = '', $errorsHTML = '') {
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
