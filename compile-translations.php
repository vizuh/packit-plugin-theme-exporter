<?php
/**
 * Simple script to compile .po files to .mo files
 * Run this from command line: php compile-translations.php
 */

class MO_Compiler {
    
    public function compile($po_file, $mo_file) {
        if (!file_exists($po_file)) {
            echo "Error: PO file not found: $po_file\n";
            return false;
        }
        
        $entries = $this->parse_po_file($po_file);
        $this->write_mo_file($mo_file, $entries);
        
        echo "✓ Compiled: $po_file -> $mo_file\n";
        return true;
    }
    
    private function parse_po_file($file) {
        $content = file_get_contents($file);
        $entries = array();
        
        preg_match_all('/msgid\s+"(.+?)"\s+msgstr\s+"(.+?)"/s', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $msgid = stripcslashes($match[1]);
            $msgstr = stripcslashes($match[2]);
            
            if ($msgid !== '' && $msgstr !== '') {
                $entries[$msgid] = $msgstr;
            }
        }
        
        return $entries;
    }
    
    private function write_mo_file($file, $entries) {
        $tmp = array();
        foreach ($entries as $key => $value) {
            $tmp[] = array($key, $value);
        }
        
        // Sort by original string
        usort($tmp, function($a, $b) {
            return strcmp($a[0], $b[0]);
        });
        
        // Calculate offsets
        $ids = '';
        $strings = '';
        
        foreach ($tmp as $entry) {
            $ids .= $entry[0] . "\0";
            $strings .= $entry[1] . "\0";
        }
        
        $key_offsets = array();
        $val_offsets = array();
        $offset = 0;
        
        foreach ($tmp as $entry) {
            $key_offsets[] = array($offset, strlen($entry[0]));
            $offset += strlen($entry[0]) + 1;
        }
        
        $offset = 0;
        foreach ($tmp as $entry) {
            $val_offsets[] = array($offset, strlen($entry[1]));
            $offset += strlen($entry[1]) + 1;
        }
        
        // Write MO file
        $fp = fopen($file, 'wb');
        
        // Magic number
        fwrite($fp, pack('V', 0x950412de));
        
        // File format revision
        fwrite($fp, pack('V', 0));
        
        // Number of strings
        fwrite($fp, pack('V', count($tmp)));
        
        // Offset of table with original strings
        $originals_addr = 28;
        fwrite($fp, pack('V', $originals_addr));
        
        // Offset of table with translation strings
        $translations_addr = $originals_addr + count($tmp) * 8;
        fwrite($fp, pack('V', $translations_addr));
        
        // Size of hashing table (we don't use it)
        fwrite($fp, pack('V', 0));
        
        // Offset of hashing table
        fwrite($fp, pack('V', 0));
        
        // Write original strings offsets and lengths
        $str_addr = $translations_addr + count($tmp) * 8;
        foreach ($key_offsets as $offset_data) {
            fwrite($fp, pack('V', $offset_data[1]));
            fwrite($fp, pack('V', $str_addr + $offset_data[0]));
        }
        
        // Write translation strings offsets and lengths
        $str_addr += strlen($ids);
        foreach ($val_offsets as $offset_data) {
            fwrite($fp, pack('V', $offset_data[1]));
            fwrite($fp, pack('V', $str_addr + $offset_data[0]));
        }
        
        // Write original strings
        fwrite($fp, $ids);
        
        // Write translation strings
        fwrite($fp, $strings);
        
        fclose($fp);
    }
}

// Compile both translation files
$compiler = new MO_Compiler();

$base_dir = __DIR__ . '/languages/';
$files = array(
    'packit-plugin-theme-exporter-pt_BR',
    'packit-plugin-theme-exporter-en_US'
);

echo "Compiling translation files...\n\n";

foreach ($files as $file) {
    $po_file = $base_dir . $file . '.po';
    $mo_file = $base_dir . $file . '.mo';
    $compiler->compile($po_file, $mo_file);
}

echo "\n✓ All translation files compiled successfully!\n";
