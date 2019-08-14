#!/bin/bash
echo "##############################"
echo "####### Updating  3DPA #######"
echo "##############################"
echo "Fetching softwareupdate"
git fetch --all -v 
echo "done"
echo "##############################"
echo "updating"
echo "setting permissions"
pwd=$(pwd)
find ${pwd}/ -type d -print0 | xargs -0 chmod 0755
find ${pwd}/ -type f -print0 | xargs -0 chmod 0644
chmod 755 ${pwd}/php -R
chmod 755 update.sh
echo "##############################"
echo "writing versionfile"
echo "##############################"
VERSION=$(git describe --always --abbrev=7)
VERSION_GIT=$(git log -1 --pretty=format:"%h")
echo "##############################"
NOW=$( date )
echo "Serverversion is " ${VERSION}
echo "Githubversion is " ${VERSION_GIT}
echo "Buildtime is " ${NOW}
cat > version.php <<EOF
<?php \$version="${VERSION}"; \$buildtime="${NOW}"; \$gitversion="${VERSION_GIT}"?>
EOF
echo "done"
echo "##############################"
