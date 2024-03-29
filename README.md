# Cyber-feed

Par Timothé ALBOUY, Arnaud PERNET et Gwenn QUELO, dans le cadre du projet de cybersécurité de deuxième année d'ingénieur à l'ENSIBS.

Cet aggrégateur RSS a été réalisée dans le cadre du projet de cybersécurité de 2ème année d'ingénieur à l'ENSIBS.

## Utile

**Flux XML :**

https://blog.feedspot.com/cyber_security_rss_feeds/
https://www.cshub.com/rss-feeds
https://www.ouest-france.fr/rss-en-continu.xml

**Failles de sécurité :**

- empêcher les injections SQL avec les prepared statements de PDO

- Bonnes pratiques de stockage des mots de passe dans la base de données : https://stackoverflow.com/questions/401656/secure-hash-and-salt-for-php-passwords?rq=1

- Failles CSRF pour attaquer des sites basés sur les cookies : https://security.stackexchange.com/questions/157061/how-does-csrf-correlate-with-same-origin-policy

- les failles XSS, qu'est ce que c'est ? https://www.youtube.com/watch?v=zv0kZKC6GAM

- stocker les tokens dans HttpOnly pour éviter les XSS : https://stackoverflow.com/questions/37800804/are-jwts-a-secure-option-for-user-authentication/37809063#37809063 https://www.owasp.org/index.php/HttpOnly

- empêcher le XSS depuis PHP : https://stackoverflow.com/questions/1996122/how-to-prevent-xss-with-html-php

Note : certains flux RSS que l'on afficherait sur le site pourraient être malveillants et contenir des injections de JavaScript. L'échappement des caractères spéciaux devra donc plutôt se faire côté client et pas côté serveur (PHP).

**Améliorations possibles :**

- Corps de requêtes à l'API en JSON : https://stackoverflow.com/questions/1973140/parsing-json-from-xmlhttprequest-responsejson

- Utilisation du serveur SMTP de Gmail pour l'envoi de mail en PHP : https://stackoverflow.com/questions/712392/send-email-using-the-gmail-smtp-server-from-a-php-page

- Suivre un workflow propre : https://www.nicoespeon.com/fr/2013/08/quel-git-workflow-pour-mon-projet/

## Installation

L'installation de Cyber-feed nécessite un serveur Apache 2 exécutant du PHP>7 et un serveur de base de données MySQL/MariaDB. Le fichier des variables d'environnement `env.php` doit également être créé à partir du fichier `env.example.php`.

## FAQ

**Pourquoi avoir choisi les tokens plutôt que les cookies/sessions ?**

- Le principal désavantage des cookies est qu'ils sont envoyés automatiquement par les navigateurs lors d'une requête vers un site, ce qui rend les sites très vulnérables aux attaques [CSRF (Cross-site Resource Forgery)][csrf]. En effet, si vous arrivez sur un site malveillant, celui-ci peut envoyer une requête HTTP vers un site ou vous êtes déjà connecté (donc votre cookie serait envoyé avec) sur une route qui supprimerait votre compte par exemple. La Same-origin Policy, une mesure des éditeurs de navigateur web, ne protège que très peu de ces failles, et d'autres mesures comme les tokens anti-CSRF demandent une implémentation par le développeur. En revanche, l'authentification par token que nous utilisons pour Cyber-feed n'est pas vulnérable à ce type d'attaque, car les tokens obtenus pour un site sont inaccessibles pour tous les autres sites.

- Les cookies sont fortement soumis à la loi, on doit notamment obtenir l'autorisation des utilisateurs pour en stocker sur leur navigateur, car ceux-ci peuvent être utilisés à des fins commerciales. Au contraire, de par leur conception, les tokens ne peuvent pas être utilisés pour des fins pécuniaires.

- Les APIs REST utilisent toutes un système d'authentification par token, car cela permet une universalité de la consommation : une API peut être consommée par une application sur navigateur comme une application sur iPhone ou encore une application Android. Ainsi, nous pourrions créer une application mobile Cyber-feed qui accèderait à la même base de données que le site web. Les cookies ne permettent pas de créer des API REST en revanche, car seuls les navigateurs web les utilisent.

**Pourquoi avoir choisi les tokens référence plutôt que les tokens auto-contenus (JWT, ...) ?**

- Les tokens auto-contenus, comme les [JWT (JSON Web Tokens)][jwt], ne nécessitent pas du tout de requête à la base de données pour être vérifiés. En effet, la vérification consiste juste à signer la charge utile du token avec une clé secrète (connue uniquement du serveur) et à comparer cette signature avec celle qui se trouve à la fin du token. Le problème majeur que l'on peut remarquer est que si un attaquant a accès à cette clé secrète, il peut générer les tokens de n'importe qui (pour peu qu'il connaisse les informations qui constitue leur charge utile). Pour Cyber-feed, nous utilisons plutôt des tokens référence, qui sont des tokens stockés dans la base de données.

Signer un token en se servant du hash du mot de passe de l'utilisateur empêcherait l'existence d'un unique secret pour tout le serveur qui permettrait de compromettre tous les comptes du site, sauf que cette technique nécessiterait de requêter la base de données à chaque fois pour vérifier un token, ce qui revient donc au même qu'utiliser des tokens référence. 

- Lorsqu'un téléphone a été perdu ou volé par exemple, on souhaite révoquer le token d'accès, sauf que si celui-ci est auto-contenu, il est très difficile 



[csrf]: https://fr.wikipedia.org/wiki/Cross-site_request_forgery
[jwt]: https://fr.wikipedia.org/wiki/JSON_Web_Token
