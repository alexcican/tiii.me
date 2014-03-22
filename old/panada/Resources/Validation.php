<?php
/**
 * Panada validation API.
 *
 * @package	Resources
 * @link	http://panadaframework.com/
 * @license	http://www.opensource.org/licenses/bsd-license.php
 * @author	Iskandar Soesman <k4ndar@yahoo.com>
 * @since	Version 0.1
 */
namespace Resources;

class Validation
{
    private
	$errorMessages = array(),
	$rules = array(),
	$validValues = array(),
	$unsetValue = false;
    
    protected
	$ruleErrorMessages = array();
	
    public function __construct()
    {
	$this->setRuleErrorMessages();
    }
    
    /**
     * Trim then make it lower
     *
     * @param string $string
     * @return string
     */
    public function trimLower($string)
    {    
        return trim(strtolower($string));
    }
    
    /**
     * Email format validation
     *
     * @param string $string
     * @return mix false if woring or string if true
     */
    public function isEmail($string)
    {
	$string = $this->trimLower($string);
        
	$chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
	
        if (strpos($string, '@') === false && strpos($string, '.') === false)
            return false;
	
        if ( ! preg_match($chars, $string))
            return false;
        
        return $string;
    }
    
    /**
     * Url address format validation
     *
     * @param string $string
     * @return mix false if woring or string if true
     */
    public function isUrl($string)
    {    
        $string = $this->trimLower($string);
	
        return filter_var($string, FILTER_VALIDATE_URL);
        /*
        $chars = '|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i';
        
        if( ! preg_match( $chars, $string ))
            return false;
        else
            return $string;
        */
    }
    
    /**
     * Return only numeric character
     *
     * @param string $string
     * @return string
     */
    public function stripNumeric($string)
    {    
        return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
        //return preg_replace('/[^0-9]/', '', $string);
    }
    
    /**
     * Positive numeric validation.
     *
     * @param string or int $string
     * @return bool
     */
    public function isPositiveNumeric($string)
    {
        return (bool) preg_match( '/^[0-9]*\.?[0-9]+$/', $string);
    }
    
    /**
     * Use this for filterize user first name and last name.
     *
     * @param string $string
     * @return string
     */
    public function displayName($string)
    {    
        //Only permit a-z, 0-9 and .,'" space this is enough for a name right?
        
        //'/[^a-zA-Z0-9s -_.,]/'
        $string = $this->trimLower($string);
        $string = strip_tags($string);
        $string = stripslashes($string);
        $string = preg_replace( '/[^a-zA-Z0-9s .,"\']/', '', $string);
        
        return ucwords($string);
    }
    
    /**
     * specify the validation rule
     *
     * @return array
     */
    public function setRules()
    {
	return array();
    }
    
    /**
     * performs the validation
     *
     * @param array $fields
     * @return bool
     */
    public function validate( $fields = array() )
    {
	$return = true;
	$rules = $this->setRules();
	
	// if user didn't specified any value, then lest catch it from PHP $_POST variable
	if( empty($fields) ) {
	    
	    $fields = $_POST;
	    
	    if( isset($_FILES) || ! empty($_FILES) )
		$fields = array_merge($fields, $_FILES);
	}
	
	
	foreach($fields as $field => $value) {
	    
	    if( is_array($value) && ! isset($value['tmp_name']) ) {
		
		$return = $this->validateArray($field, $value, $rules);
		continue;
	    }
	    
	    // apply filter if any
	    $value = $this->applyFilter($field, $value, $rules);
	    
	    if( isset($rules[$field]) ) {
		
		foreach($rules[$field]['rules'] as $key => $rule) {
		    
		    // use filed name if label not provided
		    $label = (isset($rules[$field]['label'])) ? $rules[$field]['label'] : $field;
		    
		    $response = $this->ruleToMethod($key, $rule, $field, $value, $label, $rules, $fields);
		    
		    if( ! $response ) {
			
			$return = false;
			
			unset($this->validValues[$field]);
			break;
		    }
		    else {
			
			$this->validValues[$field] = $value;
		    }
		}
	    }
	    
	    // unset any specified field value
	    if( $this->unsetValue )
		unset($this->validValues[$this->unsetValue]);
	    
	}
	
	return $return;
    }
    
