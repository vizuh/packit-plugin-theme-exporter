#!/usr/bin/env python3
"""
Simple script to compile .po files to .mo files
Run this from command line: python compile-translations.py
"""

import struct
import array
import os
import re

def parse_po_file(po_file):
    """Parse a PO file and return dictionary of msgid -> msgstr"""
    with open(po_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    entries = {}
    
    # Find all msgid/msgstr pairs
    pattern = r'msgid\s+"([^"]*)"\s+msgstr\s+"([^"]*)"'
    matches = re.findall(pattern, content, re.MULTILINE)
    
    for msgid, msgstr in matches:
        # Unescape strings
        msgid = msgid.encode().decode('unicode_escape')
        msgstr = msgstr.encode().decode('unicode_escape')
        
        if msgid and msgstr:  # Skip empty strings
            entries[msgid] = msgstr
    
    return entries

def compile_mo_file(po_file, mo_file):
    """Compile a PO file to MO format"""
    entries = parse_po_file(po_file)
    
    if not entries:
        print(f"⚠ Warning: No entries found in {po_file}")
        return False
    
    # Sort by msgid
    sorted_entries = sorted(entries.items(), key=lambda x: x[0])
    
    # Generate the binary MO format
    keys = []
    values = []
    offsets = []
    
    # Build the key/value arrays
    for msgid, msgstr in sorted_entries:
        keys.append(msgid.encode('utf-8') + b'\x00')
        values.append(msgstr.encode('utf-8') + b'\x00')
    
    # Calculate offsets
    keystart = 7 * 4 + 16 * len(keys)
    valuestart = keystart + sum(len(k) for k in keys)
    koffsets = []
    voffsets = []
    
    offset = 0
    for key in keys:
        koffsets.append((len(key) - 1, keystart + offset))
        offset += len(key)
    
    offset = 0
    for value in values:
        voffsets.append((len(value) - 1, valuestart + offset))
        offset += len(value)
    
    # The header is 7 32-bit unsigned integers
    keystart = 7 * 4 + 16 * len(keys)
    valuestart = keystart + sum(len(k) for k in keys)
    koffsets = []
    voffsets = []
    
    # Calculate offsets for keys
    offset = 0
    for key in keys:
        koffsets.append((len(key) - 1, keystart + offset))
        offset += len(key)
    
    # Calculate offsets for values
    offset = 0
    for value in values:
        voffsets.append((len(value) - 1, valuestart + offset))
        offset += len(value)
    
    # Write the MO file
    with open(mo_file, 'wb') as f:
        # Magic number (little endian)
        f.write(struct.pack('I', 0x950412de))
        
        # Version
        f.write(struct.pack('I', 0))
        
        # Number of entries
        f.write(struct.pack('I', len(keys)))
        
        # Offset of table with original strings  
        f.write(struct.pack('I', 28))
        
        # Offset of table with translation strings
        f.write(struct.pack('I', 28 + len(keys) * 8))
        
        # Size of hashing table (we don't use it)
        f.write(struct.pack('I', 0))
        
        # Offset of hashing table
        f.write(struct.pack('I', 0))
        
        # Write table of original strings
        for length, offset in koffsets:
            f.write(struct.pack('I', length))
            f.write(struct.pack('I', offset))
        
        # Write table of translation strings
        for length, offset in voffsets:
            f.write(struct.pack('I', length))
            f.write(struct.pack('I', offset))
        
        # Write original strings
        for key in keys:
            f.write(key)
        
        # Write translation strings
        for value in values:
            f.write(value)
    
    return True

# Main execution
if __name__ == '__main__':
    base_dir = 'languages'
    files = [
        'packit-plugin-theme-exporter-pt_BR',
        'packit-plugin-theme-exporter-en_US'
    ]
    
    print("Compiling translation files...\n")
    
    success_count = 0
    for filename in files:
        po_file = os.path.join(base_dir, filename + '.po')
        mo_file = os.path.join(base_dir, filename + '.mo')
        
        if not os.path.exists(po_file):
            print(f"✗ Error: {po_file} not found")
            continue
        
        if compile_mo_file(po_file, mo_file):
            print(f"✓ Compiled: {po_file} -> {mo_file}")
            success_count += 1
        else:
            print(f"✗ Failed: {po_file}")
    
    print(f"\n✓ Successfully compiled {success_count}/{len(files)} translation files!")
