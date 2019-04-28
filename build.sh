#!/bin/bash
echo "##############################"
echo "######### BULIT 3DPA #########"
echo "##############################"

VERSION=$(git describe --always --abbrev=8 --broken)
echo "Version is " ${VERSION}
cat > version.php <<EOF
<?php \$version="${VERSION}"; ?>
EOF