    /**
     * Validating array value from a field
     *
     * @param string $field The html field name
     * @param array $values The array value form the filed
     * @return bool
     */
    private function validateArray($field, $values)
    {
	$return = true;
	$rules = $this->setRules();
	
	foreach($values as $k => $value) {
	    
	    // apply filter if any
	    $value = $this->applyFilter($field, $value, $rules);
	    
	    if( isset($rules[$field]) ) {
		
		foreach($rules[$field]['rules'] as $key => $rule) {
		    
		    // use filed name if label not provided
		    $label = (isset($rules[$field]['label'])) ? $rules[$field]['label'] : $field;
		    $label = $label.' #'. $k;
		    
		    $response = $this->ruleToMethod($key, $rule, $field, $value, $label);
		    
		    $htmlField = $field.'['.$k.']';
		    
		    if( ! $response ) {
			
			$return = false;
			
			unset($this->validValues[$htmlField], $this->validValues[$field][$k]);
			return;
		    }
		    else {
			
			$this->validValues[$htmlField] = $this->validValues[$field][$k] = $value;
		    }
		}
	    }
	}
	
	return $return;
    }
    
    /**
     * Apply function to filter the string
     *
     * @param string $field The html field name
     * @param string $value The field value
     * @param array $rules The rules defined by user
     * @return string
     */
    private function applyFilter($field, $value, $rules)
    {
	// apply filter if any
	if( isset($rules[$field]['filter']) ) {
	    
	    foreach($rules[$field]['filter'] as $filter)
		$value = call_user_func_array($filter, array($value));
	}
	
	return $value;
    }
    
    /**
     * Map from rule to the actual method to handle the validation
     *
     * @param string or int $key Array key from rule list
     * @param string $rule The rule name
     * @param string $field Html field name
     * @param string $label Label to identifed the field
     * @param array $rules An array of user rules
     * @param array $fields original submitetd by user
     * @return bool
     */
    private function ruleToMethod($key, $rule, $field, $value, $label, $rules = array(), $fields = array() )
    {
	if( is_numeric($key) ) {
	    
	    $method = 'rule'.ucwords($rule);
	    
	    $response = $this->$method($field, $value, $label);
	}
	else {
	    
	    if( $key == 'callback' ) {
		
		$response = $this->$rule($field, $value, $label);
	    }
	    elseif( $key == 'compare' ) {
		
		// comparator field
		//$rule;
		
		$comparatorLabel = $rules[$rule]['label'];
		$comparatorValue = $fields[$rule];
		
		$method = 'rule'.ucwords($key);
		
		$response = $this->$method($field, $value, $label, $comparatorValue, $comparatorLabel, $rule);
	    }
	    else {
		
		$method = 'rule'.ucwords($key);
		
		$response = $this->$method($field, $value, $label, $rule);
	    }
	}
	
	return $response;
    }
    
    /**
     * Populate the error message(s)
     *
     * @param string $field
     * @param string $tagOpen
     * @param string $tagClose
     * @return null or string
     */
    public function errorMessages($field = false, $tagOpen = null, $tagClose = null)
    {
	if( empty($this->errorMessages) )
	    return null;
	
	if( $field ) {
	    
	    if( ! isset($this->errorMessages[$field]) )
		return null;
	    
	    if( ! is_null($tagOpen) || ! is_null($tagClose) )
		return $tagOpen.$this->errorMessages[$field].$tagClose;
	    
	    return $this->errorMessages[$field];
	}
	else {
	    
	    if( ! is_null($tagOpen) || ! is_null($tagClose) ) {
		
		$return = null;
	    
		foreach($this->errorMessages as $message)
		    $return .= $tagOpen.$message.$tagClose;
		
		return $return;
	    }
	    
	    return $this->errorMessages;
	}
    }
    
