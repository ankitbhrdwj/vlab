#!/bin/sh                               
WATCHED_DIR="/var/www/vlab/upload"
UPLOAD_DIR="/vlab/template"    
echo "Watching directory: $WATCHED_DIR for new files"
inotifywait -m -q -r -e move -e attrib -e modify -e create -e close_write "$WATCHED_DIR" --format "%w%f" |
while read file
do
	loc="$file"
	rem="$UPLOAD_DIR${file#$WATCHED_DIR}"
	rsync --ignore-existing --inplace -q "$loc" "$rem" 
done
