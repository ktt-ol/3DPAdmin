#!/bin/bash
echo "##############################"
echo "######### BUILT 3DPA #########"
echo "##############################"

VERSION=$(git describe --always --abbrev=8)
echo "Version is " ${VERSION}
cat > version.php <<EOF
<?php \$version="${VERSION}"; ?>
EOF
