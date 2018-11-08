# webandmobile1819-groepsopdracht-groep-04

## Getting started

```
--- installation
git clone https://github.com/webpxl/webandmobile1819-groepsopdracht-groep-04.git
cd .\webandmobile1819-groepsopdracht-groep-04\groepsopdracht-1
composer install

--- run api
php bin/console server:run
```

## Setup database

```sql
CREATE TABLE IF NOT EXISTS Message (
  Id int(11) AUTO_INCREMENT,
  Contents varchar(200) NOT NULL,
  Category varchar(20) NOT NULL,
  Upvotes int(3),
  Downvotes int(3),
  PRIMARY KEY(Id)
);

CREATE TABLE IF NOT EXISTS Comment (
    Id int(11) AUTO_INCREMENT,
    Contents varchar(200) NOT NULL,
    Token varchar(10) NOT NULL,
    Message_Id int(11),
    PRIMARY KEY(Id)
);

INSERT INTO Message VALUES
(1, 'PHP is the best programming language.', 'Programming', 0, 0);

INSERT INTO Message VALUES
(2, 'I like Javascript more.', 'Programming', 0, 0);

INSERT INTO Comment VALUES
(1, 'Indeed :D', '{11111}', 1);

INSERT INTO Comment VALUES
(2, 'That is debatable.', '{22222}', 2);

INSERT INTO Comment VALUES
(3, 'Typescript is the new Javascript.', '{33333}', 2);
```

## Running tests

```
--- testing
.\vendor\bin\phpunit
--- testing with code coverage
.\vendor\bin\phpunit  --coverage-html ..\coverage
```

## PHPMD & PHPCS

```
.\vendor\bin\phpmd SomeFile.php text cleancode,codesize,controversial,design,unusedcode
.\vendor\bin\phpcs --standard=PSR2 SomeFile.php
.\vendor\bin\phpcbf --standard=PSR2 SomeFile.php
```