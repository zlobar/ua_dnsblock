
blocklist/
------
Доменні імена, що підлягають блокуванню відповідно до [Розпоряджень НЦУ](https://cip.gov.ua/ua/filter?tagId=60751), рішень РНБО і Нацради, Указів Президента України

whitelist/
------
Доменні імена, що були раніше заблоковані і підлягають розблокуванню

Перелік заблокованих доменних імен
------

https://raw.githubusercontent.com/zlobar/ua_dnsblock/main/domains.txt

Налаштування DNS сервера BIND9
------

1) скачати https://raw.githubusercontent.com/zlobar/ua_dnsblock/main/named.conf.blocked
2) включити його в конфіг named.conf **include "/etc/bind/named.conf.blocked";**

Налаштування DNS сервера Unbound
------

1) скачати https://raw.githubusercontent.com/zlobar/ua_dnsblock/main/unbound.conf.blocked
2) включити його в конфіг unbound.conf **include-toplevel: "/etc/unbound/unbound.conf.d/*"** (або інший шлях де він буде)


_Оновлено станом на 26.04.2024_
