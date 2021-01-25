<?php

function get_path_and_query(string $param, $val) {
    $query = "?";
    foreach (array_keys($_GET) as $key) {
        if ($key == $param) continue;
        if ($query != "?") $query .= "&";
        $query .= $key . "=" . $_GET[$key];
    }
    if ($query != '?') $query .= "&";
    $query .= $param . "=" . $val;
    preg_match("/\/.*(?=\?)/", $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE); // Match path without query
    if (key_exists(0, $matches)) if ($matches[0] != null) $path = $matches[0][0];
    if (!isset($path)) $path = $_SERVER['REQUEST_URI'];
    return $path . $query;
}

function to_pg_array($set) {
    settype($set, 'array'); // can be called with a scalar or array
    $result = array();
    foreach ($set as $t) {
        if (is_array($t)) {
            $result[] = to_pg_array($t);
        } else {
            $t = str_replace('"', '\\"', $t); // escape double quote
            if (! is_numeric($t)) // quote only non-numeric values
                $t = '"' . $t . '"';
            $result[] = $t;
        }
    }
    return '{' . implode(",", $result) . '}'; // format
}

function pg_array_parse($s, $start = 0, &$end = null)
{
    if (empty($s) || $s[0] != '{') return null;
    $return = array();
    $string = false;
    $quote='';
    $len = strlen($s);
    $v = '';
    for ($i = $start + 1; $i < $len; $i++) {
        $ch = $s[$i];

        if (!$string && $ch == '}') {
            if ($v !== '' || !empty($return)) {
                $return[] = $v;
            }
            $end = $i;
            break;
        } elseif (!$string && $ch == '{') {
            $v = pg_array_parse($s, $i, $i);
        } elseif (!$string && $ch == ','){
            $return[] = $v;
            $v = '';
        } elseif (!$string && ($ch == '"' || $ch == "'")) {
            $string = true;
            $quote = $ch;
        } elseif ($string && $ch == $quote && $s[$i - 1] == "\\") {
            $v = substr($v, 0, -1) . $ch;
        } elseif ($string && $ch == $quote && $s[$i - 1] != "\\") {
            $string = false;
        } else {
            $v .= $ch;
        }
    }

    return $return;
}