git subsplit init git@github.com:awei01/pressor.git
git subsplit publish --heads="master" mu-plugin:git@github.com:pressor/mu-plugin.git
git subsplit publish --heads="master" framework:git@github.com:pressor/framework.git
rm -r -f .subsplit/
