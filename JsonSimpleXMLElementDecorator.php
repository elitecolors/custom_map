<?php 

/**
 * Class JsonSimpleXMLElementDecorator
 *
 * Implement JsonSerializable for SimpleXMLElement as a Decorator
 */
class JsonSimpleXMLElementDecorator implements JsonSerializable
{
    /**
     * @var SimpleXMLElement
     */
    private $subject;
 
    public function __construct(SimpleXMLElement $element) {
        $this->subject = $element;
    }
 
    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed data which can be serialized by json_encode.
     */
    public function jsonSerialize() {
        $subject = $this->subject;
 
        $array = array();
 
        // json encode attributes if any.
        if ($attributes = $subject->attributes()) {
            $array['@attributes'] = array_map('strval', iterator_to_array($attributes));
        }
 
        // traverse into children if applicable
        $children = $subject;
 
        // json encode child elements if any. group on duplicate names as an array.
        foreach ($children as $name => $element) {
            $decorator = new self($element);
 
            if (isset($array[$name])) {
                if (!is_array($array[$name])) {
                    $array[$name] = [$array[$name]];
                }
                $array[$name][] = $decorator;
            } else {
                $array[$name] = $decorator;
            }
        }
 
        // json encode non-whitespace element simplexml text values.
        $text = trim($subject);
        if (strlen($text)) {
            if ($array) {
                $array['@text'] = $text;
            } else {
                $array = $text;
            }
        }
 
        // return empty elements as NULL (self-closing or empty tags)
        if (!$array) {
            $array = NULL;
        }
 
        return $array;
    }
}