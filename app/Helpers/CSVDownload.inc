<?php

/**
 * CSVDownload
 *
 * 2015.06.12 改行文字が変更できるよう修正
 *
 * @package KaleidoSKop
 * @since 4.0.0
 * @version 4.0.0
 * @author T.Kozaka
 */

namespace App\Helpers;

use SplFileObject;

if (!defined("__INPUT_ENCODING")) define("__INPUT_ENCODING", "UTF-8");
if (!defined("__OUTPUT_ENCODING")) define("__OUTPUT_ENCODING", "SJIS-Win");

class CSVDownload {

    private $output_fields;
    private $data_list;
    private $filename;
    private $input_encoding;
    private $output_encoding;
    private $end_of_line;
    private $force_enclosure;

    public function __construct() {
        $this->initialize();
    }

    public function initialize() {
        // @formatter:off
        $this->output_fields   = array();
        $this->data_list       = array();
        $this->filename        = "output";
        $this->input_encoding  = __INPUT_ENCODING;
        $this->output_encoding = __OUTPUT_ENCODING;
        $this->end_of_line     = "\r\n";
        $this->force_enclosure = false;
        // @formatter:on
    }

    public function set_output_fields($output_fields) {
        if (is_array($output_fields)) {
            $this->output_fields = $output_fields;
        }
    }

    public function set_data_list($data_list) {
        if (is_array($data_list)) {
            $this->data_list = $data_list;
        }
    }

    public function set_filename($filename) {
        $this->filename = $filename;
    }

    public function set_input_encoding($input_encoding) {
        $this->input_encoding = $input_encoding;
    }

    public function set_output_encoding($output_encoding) {
        $this->output_encoding = $output_encoding;
    }

    public function set_end_of_line($end_of_line) {
        $this->end_of_line = $end_of_line;
    }

    public function set_force_enclosure($force_enclosure) {
        $this->force_enclosure = $force_enclosure;
    }

    public function download($filename = NULL, $delimiter = ",", $enclosure = "\"") {
        if (isset($filename)) {
            $this->set_filename($filename);
        }
        $handle = fopen("php://output", "w");
        if ($handle) {
            $this->output_header();
            ob_start(array($this, "eol_convert"));
            $this->fputcsv($handle, $delimiter, $enclosure);
            ob_end_flush();
            fclose($handle);
        }
    }

    private function eol_convert($buffer) {
        return preg_replace("/\r\n|\r|\n/", $this->end_of_line, $buffer);
    }

    public function get_csv($delimiter = ",", $enclosure = "\"") {

        $handle = tmpfile();
        $this->fputcsv($handle, $delimiter, $enclosure);
        rewind($handle);

        $output = "";
        while (!feof($handle)) {
            $output .= fread($handle, 4096);
        }
        fclose($handle);
        return $this->eol_convert($output);

    }

    protected function fputcsv($handle, $delimiter, $enclosure) {

        $meta = stream_get_meta_data($handle);
        $file = new SplFileObject($meta["uri"], "wb");

        if (count($this->output_fields)) {
            array_walk($this->output_fields, array($this, "convert"), $enclosure);
            if ($this->force_enclosure) {
                $file->fwrite($enclosure . join($enclosure . $delimiter . $enclosure, $this->output_fields) . $enclosure . PHP_EOL);
            } else {
                $file->fputcsv($this->output_fields, $delimiter, $enclosure);
            }
        }
        if (count($this->data_list)) {
            foreach ($this->data_list as $data) {
                array_walk($data, array($this, "convert"), $enclosure);
                if ($this->force_enclosure) {
                    $file->fwrite($enclosure . join($enclosure . $delimiter . $enclosure, $data) . $enclosure . PHP_EOL);
                } else {
                    $file->fputcsv($data, $delimiter, $enclosure);
                }
            }
        }

    }

    public function get_tsv() {
        return $this->get_csv("\t");
    }

    private function output_header() {
        header("Pragma: public");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename={$this->filename}.csv");
    }

    function convert(&$string, $key, $enclosure) {
        $string = mb_convert_encoding($string, $this->output_encoding, $this->input_encoding);
        if ($this->force_enclosure) {
            $string = str_replace($enclosure, $enclosure . $enclosure, $string);
        }
    }

}
