# tinynote

A simple [markdown](https://www.markdownguide.org) note taking application with encryption support built in [PHP](https://www.php.net).

* [Documentation.](https://ricardogj08.github.io/tinynote/)

## Installation

Install dependencies:

```
cd tinynote
composer install
```

Copy the `env.example` file to `.env` and configure your application:

```
cp env.example .env
```

Run database migrations:

```
composer run migrations -- --setup
```

Generate JWT private key:

```
openssl genrsa \
  -out writable/jwt/rsa-private-key.pem \
  2048
```

Generate JWT public key from the private key:

```
openssl rsa -in writable/jwt/rsa-private-key.pem \
  -pubout \
  -outform PEM \
  -out writable/jwt/rsa-public-key.pem
```

## Commands

Format PHP code:

```
composer run prettier
```

Generate entity-relationship diagram:

```
composer run gendb-diagram
```

Generate API documentation:

```
composer run gendoc
```

## References

* [MigratoryData - Generate JWT tokens using PHP.](https://migratorydata.com/docs/extensions/auth-jwt/generate-jwt-with-php)
* [PHP - Encrypt long data with OpenSSL.](https://www.php.net/manual/en/function.openssl-private-encrypt.php#119810)

## License

```
tinynote -- A simple markdown note taking application with encryption support built in PHP.

Copyright (C) 2024  Ricardo García Jiménez <ricardogj08@riseup.net>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
```
