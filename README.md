# Wordpress Website: TC Illertissen e.V.

### TC Illertissen

## Domains Production
- [www.tci-illertissen.de](https://www.tci-illertissen.de/)


### Hoster
https://www.ionos.de/

### Develop environment
To run project locally you need to install docker and [ddev](https://ddev.com/get-started/)


### Deployment

Deployment is done via GitHub actions

#### Path on server
```
/kunden/homepages/41/d100076765/htdocs/clickandbuilds/TCIllertissen/
```

Deployment will be triggered automatically after each merge to **main** branch
All merges to master branch must be done via pull requests

#### Configuration

Wordpress configuration is located in `/kunden/homepages/41/d100076765/htdocs/clickandbuilds/TCIllertissen/wp-config.php` for prod
