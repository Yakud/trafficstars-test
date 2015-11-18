# trafficstars-test
How to install:
```shell
git clone https://github.com/Yakud/trafficstars-test.git
cd trafficstars-test
./install
```

How to use:
```shell
php parse.php /path/to/text/file.txt
```

Example: 
```shell
$ echo "Мама мыла раму, маме мыла мало." > text.txt
$ php parse.php ./text.txt 
мама:1
раму:1
маме:1
мало:1
мыла:2
Parse time: 0.014544010162354
```