    /**
     * Setter for error message
     *
     * @param string $field
     * @param string $message
     * @return void
     */
    public function setErrorMessage($field, $message)
    {
	$this->errorMessages[$field] = $message;
    }
    
    /**
     * Filterized value
     *
     * @param string | false $field
     * @return string
     */
    public function value($field = false)
    {
	if( ! $field ){
	    
	    $validValues = $this->validValues;
	    foreach($validValues as $key => $value) {
		
		if( is_array($value) ) {
		    
		    foreach($value as $k => $v) {
			$f = $key.'['.$k.']';
			unset($validValues[$f]);
		    }
		    
		}
	    }
	    return $validValues;
	}
	
	if( ! isset($this->validValues[$field]) )
	    return null;
	
	return $this->validValues[$field];
    }
    
    /**
     * Translate from error message to actial error message
     *
     * @param string $rule
     * @param string $label
     * @return string
     */
    private function ruleErrorMessage($rule, $label)
    {
	return str_replace('%label%', $label, $this->ruleErrorMessages[$rule]);
    }
    
    /**
     * Methode to chek the value was empty or not
     *
     * @param string $field
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleRequired($field, $value, $label)
    {
	if( empty($value) ) {
	    
	    $this->setErrorMessage( $field, $this->ruleErrorMessage('required', $label) );
	    
	    return false;
	}
	
	return true;
    }
    
    /**
     * Method to validate the email format
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleEmail($field, $value, $label)
    {
	if( ! $this->isEmail($value) ) {
	    
	    $this->setErrorMessage($field, $this->ruleErrorMessage('email', $label));
	    
	    return false;
	}
	
	return true;
    }
    
    /**
     * Method to validate minimum string length
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleMin($field, $value, $label, $minVal)
    {
	if( strlen($value) < $minVal ) {
	    
	    $this->setErrorMessage($field, str_replace(array('%label%', '%size%'), array($label, $minVal), $this->ruleErrorMessages['min']));
	    return false;
	}
	
	return true;
    }
    
    /**
     * Method to validate maximum string length
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleMax($field, $value, $label, $maxVal)
    {
	if( strlen($value) > $maxVal ) {
	    
	    $this->setErrorMessage($field, str_replace(array('%label%', '%size%'), array($label, $maxVal), $this->ruleErrorMessages['max']));
	    return false;
	}
	
	return true;
    }
    
    /**
     * Define the default error messages
     *
     * @param array $messages User defined message by rule
     * @return void
     */
    protected function setRuleErrorMessages($messages = array() )
    {
	$this->ruleErrorMessages = array(
	    'required' => '%label% can not be empty',
	    'email' => '%label% not valid email format',
	    'min' => '%label% need more then %size% character',
	    'max' => '%label% need less then %size% character',
	    'compare' => '%label% value did not match compare to %comparatorLabel%',
	    'file' => '%label% can not be empty',
	    'regex' => '%label% input format not valid',
	    'in' => '%label% did not match to any specified values',
	    'url' => '%label% not valid URL format',
	    'alpha' => '%label% can only alphabet character',
	    'numeric' => '%label% can only numerical character',
	    'alphanumeric' => '%label% can only alphabet or numerical character',
	    'match' => '%label% length must exactly %size% character',
	);
	
	$this->ruleErrorMessages = array_merge($this->ruleErrorMessages, $messages);
    }
    
