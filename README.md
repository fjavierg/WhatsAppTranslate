# WhatsApp Chat Translator

WhatsApp Messenger Chat Transator
========================
- Author: J. Gómez
- Date: 2015
- Version : 1.0

Based on whatsapp/chat-api (github.com/WHAnonymous/Chat-API) and Microsoft Translator API


----------

# Requires

- php: >=5.6
- ext-curl
- ext-gd
- ext-mcrypt
- ext-openssl
- ext-pdo: *
- ext-sockets
- ext-sqlite3
- PHP Protobuf(https://github.com/allegro/php-protobuf)
- Curve25519(https://github.com/mgp25/curve25519-php) to enable end to end encryption


----------


# License

GPLv3+: http://www.gnu.org/licenses/gpl-3.0.html.

#What is it?

Whatsapp Chat translator translates chat messages with your contacts into different languages.

Start a new chat by sending the vCard of your contact or sending "Chat Contact's number" to WA Chat Translator.

New messages will be translated and forwarded to your contact and your contact's replies will be fowrarded to you after translation
Several languages supported.

#Installation

1.Register a new number and get its credentials. See https://github.com/mgp25/Chat-API/wiki/WhatsAPI-Documentation#number-registration
2.Update WAcredentials.php file with your whatsApp credentials.
3.Sign up and register for Microsoft Translator API. See https://www.microsoft.com/en-us/translator/getstarted.aspx
4.Update MScredentials.php file
5.Run waTranslateApp.php
6.Send a vCard of your contact or "Chat number" to start a new chat.

 
