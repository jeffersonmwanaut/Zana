<?php namespace Zana;

class Exception extends \Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        $message = "<div style='padding: 10px 50px; font-family: sans-serif'>";
        $message .= "<h1 style='color: #AC3F3D; font-weight: lighter'>Oops! An Error Occurred</h1>";
        $message .= "<h3 style='font-weight: lighter'>" . get_called_class() . " " . $this->code . " " . $this->message ."</h3>";
        $message .= "<table style='width: 100%; border: solid #dbdbd9 1px; border-collapse: collapse;'>";
        $message .= "<caption style='background-color: #dbdbd9; padding: 8px; text-align: left; font-weight: bolder'>Trace</caption>";
        $message .= "<tbody style='font-size: smaller'>";
        $i = 0;
        foreach($this->getTrace() as $trace):
            $i++;
            $message .= "<tr><td style='padding: 8px; border-bottom: solid #dbdbd9 1px'>" . $i . "</td><td style='border-bottom: solid #dbdbd9 1px'>" . $trace['file'] . "</td><td style='border-bottom: solid #dbdbd9 1px'>(line " . $trace['line'] . ")</td></tr>";
        endforeach;
        $message .= "</tbody>";
        $message .= "</table>";
        $message .= "</div>";
        return $message;
    }
}