    /**
     * Ensuring the attribute is equal to another attribute
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @param string $comparatorValue Value from comparator field
     * @param string $comparatorLabel label from comparator field
     * @param string $comparatorField field name of comparator field
     * @return bool
     */
    private function ruleCompare($field, $value, $label, $comparatorValue, $comparatorLabel, $comparatorField)
    {
	if( empty($comparatorValue) )
	    return false;
	
	if( $value == $comparatorValue)
	    return true;
	
	$this->setErrorMessage($field, str_replace( array('%label%', '%comparatorLabel%'), array($label, $comparatorLabel), $this->ruleErrorMessages['compare']));
	
	// unset comparator filed value
	$this->unsetValue = $comparatorField;
	
	return false;
    }
    
    /**
     * Ensuring the attribute contains the name of an uploaded file.
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleFile($field, $value, $label)
    {
	// its mean uploaded file more then 1 field
	if( is_array($value['name']) ) {
	    
	    foreach($value['name'] as $name) {
		if( empty($name) ) {
		    goto ret;
		}
	    }
	    
	    return true;
	}
	else {
	    
	    if( ! empty($value['name']) )
		return true;
	}
	
	ret:
	$this->setErrorMessage($field, str_replace('%label%', $label, $this->ruleErrorMessages['file']));
	
	return false;
    }
    
    /**
     * Ensuring the data is among a pre-specified list of values
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleIn($field, $value, $label)
    {
	$rules = $this->setRules();
	$list = $rules[$field]['rules']['in'];
	
	if( array_search($value, $list) !== false)
	    return true;
	
	$this->setErrorMessage($field, str_replace('%label%', $label, $this->ruleErrorMessages['in']));
	
	return false;
    }
    
    /**
     * Ensuring the data matches a regular expression
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleRegex($field, $value, $label, $pattern = false)
    {
	if( ! $pattern ) {
	    
	    $rules = $this->setRules();
	    $pattern = $rules[$field]['rules']['regex'];
	}
	
	if( preg_match($pattern, $value) )
	    return true;
	
	$this->setErrorMessage($field, str_replace('%label%', $label, $this->ruleErrorMessages['regex']));
	
	return false;
    }
    
    /**
     * Ensuring the data is a valid URL
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleUrl($field, $value, $label)
    {
	if( $this->isUrl($value) )
	    return true;
	
	$this->setErrorMessage($field, str_replace('%label%', $label, $this->ruleErrorMessages['url']));
	
	return false;
    }
    
    /**
     * Ansuring the data is a valid alphabet format
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleAlpha($field, $value, $label)
    {
	if( $this->ruleRegex($field, $value, $label, '/^([a-z])+$/i') )
	    return true;
	
	$this->setErrorMessage($field, str_replace('%label%', $label, $this->ruleErrorMessages['alpha']));
	
	return false;
    }
    
    /**
     * ensuring the data is a valid number
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleNumeric($field, $value, $label)
    {
	if( $this->ruleRegex($field, $value, $label, '/^[\-+]?[0-9]*\.?[0-9]+$/') )
	    return true;
	
	$this->setErrorMessage($field, str_replace('%label%', $label, $this->ruleErrorMessages['numeric']));
	
	return false;
    }
    
    /**
     * ensuring the data is a valid alphanumeric
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleAlphaNumeric($field, $value, $label)
    {
	if( $this->ruleRegex($field, $value, $label, '/^([a-z0-9])+$/i') )
	    return true;
	
	$this->setErrorMessage($field, str_replace('%label%', $label, $this->ruleErrorMessages['alphanumeric']));
	return false;
    }
    
    /**
     * Ensuring the value length exact certain size
     *
     * @param string $feld
     * @param string $value
     * @param string $label
     * @return bool
     */
    private function ruleMatch($field, $value, $label)
    {
	$rules = $this->setRules();
	$size = $rules[$field]['rules']['match'];
	
	if( strlen($value) == $size )
	    return true;
	
	$this->setErrorMessage($field, str_replace(array('%label%', '%size%'), array($label, $size), $this->ruleErrorMessages['match']));
	
	return false;
    }
}