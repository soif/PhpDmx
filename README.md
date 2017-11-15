# phpDmx

Controls DMX devices from php.

## Features
* multiples Transports as Plugins: ArtNet, OLA
* Create or Load Fixtures from templates
* Built-in REST API

## Classes
phpDmx implements the following classes :

#### plugin_ola
Use the **OLA** server package as a gateway. This allows to remember the current DMX state, while using any of the [OLA built-in protocols](https://www.openlighting.org/ola/). *On recent Debian/ubuntu OLA can be easely installed with `apt-get install ola`.*

#### plugin_artnet
Directly send **Art-Net** Commands.

#### API
Translates REST web queries into DMX commands. 

#### Fixture
Load fixtures from a template, or create them from scratch.
Automatically create presets for each fixture.


## Contributions
This project is open to contributions, so please fork it and feel free to submit PR to add fixtures template or to enhance it.


## Licence
This program is free software; you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
