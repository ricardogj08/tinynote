# tinynote

A simple markdown note taking application with encryption support built in PHP.

## Installation

Install dependencies:

    composer install

Copy the `env.example` file to `.env` and configure your application:

    cp env.example .env

Run database migrations:

    composer run migrations -- --setup

## Commands

Format PHP code:

    composer run prettier

Generate entity-relationship diagram:

    composer run gendb-diagram

## License

    tinynote - A simple markdown note taking application with encryption support built in PHP.

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
